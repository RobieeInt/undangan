import './bootstrap';

/**
 * Use Livewire's own ESM bundle so Alpine and Livewire share
 * the SAME instance. Never import 'alpinejs' separately on pages
 * that also use Livewire — it creates a second Alpine.
 *
 * The ESM bundle:
 *   - already bundles @alpinejs/persist (don't register it again)
 *   - auto-starts via DOMContentLoaded when livewireScriptConfig is absent
 *   - exports the shared Alpine instance for us to extend
 */
import { Alpine, Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';

// ── Music player refs — stored OUTSIDE Alpine's reactive proxy
//    to avoid Proxy interference with complex YT.Player / Audio objects
window._musicAudio   = null;
window._musicYt      = null;   // YT.Player instance
window._musicYtReady = false;
window._musicYtPend  = false;  // play requested before player ready

// ── Global Alpine Stores ──────────────────────────────────
Alpine.store('invitation', {
    musicPlaying:   false,
    isYoutube:      false,
    envelopeOpened: false,

    _isYoutubeUrl(url) {
        return url && (url.includes('youtube.com') || url.includes('youtu.be'));
    },

    _extractYouTubeId(url) {
        const patterns = [
            /[?&]v=([^&#]+)/,
            /youtu\.be\/([^?&#]+)/,
            /youtube\.com\/embed\/([^?&#]+)/,
            /youtube\.com\/shorts\/([^?&#]+)/,
        ];
        for (const p of patterns) {
            const m = url.match(p);
            if (m) return m[1];
        }
        return null;
    },

    initMusic(url, autoplay = false) {
        if (!url) return;
        window._musicAutoplay = autoplay;

        if (this._isYoutubeUrl(url)) {
            this.isYoutube = true;
            const videoId = this._extractYouTubeId(url);
            if (!videoId) return;

            // Hidden container replaced by YT.Player
            const el = document.createElement('div');
            el.id = 'yt-music-player';
            el.style.cssText = 'position:fixed;width:1px;height:1px;bottom:-999px;left:-999px;overflow:hidden;pointer-events:none;';
            document.body.appendChild(el);

            const init = () => {
                window._musicYt = new window.YT.Player('yt-music-player', {
                    width: '1', height: '1',
                    videoId,
                    playerVars: { autoplay: 0, controls: 0, loop: 1, playlist: videoId, rel: 0, playsinline: 1 },
                    events: {
                        onReady() {
                            window._musicYtReady = true;
                            if (window._musicYtPend) {
                                window._musicYt.playVideo();
                                window._musicYtPend = false;
                            }
                        },
                        onError(e) {
                            const msg = {
                                2:   'Parameter video tidak valid.',
                                5:   'Video tidak bisa diputar di browser ini.',
                                100: 'Video tidak ditemukan atau sudah dihapus.',
                                101: 'Pemilik video melarang pemutaran via embed.',
                                150: 'Pemilik video melarang pemutaran via embed.',
                            }[e.data] || `Error YouTube (${e.data})`;
                            console.warn('[Musik] YouTube error:', msg);
                            window._musicYtError = msg;
                            // Reset state so button doesn't get stuck
                            Alpine.store('invitation').musicPlaying = false;
                        }
                    },
                });
            };

            if (window.YT && window.YT.Player) {
                init();
            } else {
                const prev = window.onYouTubeIframeAPIReady;
                window.onYouTubeIframeAPIReady = () => { if (typeof prev === 'function') prev(); init(); };
                if (!document.getElementById('yt-iframe-api')) {
                    const s = document.createElement('script');
                    s.id = 'yt-iframe-api';
                    s.src = 'https://www.youtube.com/iframe_api';
                    document.head.appendChild(s);
                }
            }

        } else {
            this.isYoutube = false;
            window._musicAudio = new Audio(url);
            window._musicAudio.loop = true;
        }
    },

    toggleMusic() {
        if (this.isYoutube) {
            if (!window._musicYt) return;
            if (!window._musicYtReady) {
                // Queue play for when player is ready
                window._musicYtPend = !this.musicPlaying;
                this.musicPlaying   = !this.musicPlaying;
                return;
            }
            if (this.musicPlaying) {
                window._musicYt.pauseVideo();
            } else {
                window._musicYt.playVideo();
            }
            this.musicPlaying = !this.musicPlaying;
        } else {
            if (!window._musicAudio) return;
            if (this.musicPlaying) {
                window._musicAudio.pause();
            } else {
                window._musicAudio.play().catch(() => {});
            }
            this.musicPlaying = !this.musicPlaying;
        }
    },

    openEnvelope() {
        this.envelopeOpened = true;
        // Only auto-start music if music_autoplay was enabled
        if (window._musicAutoplay && !this.musicPlaying) this.toggleMusic();
    }
});

// ── Alpine Components ─────────────────────────────────────
Alpine.data('countdown', (targetDate) => ({
    days: 0, hours: 0, minutes: 0, seconds: 0,
    interval: null,
    init() {
        this.tick();
        this.interval = setInterval(() => this.tick(), 1000);
    },
    tick() {
        const now = new Date().getTime();
        const diff = new Date(targetDate).getTime() - now;
        if (diff <= 0) { this.days = this.hours = this.minutes = this.seconds = 0; clearInterval(this.interval); return; }
        this.days    = Math.floor(diff / 86400000);
        this.hours   = Math.floor((diff % 86400000) / 3600000);
        this.minutes = Math.floor((diff % 3600000) / 60000);
        this.seconds = Math.floor((diff % 60000) / 1000);
    },
    destroy() { clearInterval(this.interval); }
}));

// ── YouTube embed permission tester (used in editor music tab) ────────────
// Creates a real YT.Player, calls playVideo(), and checks for error 101/150.
// On completion calls $wire.setYoutubeEmbedStatus(status) to update Livewire.
Alpine.data('ytEmbedChecker', (videoId) => ({
    _player: null,
    _timer:  null,

    init() {
        if (!videoId) return;
        this._runCheck(videoId);
    },

    destroy() {
        clearTimeout(this._timer);
        try { if (this._player) this._player.destroy(); } catch(e) {}
    },

    _runCheck(vid) {
        const self = this;

        // Container the player replaces
        const el = document.createElement('div');
        el.style.cssText = 'position:fixed;width:2px;height:2px;bottom:-9999px;left:-9999px;pointer-events:none;';
        document.body.appendChild(el);

        // Give up after 10s
        self._timer = setTimeout(() => { cleanup('unknown'); }, 10000);

        function cleanup(status) {
            clearTimeout(self._timer);
            try { if (self._player) { self._player.destroy(); self._player = null; } } catch(e) {}
            el.remove();
            if (self.$wire) self.$wire.setYoutubeEmbedStatus(status);
        }

        function create() {
            self._player = new window.YT.Player(el, {
                videoId: vid,
                width: '2', height: '2',
                playerVars: { autoplay: 0, controls: 0, mute: 1, playsinline: 1 },
                events: {
                    onReady(e) {
                        // Trigger playback so YouTube checks embed permission
                        try { e.target.playVideo(); } catch(ex) {}
                        // If no error in 4s, assume ok
                        self._timer = setTimeout(() => cleanup('ok'), 4000);
                    },
                    onStateChange(e) {
                        // State 1 = playing — definitely ok
                        if (e.data === 1) {
                            try { e.target.stopVideo(); } catch(ex) {}
                            cleanup('ok');
                        }
                    },
                    onError(e) {
                        cleanup(e.data === 101 || e.data === 150 ? 'blocked' : 'unknown');
                    },
                },
            });
        }

        if (window.YT && window.YT.Player) {
            create();
        } else {
            const prev = window.onYouTubeIframeAPIReady;
            window.onYouTubeIframeAPIReady = () => { if (typeof prev === 'function') prev(); create(); };
            if (!document.getElementById('yt-iframe-api')) {
                const s = document.createElement('script');
                s.id = 'yt-iframe-api'; s.src = 'https://www.youtube.com/iframe_api';
                document.head.appendChild(s);
            }
        }
    },
}));

Alpine.data('saveToCalendar', (event) => ({
    addToCalendar() {
        const start = event.date.replace(/-/g, '') + 'T' + event.time.replace(/:/g, '') + '00';
        const url = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(event.title)}&dates=${start}/${start}&details=${encodeURIComponent(event.description)}&location=${encodeURIComponent(event.location)}`;
        window.open(url, '_blank');
    }
}));

// Global helper — some templates call saveToCalendar({...}) directly in Alpine @click
// expressions instead of using x-data="saveToCalendar(...)". This satisfies both patterns.
window.saveToCalendar = function ({ title, start, end, location, description }) {
    const s = start.replace(/[-:]/g, '').replace(' ', 'T').substring(0, 15);
    const e = (end || start).replace(/[-:]/g, '').replace(' ', 'T').substring(0, 15);
    const url = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
        + '&text='     + encodeURIComponent(title || '')
        + '&dates='    + s + '/' + e
        + '&details='  + encodeURIComponent(description || '')
        + '&location=' + encodeURIComponent(location || '');
    window.open(url, '_blank');
};

Alpine.data('qrScanner', (livewireComponent) => ({
    scanning: false,
    stream: null,
    animFrame: null,
    lastScan: null,
    cooldown: false,   // prevent duplicate triggers

    async startScan() {
        this.scanning = true;
        await this.$nextTick();

        const video  = document.getElementById('qr-video');
        const canvas = document.getElementById('qr-canvas');

        try {
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 } }
            });
            video.srcObject = this.stream;
            await video.play();
        } catch (e) {
            this.scanning = false;
            alert('Tidak bisa akses kamera. Pastikan izin kamera sudah diberikan.');
            return;
        }

        const { default: jsQR } = await import('jsqr');
        const ctx = canvas.getContext('2d', { willReadFrequently: true });

        const tick = () => {
            if (!this.scanning) return;
            if (video.readyState >= HTMLMediaElement.HAVE_ENOUGH_DATA) {
                canvas.width  = video.videoWidth;
                canvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0);
                const img  = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(img.data, img.width, img.height, { inversionAttempts: 'dontInvert' });
                if (code && !this.cooldown && code.data !== this.lastScan) {
                    this.lastScan = code.data;
                    this.cooldown = true;
                    // QR contains: http://host/checkin/verify/{token}
                    // Token is the last path segment, NOT a query param
                    let token = code.data;
                    try {
                        const u = new URL(code.data);
                        const seg = u.pathname.split('/').filter(s => s.length > 0);
                        if (seg.length > 0) token = seg[seg.length - 1];
                    } catch {}
                    livewireComponent.call('processToken', token);
                    setTimeout(() => { this.cooldown = false; this.lastScan = null; }, 3000); // 3s cooldown
                }
            }
            this.animFrame = requestAnimationFrame(tick);
        };
        this.animFrame = requestAnimationFrame(tick);
    },

    stopScan() {
        this.scanning = false;
        cancelAnimationFrame(this.animFrame);
        if (this.stream) { this.stream.getTracks().forEach(t => t.stop()); this.stream = null; }
        this.lastScan = null;
        this.cooldown = false;
    }
}));

Alpine.data('autoSave', (delay = 2000) => ({
    timer: null, status: '',
    scheduleAutoSave(livewireComponent) {
        this.status = 'Menyimpan...';
        clearTimeout(this.timer);
        this.timer = setTimeout(() => { livewireComponent.call('autoSave'); this.status = 'Tersimpan ✓'; }, delay);
    }
}));

window.Alpine = Alpine;

// @livewireScriptConfig in <head> sets window.livewireScriptConfig before
// this module runs, which disables the ESM auto-start listener.
// We must therefore call Livewire.start() explicitly here.
// Stores & data above are registered BEFORE start(), so Alpine picks them up.
Livewire.start();
