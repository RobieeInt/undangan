{{--
════════════════════════════════════════════════════════════════════════
 CINEMATIC OPENING — Universal add-on for all invitation templates
════════════════════════════════════════════════════════════════════════
 Required additions in each template file:

   1. Cover div          → add  data-coi-cover
   2. Main content div   → add  data-coi-main
   3. Buka Undangan btn  → add  data-coi-btn
   4. Before </body>     → @include('partials.cinematic-opening')

 Works with any existing id/class attributes — no conflicts.
════════════════════════════════════════════════════════════════════════
--}}

{{-- ══ 1. CSS ══════════════════════════════════════════════════════ --}}
<style>
/* ── Cover: GPU compositing ─────────────────────────────────── */
[data-coi-cover] {
    will-change: transform, opacity, filter;
    transform-origin: center center;
    overflow: hidden;
}

/* ── Glow pulse injected into cover ────────────────────────── */
#coi-glow {
    position: absolute; inset: 0; pointer-events: none; z-index: 8;
    background: radial-gradient(ellipse 80% 55% at 50% 50%,
        rgba(255,255,255,0.09) 0%,
        rgba(255,255,255,0.02) 45%,
        transparent 70%);
    animation: coiGlowPulse 4s ease-in-out infinite;
    will-change: opacity, transform;
}
@keyframes coiGlowPulse {
    0%, 100% { opacity: 0.5; transform: scale(1);    }
    50%      { opacity: 1;   transform: scale(1.06); }
}

/* ── Particles container ────────────────────────────────────── */
#coi-particles {
    position: absolute; inset: 0;
    overflow: hidden; pointer-events: none; z-index: 7;
}
.coi-p {
    position: absolute; border-radius: 50%;
    will-change: transform, opacity;
    animation: coiPRise var(--pd,5s) ease-in-out var(--dl,0s) infinite;
}
@keyframes coiPRise {
    0%   { transform: translateY(0) translateX(0) scale(1);            opacity: 0; }
    10%  { opacity: 1; }
    90%  { opacity: 0.4; }
    100% { transform: translateY(-140px) translateX(var(--dx,20px)) scale(0.2); opacity: 0; }
}

/* ── Floating botanical ornaments ───────────────────────────── */
.coi-floral {
    position: absolute; pointer-events: none; z-index: 6;
    will-change: transform;
    animation: coiFloralDrift var(--fd,7s) ease-in-out var(--fdl,0s) infinite;
}
@keyframes coiFloralDrift {
    0%, 100% { transform: translateY(0)     rotate(var(--fr,0deg)) scale(1);    }
    33%      { transform: translateY(-14px) rotate(calc(var(--fr,0deg) + 6deg)) scale(1.02); }
    66%      { transform: translateY(7px)   rotate(calc(var(--fr,0deg) - 4deg)) scale(0.98); }
}

/* ── Slow-spin gradient on cover ────────────────────────────── */
#coi-grad {
    position: absolute; inset: -20%; pointer-events: none; z-index: 5;
    background: conic-gradient(
        from 0deg at 30% 50%,
        transparent 0deg,
        rgba(255,255,255,0.03) 60deg,
        transparent 120deg,
        rgba(255,255,255,0.02) 200deg,
        transparent 360deg
    );
    animation: coiGradSpin 20s linear infinite;
    will-change: transform;
}
@keyframes coiGradSpin { to { transform: rotate(360deg); } }

/* ── Suppress Alpine transitions during GSAP hand-off ───────── */
.coi-bypass,
.coi-bypass [x-show] {
    transition: none !important;
    animation-duration: 0.001ms !important;
}

/* ── Main content: NO will-change here — it creates a stacking
   context that breaks position:fixed children (navbar displaced).
   will-change is set temporarily by GSAP during animation only. ── */

/* ════════════════════════════════════════════════════════════
   SPLIT REVEAL — Theme-aware door panels
   Panels sit at z-index:101 (above cover z-[100])
   ════════════════════════════════════════════════════════════ */
#coi-panel-l,
#coi-panel-r {
    position: fixed; top: 0; height: 100%; width: 50%;
    z-index: 101; will-change: transform; overflow: hidden;
    backface-visibility: hidden; -webkit-backface-visibility: hidden;
    pointer-events: none;
}
#coi-panel-l { left: 0; }
#coi-panel-r { right: 0; }

/* ── Center seam glow line ───────────────────────────────── */
#coi-seam {
    position: fixed; top: 0; left: 50%;
    transform: translateX(-50%);
    width: 2px; height: 100%; z-index: 102;
    pointer-events: none; will-change: opacity;
    animation: coiSeamBreath 2.8s ease-in-out infinite;
}
@keyframes coiSeamBreath {
    0%, 100% { opacity: 0.55; }
    50%       { opacity: 1.0;  }
}
</style>

{{-- ══ 2. CDN Scripts ══════════════════════════════════════════════ --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
{{-- Lenis dihapus — native scroll, tidak ada lag --}}

{{-- ══ 3. Cinematic JS ══════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    /* ── Poll until GSAP is loaded ──────────────────────────── */
    function whenReady(cb, n) {
        if (typeof gsap !== 'undefined') {
            cb();
        } else if ((n || 0) < 100) {
            setTimeout(function () { whenReady(cb, (n || 0) + 1); }, 50);
        }
    }
    whenReady(setup);

    /* ══════════════════════════════════════════════════════════
       SETUP
    ══════════════════════════════════════════════════════════ */
    function setup() {
        gsap.registerPlugin(ScrollTrigger);

        var cover = document.querySelector('[data-coi-cover]');
        var main  = document.querySelector('[data-coi-main]');

        /* ── Fallback: Lenis only, for templates with their own animation ── */
        if (!cover || !main) {
            initLenisWhenOpened();
            return;
        }

        /* inject decorative layers into cover */
        injectCoverLayers(cover);

        /* main dibiarkan di posisi normal — panels yang akan menutupinya
           saat klik, lalu slide buka. Tidak perlu pre-set off-screen. */

        /* idle slow-zoom on cover background image */
        var bgImg = cover.querySelector('img');
        if (bgImg) {
            bgImg.style.willChange = 'transform';
            bgImg.style.transformOrigin = 'center center';
            gsap.to(bgImg, {
                scale: 1.08, duration: 9,
                ease: 'sine.inOut', yoyo: true, repeat: -1,
            });
        }

        /* ── capture-phase listener so it fires BEFORE Alpine @click ── */
        var _clicked = false;
        document.addEventListener('click', function (e) {
            if (!e.target.closest('[data-coi-btn]')) return;
            if (_clicked) return;   /* prevent double-fire */
            _clicked = true;
            e.stopImmediatePropagation();
            /* Build panels at click-time: they appear full-screen then
               immediately animate out — cover screen is unobstructed
               until user presses "Buka Undangan". */
            var doors = buildSplitPanels();
            runTimeline(cover, main, bgImg, doors);
        }, true);
    }

    /* ══════════════════════════════════════════════════════════
       INJECT COVER DECORATIVE LAYERS (all via JS → no HTML edits)
    ══════════════════════════════════════════════════════════ */
    function injectCoverLayers(cover) {
        /* slow-spin gradient */
        var grad = document.createElement('div'); grad.id = 'coi-grad';
        cover.appendChild(grad);

        /* luxury glow pulse */
        var glow = document.createElement('div'); glow.id = 'coi-glow';
        cover.appendChild(glow);

        /* floating particles */
        var pWrap = document.createElement('div'); pWrap.id = 'coi-particles';
        cover.appendChild(pWrap);
        spawnParticles(pWrap, 22);

        /* botanical ornaments */
        injectBotanicals(cover);
    }

    /* ══════════════════════════════════════════════════════════
       PARTICLES
    ══════════════════════════════════════════════════════════ */
    function spawnParticles(wrap, n) {
        var COLS = [
            'rgba(255,255,255,0.75)',
            'rgba(255,220,180,0.65)',
            'rgba(210,230,210,0.55)',
            'rgba(255,200,220,0.65)',
            'rgba(240,220,180,0.55)',
        ];
        for (var i = 0; i < n; i++) {
            var el  = document.createElement('div');
            el.className = 'coi-p';
            var sz  = 2 + Math.random() * 3.5;
            var col = COLS[i % COLS.length];
            var dx  = (Math.random() - 0.5) * 72;
            var pd  = 3.5 + Math.random() * 5.5;
            var dl  = -(Math.random() * pd);
            el.style.cssText =
                'width:'  + sz + 'px;height:' + sz + 'px;' +
                'background:' + col + ';' +
                'box-shadow:0 0 ' + (sz * 2.5) + 'px ' + col + ';' +
                'left:' + (Math.random() * 100) + '%;' +
                'bottom:' + (Math.random() * 55)  + '%;' +
                '--dx:' + dx + 'px;--pd:' + pd + 's;--dl:' + dl + 's;';
            wrap.appendChild(el);

            /* extra GSAP glow blink on ~half the particles */
            if (i % 2 === 0) {
                gsap.to(el, {
                    opacity: 0.25, duration: 0.7 + Math.random(),
                    yoyo: true, repeat: -1, ease: 'sine.inOut',
                    delay: Math.random() * 2,
                });
            }
        }
    }

    /* ══════════════════════════════════════════════════════════
       BOTANICAL SVG ORNAMENTS
       NOTE: BOTS is declared inside injectBotanicals (not at
       IIFE scope) to avoid the same CDN-script timing issue.
    ══════════════════════════════════════════════════════════ */
    function botanicalSvg(s) {
        var h = Math.round(s * 1.3);
        return '<svg viewBox="0 0 100 130" fill="currentColor" xmlns="http://www.w3.org/2000/svg"' +
            ' width="' + s + '" height="' + h + '" style="color:#fff">' +
            '<path d="M50 125 Q48 92 50 58 Q52 24 50 8" stroke="currentColor" stroke-width="1.4" fill="none" opacity="0.55"/>' +
            '<path d="M50 100 Q32 88 23 70 Q36 78 50 84" opacity="0.5"/>' +
            '<path d="M50 100 Q68 88 77 70 Q64 78 50 84" opacity="0.5"/>' +
            '<path d="M50 78  Q29 66 19 46 Q34 55 50 62" opacity="0.4"/>' +
            '<path d="M50 78  Q71 66 81 46 Q66 55 50 62" opacity="0.4"/>' +
            '<path d="M50 56  Q33 44 26 28 Q39 37 50 42" opacity="0.35"/>' +
            '<path d="M50 56  Q67 44 74 28 Q61 37 50 42" opacity="0.35"/>' +
            '<circle cx="50" cy="8" r="4.5" opacity="0.5"/>' +
            '</svg>';
    }
    function injectBotanicals(cover) {
        var BOTS = [
            { pos:'top:4%;left:1%',    w:90, op:0.12, fr:'-15deg', fd:7,   fdl:0    },
            { pos:'top:3%;right:2%',   w:80, op:0.10, fr:'20deg',  fd:8,   fdl:-2.5 },
            { pos:'bottom:5%;left:3%', w:72, op:0.12, fr:'-10deg', fd:9,   fdl:-4   },
            { pos:'bottom:4%;right:2%',w:84, op:0.10, fr:'18deg',  fd:7.5, fdl:-1.5 },
            { pos:'top:43%;left:-3%',  w:58, op:0.07, fr:'-22deg', fd:10,  fdl:-3   },
        ];
        BOTS.forEach(function (b) {
            var el = document.createElement('div');
            el.className = 'coi-floral';
            el.style.cssText =
                b.pos + ';width:' + b.w + 'px;height:' + Math.round(b.w*1.3) + 'px;' +
                'opacity:' + b.op + ';--fr:' + b.fr + ';--fd:' + b.fd + 's;--fdl:' + b.fdl + 's;';
            el.innerHTML = botanicalSvg(b.w);
            cover.appendChild(el);
        });
    }

    /* ══════════════════════════════════════════════════════════
       REVEAL FLOWERS — Jasmine blooms float up during door reveal
    ══════════════════════════════════════════════════════════ */
    function jasmineFlowerSvgStr(sz) {
        var d = sz * 2;
        return '<svg width="' + d + '" height="' + d +
               '" viewBox="-10 -10 20 20" xmlns="http://www.w3.org/2000/svg">' +
               '<ellipse cx="0" cy="-5.5" rx="3.1" ry="5.5" fill="white" opacity=".88"/>' +
               '<ellipse cx="0" cy="-5.5" rx="3.1" ry="5.5" fill="white" opacity=".82" transform="rotate(72)"/>' +
               '<ellipse cx="0" cy="-5.5" rx="3.1" ry="5.5" fill="white" opacity=".82" transform="rotate(144)"/>' +
               '<ellipse cx="0" cy="-5.5" rx="3.1" ry="5.5" fill="white" opacity=".82" transform="rotate(216)"/>' +
               '<ellipse cx="0" cy="-5.5" rx="3.1" ry="5.5" fill="white" opacity=".82" transform="rotate(288)"/>' +
               '<circle cx="0" cy="0" r="2.1" fill="#C9A86A" opacity=".95"/>' +
               '</svg>';
    }

    function spawnRevealFlowers() {
        var wrap = document.createElement('div');
        wrap.id  = 'coi-flower-burst';
        wrap.style.cssText =
            'position:fixed;inset:0;z-index:104;pointer-events:none;overflow:hidden;';
        document.body.appendChild(wrap);

        /* Flowers clustered near the center seam, rising as doors open */
        var specs = [
            {xp:47,  yp:88, sz:16, dl:0,    rx:-20, sway:-42},
            {xp:53,  yp:91, sz:12, dl:0.12, rx:25,  sway:36},
            {xp:44,  yp:82, sz:10, dl:0.25, rx:-35, sway:-28},
            {xp:56,  yp:85, sz:14, dl:0.18, rx:18,  sway:26},
            {xp:50,  yp:94, sz:9,  dl:0.08, rx:-12, sway:-16},
            {xp:42,  yp:76, sz:11, dl:0.40, rx:30,  sway:-52},
            {xp:58,  yp:78, sz:13, dl:0.32, rx:-22, sway:46},
            {xp:48,  yp:68, sz:8,  dl:0.55, rx:40,  sway:-22},
            {xp:52,  yp:70, sz:10, dl:0.48, rx:-30, sway:32},
            {xp:45,  yp:60, sz:12, dl:0.62, rx:15,  sway:-38},
            {xp:55,  yp:56, sz:9,  dl:0.70, rx:-25, sway:20},
            {xp:50,  yp:48, sz:11, dl:0.80, rx:35,  sway:-26},
            {xp:46,  yp:42, sz:8,  dl:0.92, rx:-40, sway:42},
            {xp:54,  yp:38, sz:10, dl:1.04, rx:20,  sway:-32},
        ];

        specs.forEach(function (s) {
            var el = document.createElement('div');
            el.style.cssText =
                'position:absolute;' +
                'left:' + s.xp + '%;top:' + s.yp + '%;' +
                'margin-left:-' + s.sz + 'px;margin-top:-' + s.sz + 'px;' +
                'opacity:0;will-change:transform,opacity;';
            el.innerHTML = jasmineFlowerSvgStr(s.sz);
            wrap.appendChild(el);

            var upDist = 115 + Math.random() * 155;
            gsap.fromTo(el,
                { opacity: 0, y: 0, x: 0, rotation: 0, scale: 0.4 },
                {
                    opacity: 0.78,
                    y: -upDist,
                    x: s.sway,
                    rotation: s.rx,
                    scale: 1,
                    duration: 2.0 + Math.random() * 1.4,
                    delay: s.dl,
                    ease: 'power1.out',
                    onComplete: function () {
                        gsap.to(el, { opacity: 0, duration: 0.5, ease: 'power1.in' });
                    }
                }
            );
        });

        setTimeout(function () {
            if (wrap.parentNode) wrap.parentNode.removeChild(wrap);
        }, 6500);
    }

    /* ══════════════════════════════════════════════════════════
       POST-REVEAL EFFECTS — Themed particles after panels open
       Fires at t≈1.0 (panels fully slid away).
       Each theme gets its own particle style.
    ══════════════════════════════════════════════════════════ */

    /* ── SVG helpers ──────────────────────────────────────── */
    function rosePetalHtml(sz) {
        var d   = sz * 2;
        var cols = ['#f9a8d4','#fbcfe8','#fce7f3'];
        var col = cols[Math.floor(Math.random() * 3)];
        return '<svg width="' + d + '" height="' + d +
               '" viewBox="-10 -10 20 20" xmlns="http://www.w3.org/2000/svg">' +
               '<ellipse rx="6" ry="9" fill="' + col + '" opacity=".82"/>' +
               '<ellipse rx="6" ry="9" fill="' + col + '" opacity=".65" transform="rotate(60)"/>' +
               '<ellipse rx="6" ry="9" fill="' + col + '" opacity=".65" transform="rotate(120)"/>' +
               '<ellipse rx="6" ry="9" fill="' + col + '" opacity=".65" transform="rotate(180)"/>' +
               '<ellipse rx="6" ry="9" fill="' + col + '" opacity=".65" transform="rotate(240)"/>' +
               '<ellipse rx="6" ry="9" fill="' + col + '" opacity=".65" transform="rotate(300)"/>' +
               '<circle r="3" fill="#f472b6" opacity=".7"/>' +
               '</svg>';
    }

    function islamicStarHtml(sz) {
        var d   = sz * 2;
        var col = Math.random() > 0.5 ? '#306D29' : '#0D530E';
        return '<svg width="' + d + '" height="' + d +
               '" viewBox="-10 -10 20 20" xmlns="http://www.w3.org/2000/svg">' +
               '<ellipse rx="3" ry="8" fill="' + col + '" opacity=".88"/>' +
               '<ellipse rx="3" ry="8" fill="' + col + '" opacity=".82" transform="rotate(45)"/>' +
               '<ellipse rx="3" ry="8" fill="' + col + '" opacity=".82" transform="rotate(90)"/>' +
               '<ellipse rx="3" ry="8" fill="' + col + '" opacity=".82" transform="rotate(135)"/>' +
               '<circle r="2.5" fill="#FBF5DD" opacity=".75"/>' +
               '</svg>';
    }

    function royalDiamondHtml(sz) {
        var d = sz * 2;
        return '<svg width="' + d + '" height="' + d +
               '" viewBox="-10 -10 20 20" xmlns="http://www.w3.org/2000/svg">' +
               '<polygon points="0,-8 6,0 0,8 -6,0" fill="#D4AF37" opacity=".88"/>' +
               '<polygon points="0,-4 3,0 0,4 -3,0" fill="#fff" opacity=".5"/>' +
               '</svg>';
    }

    function silverStarHtml(sz, col) {
        var d = sz * 2;
        return '<svg width="' + d + '" height="' + d +
               '" viewBox="-10 -10 20 20" xmlns="http://www.w3.org/2000/svg">' +
               '<path d="M0,-9 L1.8,-1.8 L9,0 L1.8,1.8 L0,9 L-1.8,1.8 L-9,0 L-1.8,-1.8 Z"' +
               ' fill="' + col + '" opacity=".9"/>' +
               '<circle r="2.2" fill="' + col + '" opacity=".95"/>' +
               '</svg>';
    }

    function sparkDotHtml(sz, col) {
        var d = sz * 2;
        return '<svg width="' + d + '" height="' + d +
               '" viewBox="-10 -10 20 20" xmlns="http://www.w3.org/2000/svg">' +
               '<circle r="4.5" fill="' + col + '" opacity=".85"/>' +
               '<line x1="0" y1="-9" x2="0" y2="-5.5" stroke="' + col + '" stroke-width="1.5" opacity=".6"/>' +
               '<line x1="0" y1="5.5" x2="0" y2="9" stroke="' + col + '" stroke-width="1.5" opacity=".6"/>' +
               '<line x1="-9" y1="0" x2="-5.5" y2="0" stroke="' + col + '" stroke-width="1.5" opacity=".6"/>' +
               '<line x1="5.5" y1="0" x2="9" y2="0" stroke="' + col + '" stroke-width="1.5" opacity=".6"/>' +
               '<line x1="-7" y1="-7" x2="-4" y2="-4" stroke="' + col + '" stroke-width="1" opacity=".4"/>' +
               '<line x1="4" y1="4" x2="7" y2="7" stroke="' + col + '" stroke-width="1" opacity=".4"/>' +
               '</svg>';
    }

    /* ── Dispatcher ───────────────────────────────────────── */
    function spawnPostRevealEffect(theme) {
        if (!theme || theme === 'skip' || theme === 'default') return;

        var wrap    = document.createElement('div');
        wrap.id     = 'coi-post-reveal';
        wrap.style.cssText =
            'position:fixed;inset:0;z-index:103;pointer-events:none;overflow:hidden;';
        document.body.appendChild(wrap);

        switch (theme) {
            case 'floral':        _postFloral(wrap);       break;
            case 'luxury':        _postLuxury(wrap);       break;
            case 'dark-elegant':  _postDarkElegant(wrap);  break;
            case 'islamic':   _postIslamic(wrap);   break;
            case 'jawa':      _postJawa(wrap);      break;
            case 'minimal':   _postMinimal(wrap);   break;
            case 'royal':     _postRoyal(wrap);     break;
            case 'butterfly': _postButterfly(wrap); break;
        }

        setTimeout(function () {
            if (wrap.parentNode) wrap.parentNode.removeChild(wrap);
        }, 7500);
    }

    /* ── FLORAL: Pink rose petals drift down ──────────────── */
    function _postFloral(wrap) {
        for (var i = 0; i < 18; i++) {
            (function (i) {
                var sz = 7 + Math.random() * 8;
                var el = document.createElement('div');
                el.style.cssText =
                    'position:absolute;left:' + (Math.random() * 100) + '%;top:-5%;' +
                    'opacity:0;will-change:transform,opacity;';
                el.innerHTML = rosePetalHtml(sz);
                wrap.appendChild(el);
                gsap.fromTo(el,
                    { opacity: 0, y: 0, x: 0, rotation: Math.random() * 360, scale: 0.4 },
                    {
                        opacity: 0.88,
                        y: window.innerHeight * (0.55 + Math.random() * 0.35),
                        x: (Math.random() - 0.5) * 180,
                        rotation: '+=' + ((Math.random() - 0.5) * 180),
                        scale: 0.75 + Math.random() * 0.45,
                        duration: 3.5 + Math.random() * 2.5,
                        delay: Math.random() * 1.8,
                        ease: 'power1.inOut',
                        onComplete: function () {
                            gsap.to(el, { opacity: 0, duration: 0.6, ease: 'power1.in' });
                        }
                    }
                );
            })(i);
        }
    }

    /* ── LUXURY: Gold sparkle burst radiating from center ─── */
    function _postLuxury(wrap) {
        for (var i = 0; i < 26; i++) {
            (function (i) {
                var sz    = 5 + Math.random() * 7;
                var angle = (i / 26) * Math.PI * 2;
                var dist  = 90 + Math.random() * 190;
                var el    = document.createElement('div');
                el.style.cssText =
                    'position:absolute;left:50%;top:50%;' +
                    'margin-left:-' + sz + 'px;margin-top:-' + sz + 'px;' +
                    'opacity:0;will-change:transform,opacity;';
                el.innerHTML = sparkDotHtml(sz, '#C9A84C');
                wrap.appendChild(el);
                gsap.fromTo(el,
                    { opacity: 0, x: 0, y: 0, scale: 0, rotation: 0 },
                    {
                        opacity: 0.9,
                        x: Math.cos(angle) * dist,
                        y: Math.sin(angle) * dist,
                        scale: 1,
                        rotation: Math.random() * 360,
                        duration: 1.4 + Math.random() * 0.9,
                        delay: Math.random() * 0.5,
                        ease: 'power2.out',
                        onComplete: function () {
                            gsap.to(el, {
                                opacity: 0, scale: 0.3,
                                duration: 0.9, delay: 0.3, ease: 'power1.in'
                            });
                        }
                    }
                );
            })(i);
        }
    }

    /* ── DARK ELEGANT: Silver stars cascade + twinkle ────── */
    function _postDarkElegant(wrap) {
        var FALL_COLS = ['#E5E7EB','#D4D4D8','#E5E7EB','#C9A84C','#F3F4F6'];

        /* 28 stars rain down from top — most silver, some gold */
        for (var i = 0; i < 28; i++) {
            (function (i) {
                var sz   = 4 + Math.random() * 7;
                var col  = FALL_COLS[i % FALL_COLS.length];
                var el   = document.createElement('div');
                el.style.cssText =
                    'position:absolute;left:' + (Math.random() * 100) + '%;top:-5%;' +
                    'opacity:0;will-change:transform,opacity;';
                el.innerHTML = silverStarHtml(sz, col);
                wrap.appendChild(el);

                var fallDist = window.innerHeight * (0.38 + Math.random() * 0.45);
                gsap.fromTo(el,
                    { opacity: 0, y: 0, x: 0, rotation: 0, scale: 0.2 },
                    {
                        opacity: 0.9,
                        y: fallDist,
                        x: (Math.random() - 0.5) * 110,
                        rotation: Math.random() * 540,
                        scale: 1,
                        duration: 2.2 + Math.random() * 2.2,
                        delay: Math.random() * 2.0,
                        ease: 'power1.inOut',
                        onComplete: function () {
                            gsap.to(el, {
                                opacity: 0, scale: 0.3,
                                duration: 0.7, ease: 'power1.in'
                            });
                        }
                    }
                );
            })(i);
        }

        /* 8 twinkling stationary stars scattered across screen */
        for (var j = 0; j < 8; j++) {
            (function (j) {
                var sz  = 5 + Math.random() * 5;
                var col = j % 3 === 0 ? '#C9A84C' : '#E5E7EB';
                var el  = document.createElement('div');
                el.style.cssText =
                    'position:absolute;' +
                    'left:' + (5 + Math.random() * 90) + '%;' +
                    'top:' + (10 + Math.random() * 75) + '%;' +
                    'opacity:0;will-change:transform,opacity;';
                el.innerHTML = silverStarHtml(sz, col);
                wrap.appendChild(el);

                gsap.fromTo(el,
                    { opacity: 0, scale: 0, rotation: -90 },
                    {
                        opacity: 0.95, scale: 1, rotation: 0,
                        duration: 0.45 + Math.random() * 0.3,
                        delay: 0.4 + Math.random() * 1.8,
                        ease: 'back.out(2.5)',
                        onComplete: function () {
                            /* pulse-twinkle 3 times then vanish */
                            gsap.to(el, {
                                opacity: 0.25, duration: 0.38,
                                yoyo: true, repeat: 5, ease: 'sine.inOut',
                                onComplete: function () {
                                    gsap.to(el, {
                                        opacity: 0, scale: 0,
                                        rotation: 90,
                                        duration: 0.45, ease: 'power1.in'
                                    });
                                }
                            });
                        }
                    }
                );
            })(j);
        }
    }

    /* ── ISLAMIC: Emerald stars float upward ──────────────── */
    function _postIslamic(wrap) {
        for (var i = 0; i < 16; i++) {
            (function (i) {
                var sz = 8 + Math.random() * 10;
                var el = document.createElement('div');
                el.style.cssText =
                    'position:absolute;' +
                    'left:' + (5 + Math.random() * 90) + '%;' +
                    'top:'  + (55 + Math.random() * 40) + '%;' +
                    'opacity:0;will-change:transform,opacity;';
                el.innerHTML = islamicStarHtml(sz);
                wrap.appendChild(el);
                var upDist = 200 + Math.random() * 220;
                gsap.fromTo(el,
                    { opacity: 0, y: 0, x: 0, rotation: 0, scale: 0.4 },
                    {
                        opacity: 0.85,
                        y: -upDist,
                        x: (Math.random() - 0.5) * 90,
                        rotation: (Math.random() - 0.5) * 140,
                        scale: 1,
                        duration: 2.8 + Math.random() * 1.6,
                        delay: Math.random() * 1.4,
                        ease: 'power1.out',
                        onComplete: function () {
                            gsap.to(el, { opacity: 0, duration: 0.6, ease: 'power1.in' });
                        }
                    }
                );
            })(i);
        }
    }

    /* ── JAWA: Warm golden jasmine flowers rise ───────────── */
    function _postJawa(wrap) {
        for (var i = 0; i < 14; i++) {
            (function (i) {
                var sz = 10 + Math.random() * 12;
                var el = document.createElement('div');
                el.style.cssText =
                    'position:absolute;' +
                    'left:' + (5 + Math.random() * 90) + '%;' +
                    'top:'  + (58 + Math.random() * 36) + '%;' +
                    /* CSS filter makes white petals golden */
                    'filter:sepia(1) saturate(3.2) hue-rotate(6deg) brightness(1.1);' +
                    'opacity:0;will-change:transform,opacity;';
                el.innerHTML = jasmineFlowerSvgStr(sz);
                wrap.appendChild(el);
                var upDist = 170 + Math.random() * 170;
                gsap.fromTo(el,
                    { opacity: 0, y: 0, x: 0, rotation: 0, scale: 0.35 },
                    {
                        opacity: 0.88,
                        y: -upDist,
                        x: (Math.random() - 0.5) * 65,
                        rotation: (Math.random() - 0.5) * 90,
                        scale: 1,
                        duration: 3.0 + Math.random() * 1.8,
                        delay: Math.random() * 1.2,
                        ease: 'power1.out',
                        onComplete: function () {
                            gsap.to(el, { opacity: 0, duration: 0.65, ease: 'power1.in' });
                        }
                    }
                );
            })(i);
        }
    }

    /* ── MINIMAL: Soft gray dots expand then fade ─────────── */
    function _postMinimal(wrap) {
        for (var i = 0; i < 12; i++) {
            (function (i) {
                var sz = 5 + Math.random() * 6;
                var el = document.createElement('div');
                var xp = 10 + Math.random() * 80;
                var yp = 10 + Math.random() * 80;
                el.style.cssText =
                    'position:absolute;' +
                    'left:' + xp + '%;top:' + yp + '%;' +
                    'width:' + (sz * 2) + 'px;height:' + (sz * 2) + 'px;' +
                    'border-radius:50%;background:#D1D5DB;' +
                    'opacity:0;will-change:transform,opacity;';
                wrap.appendChild(el);
                gsap.fromTo(el,
                    { opacity: 0, scale: 0 },
                    {
                        opacity: 0.55,
                        scale: 1,
                        duration: 0.8 + Math.random() * 0.7,
                        delay: Math.random() * 1.4,
                        ease: 'back.out(1.5)',
                        onComplete: function () {
                            gsap.to(el, {
                                opacity: 0, scale: 2.8,
                                duration: 1.1, delay: 0.5, ease: 'power1.in'
                            });
                        }
                    }
                );
            })(i);
        }
    }

    /* ── ROYAL: Gold diamonds fall from top corners ───────── */
    function _postRoyal(wrap) {
        for (var i = 0; i < 24; i++) {
            (function (i) {
                var sz = 5 + Math.random() * 9;
                /* cluster: 10 from left corner, 10 from right, 4 from top-center */
                var xp = i < 10
                    ? Math.random() * 24
                    : i < 20
                        ? 76 + Math.random() * 24
                        : 35 + Math.random() * 30;
                var el = document.createElement('div');
                el.style.cssText =
                    'position:absolute;left:' + xp + '%;top:-5%;' +
                    'opacity:0;will-change:transform,opacity;';
                el.innerHTML = royalDiamondHtml(sz);
                wrap.appendChild(el);
                gsap.fromTo(el,
                    { opacity: 0, y: 0, x: 0, rotation: 0, scale: 0.4 },
                    {
                        opacity: 0.88,
                        y: window.innerHeight * (0.35 + Math.random() * 0.45),
                        x: (Math.random() - 0.5) * 130,
                        rotation: Math.random() * 360,
                        scale: 1,
                        duration: 3.0 + Math.random() * 2.0,
                        delay: Math.random() * 1.6,
                        ease: 'power1.inOut',
                        onComplete: function () {
                            gsap.to(el, { opacity: 0, duration: 0.65, ease: 'power1.in' });
                        }
                    }
                );
            })(i);
        }
    }

    /* ── BUTTERFLY: Blue + gold sparkles twinkle and drift ── */
    function _postButterfly(wrap) {
        var COLS = ['#38bdf8','#38bdf8','#c8a96e','#7dd3fc','#38bdf8'];
        for (var i = 0; i < 20; i++) {
            (function (i) {
                var sz  = 4 + Math.random() * 7;
                var col = COLS[i % COLS.length];
                var el  = document.createElement('div');
                var xp  = 5 + Math.random() * 90;
                var yp  = 10 + Math.random() * 80;
                el.style.cssText =
                    'position:absolute;left:' + xp + '%;top:' + yp + '%;' +
                    'opacity:0;will-change:transform,opacity;';
                el.innerHTML = sparkDotHtml(sz, col);
                wrap.appendChild(el);
                gsap.fromTo(el,
                    { opacity: 0, scale: 0, rotation: 0 },
                    {
                        opacity: 0.85,
                        scale: 1,
                        rotation: (Math.random() - 0.5) * 90,
                        duration: 0.5 + Math.random() * 0.45,
                        delay: Math.random() * 2.2,
                        ease: 'back.out(2)',
                        onComplete: function () {
                            gsap.to(el, {
                                opacity: 0,
                                scale: 0.3,
                                y: (Math.random() - 0.5) * 70,
                                x: (Math.random() - 0.5) * 70,
                                duration: 1.1 + Math.random() * 0.8,
                                delay: 0.4 + Math.random() * 0.6,
                                ease: 'power1.in'
                            });
                        }
                    }
                );
            })(i);
        }
    }

    /* ══════════════════════════════════════════════════════════
       GSAP CINEMATIC TIMELINE
       ─────────────────────────────────────────────────────────
       Flow yang benar:
         t=0     → cover LANGSUNG hidden, main LANGSUNG shown
                   (panels sudah menutupi semua — user tidak lihat flash)
         t=0–1.1 → panels slide kiri & kanan membuka konten
         t=0.7   → seam fade out
         t=1.1+  → hero elements stagger fade-up
         done    → bersihkan panels, setup scroll reveal
    ══════════════════════════════════════════════════════════ */
    function runTimeline(cover, main, bgImg, doors) {
        var alpineRoot = document.querySelector('body[x-data]') ||
                         document.querySelector('[x-data]');

        /* stop idle bg zoom */
        if (bgImg) gsap.killTweensOf(bgImg);

        /* ── LANGKAH 0 — Sembunyikan cover & tampilkan main
           SEKETIKA sebelum animasi dimulai.
           Panels (z-101) sudah menutupi segalanya saat ini,
           sehingga user tidak melihat perubahan apapun. ─── */
        cover.classList.add('coi-bypass');
        main.classList.add('coi-bypass');

        /* sembunyikan cover sepenuhnya — tak perlu dissolve */
        gsap.set(cover, { display: 'none' });

        /* Pre-apply AOS animate state on hero elements BEFORE main is shown —
           prevents single-frame flash where AOS elements sit at opacity:0
           while waiting for IntersectionObserver to fire. */
        var heroSecPre = main.querySelector('#nav-top, section:first-of-type');
        if (heroSecPre) {
            heroSecPre.querySelectorAll('[data-aos]').forEach(function(el) {
                el.classList.add('aos-animate');
            });
        }

        /* tampilkan main di balik panels */
        try { window.Alpine.store('invitation').openEnvelope(); } catch (e) {}
        if (alpineRoot && window.Alpine) {
            try { window.Alpine.$data(alpineRoot).opened = true; } catch (e) {}
        } else {
            main.style.display = 'block';
        }
        /* pastikan main tidak punya transform sisa dari gsap.set setup */
        gsap.set(main, { clearProps: 'y,scale,opacity,willChange,transformOrigin' });

        /* ── GSAP Timeline ───────────────────────────────────── */
        var tl = gsap.timeline({
            defaults: { ease: 'power2.inOut' },
            onComplete: function () {
                main.classList.remove('coi-bypass');
                if (doors) {
                    ['panelL','panelR','seam'].forEach(function(k){
                        if (doors[k] && doors[k].parentNode)
                            doors[k].parentNode.removeChild(doors[k]);
                    });
                }
                initLenis();
            }
        });

        /* ── STEP 1  Panels slide out ────────────────────────── */
        if (doors && doors.panelL && doors.panelR) {
            tl.to(doors.panelL, { x: '-100%', duration: 1.1, ease: 'power2.inOut' }, 0);
            tl.to(doors.panelR, { x: '100%',  duration: 1.1, ease: 'power2.inOut' }, 0);
            if (doors.seam) {
                doors.seam.style.animation = 'none';
                tl.to(doors.seam, { opacity: 0, duration: 0.3, ease: 'power1.in' }, 0.75);
            }
            /* jasmine flowers bloom from the seam as doors open */
            tl.add(function () { spawnRevealFlowers(); }, 0.08);
            /* themed post-reveal effect fires as panels finish sliding */
            tl.add(function () { spawnPostRevealEffect(doors.theme); }, 1.0);
        }

        /* ── STEP 2  Hero elements stagger setelah panels buka ─
           Hanya target elemen TANPA data-aos — supaya tidak
           bentrok dengan AOS yang sudah handle hero-nya sendiri
           (misal jawa-klasik). Template tanpa AOS di hero tetap
           mendapat GSAP stagger. */
        var heroSec = main.querySelector('#nav-top, section:first-of-type');
        if (heroSec) {
            var heroEls = Array.from(heroSec.querySelectorAll(
                'h1, h2, p, .fc, ' +
                '.font-serif-luxury, .font-cormorant, ' +
                '.font-cinzel, .font-pinyon, ' +
                '[class*="fc"]'
            )).filter(function(el) {
                /* skip elements already managed by AOS */
                return !el.hasAttribute('data-aos');
            });
            if (heroEls.length) {
                tl.fromTo(heroEls,
                    { opacity: 0, y: 22 },
                    {
                        opacity: 1, y: 0,
                        stagger: { amount: 0.5, from: 'start' },
                        duration: 0.6,
                        ease: 'power2.out',
                        clearProps: 'all',
                    },
                1.15);
            }
        }
    }

    /* ══════════════════════════════════════════════════════════
       SPLIT REVEAL — Theme Detection
       Fingerprints the template by checking for unique element
       IDs / class names injected by each template's own CSS/JS.
    ══════════════════════════════════════════════════════════ */
    function detectTheme() {
        /* Jawa Exclusive: has its own je-door panels — skip */
        if (document.getElementById('je-cover'))           return 'skip';
        /* Andalusia Exclusive: has its own Moorish gate — skip */
        if (document.getElementById('al-cover'))           return 'skip';
        /* Floral Luxury */
        if (document.getElementById('fl-cover-petals'))    return 'floral';
        /* Dark Elegant */
        if (document.getElementById('de-cover-particles')) return 'dark-elegant';
        /* Emerald Islamic */
        if (document.getElementById('ei-global-canvas'))   return 'islamic';
        /* Jawa Klasik */
        if (document.querySelector('.batik-bg'))           return 'jawa';
        /* Minimalist Modern */
        if (document.getElementById('mm-cover-dots'))      return 'minimal';
        /* Batavia Royale */
        if (document.getElementById('br-cover-screen'))    return 'royal';
        /* Blue Butterfly */
        if (document.getElementById('butterfly-canvas'))   return 'butterfly';
        return 'default';
    }

    /* ══════════════════════════════════════════════════════════
       SPLIT REVEAL — Panel Builder
       Creates left panel, right panel, and center seam div,
       appends them to body, returns references.
       NOTE: Config is defined INSIDE the function (not at IIFE
       scope) to avoid timing issues with synchronous CDN scripts
       that call setup() before outer var declarations run.
    ══════════════════════════════════════════════════════════ */

    function buildSplitPanels() {
        var theme = detectTheme();
        if (theme === 'skip') return null;

        /* Theme visual config — defined here to ensure it's always
           initialized when this function is called, regardless of
           when the CDN scripts (gsap/lenis) trigger setup(). */
        var COI_SPLIT = {
            floral:   {
                bg:   'linear-gradient(160deg,#FBF5DD 0%,#E7E1B1 100%)',
                seam: 'linear-gradient(180deg,transparent 0%,rgba(48,109,41,0.35) 12%,rgba(48,109,41,0.8) 50%,rgba(48,109,41,0.35) 88%,transparent 100%)',
                glow: '0 0 20px 5px rgba(48,109,41,0.28)',
            },
            luxury:   {
                bg:   '#0F0F0F',
                seam: 'linear-gradient(180deg,transparent 0%,rgba(201,168,76,0.25) 10%,rgba(201,168,76,0.95) 50%,rgba(201,168,76,0.25) 90%,transparent 100%)',
                glow: '0 0 28px 7px rgba(201,168,76,0.45)',
            },
            'dark-elegant': {
                bg:   '#0A0A0A',
                seam: 'linear-gradient(180deg,transparent 0%,rgba(229,231,235,0.12) 10%,rgba(229,231,235,0.72) 50%,rgba(229,231,235,0.12) 90%,transparent 100%)',
                glow: '0 0 28px 7px rgba(229,231,235,0.22)',
            },
            islamic:  {
                bg:   'linear-gradient(160deg,#FBF5DD 0%,#E7E1B1 100%)',
                seam: 'linear-gradient(180deg,transparent 0%,rgba(13,83,14,0.3) 12%,rgba(13,83,14,0.78) 50%,rgba(13,83,14,0.3) 88%,transparent 100%)',
                glow: '0 0 18px 5px rgba(13,83,14,0.28)',
            },
            jawa:     {
                bg:   '#150303',
                seam: 'linear-gradient(180deg,transparent 0%,rgba(186,159,84,0.25) 10%,rgba(186,159,84,0.9) 50%,rgba(186,159,84,0.25) 90%,transparent 100%)',
                glow: '0 0 24px 6px rgba(186,159,84,0.38)',
            },
            minimal:  {
                bg:   '#FFFFFF',
                seam: 'linear-gradient(180deg,transparent 0%,rgba(156,163,175,0.18) 20%,rgba(156,163,175,0.32) 50%,rgba(156,163,175,0.18) 80%,transparent 100%)',
                glow: 'none',
            },
            royal:    {
                bg:   'linear-gradient(145deg,#0f172a 0%,#1e293b 100%)',
                seam: 'linear-gradient(180deg,transparent 0%,rgba(212,175,55,0.28) 10%,rgba(212,175,55,0.95) 50%,rgba(212,175,55,0.28) 90%,transparent 100%)',
                glow: '0 0 28px 7px rgba(212,175,55,0.42)',
            },
            butterfly:{
                bg:   'linear-gradient(145deg,#1e3a5f 0%,#172d4a 100%)',
                seam: 'linear-gradient(180deg,transparent 0%,rgba(200,169,110,0.28) 12%,rgba(200,169,110,0.8) 50%,rgba(200,169,110,0.28) 88%,transparent 100%)',
                glow: '0 0 20px 5px rgba(200,169,110,0.32)',
            },
            default:  {
                bg:   'rgba(248,248,248,0.98)',
                seam: 'linear-gradient(180deg,transparent,rgba(0,0,0,0.18),transparent)',
                glow: 'none',
            },
        };

        var cfg = COI_SPLIT[theme] || COI_SPLIT['default'];

        /* ── Left panel ── */
        var pl = document.createElement('div');
        pl.id  = 'coi-panel-l';
        pl.style.background = cfg.bg;
        pl.innerHTML = panelSvg(theme, 'l');

        /* ── Right panel ── */
        var pr = document.createElement('div');
        pr.id  = 'coi-panel-r';
        pr.style.background = cfg.bg;
        pr.innerHTML = panelSvg(theme, 'r');

        /* ── Center seam ── */
        var seam = document.createElement('div');
        seam.id = 'coi-seam';
        seam.style.cssText =
            'background:' + cfg.seam + ';' +
            'box-shadow:' + cfg.glow + ';';

        document.body.appendChild(pl);
        document.body.appendChild(pr);
        document.body.appendChild(seam);

        return { panelL: pl, panelR: pr, seam: seam, theme: theme };
    }

    /* ══════════════════════════════════════════════════════════
       SPLIT REVEAL — SVG Ornaments per Theme
       Each ornament is positioned on the INNER edge of its panel
       (the side that faces the center seam) so the two panels
       together form a symmetrical composition.
    ══════════════════════════════════════════════════════════ */
    function panelSvg(theme, side) {
        switch (theme) {
            case 'floral':    return floralSvg(side);
            case 'luxury':       return luxurySvg(side);
            case 'dark-elegant': return luxurySvg(side);
            case 'islamic':      return islamicSvg(side);
            case 'jawa':      return jawaSvg(side);
            case 'minimal':   return minimalSvg(side);
            case 'royal':     return royalSvg(side);
            case 'butterfly': return butterflySvg(side);
            default:          return '';
        }
    }

    /* ─────────────────────────────────────────────────────────
       FLORAL: Rose branches climbing the inner edge
    ───────────────────────────────────────────────────────── */
    function floralSvg(side) {
        var anchor = side === 'l' ? 'right:0' : 'left:0';
        var flip   = side === 'r' ? ';transform:scaleX(-1)' : '';
        return (
            '<svg viewBox="0 0 150 560" xmlns="http://www.w3.org/2000/svg"' +
            ' preserveAspectRatio="xMaxYMax meet"' +
            ' style="position:absolute;' + anchor + ';bottom:0;width:52%;height:82%;pointer-events:none' + flip + '">' +
            '<g opacity="0.44">' +
            /* main stem */
            '<path d="M135 560 Q122 450 106 340 Q94 245 98 130 Q101 65 96 8"' +
            '  stroke="#306D29" stroke-width="2.2" fill="none" stroke-linecap="round"/>' +
            /* branch low */
            '<path d="M118 480 Q84 462 58 438"' +
            '  stroke="#306D29" stroke-width="1.6" fill="none" stroke-linecap="round"/>' +
            /* branch mid */
            '<path d="M105 330 Q67 310 42 283"' +
            '  stroke="#306D29" stroke-width="1.4" fill="none" stroke-linecap="round"/>' +
            /* branch upper */
            '<path d="M99 188 Q63 167 44 140"' +
            '  stroke="#306D29" stroke-width="1.2" fill="none" stroke-linecap="round"/>' +
            /* branch top */
            '<path d="M97 78 Q70 60 56 36"' +
            '  stroke="#306D29" stroke-width="1" fill="none" stroke-linecap="round"/>' +
            /* ── Rose 1 — full bloom (low) ── */
            '<g transform="translate(58,438)">' +
            '<circle r="16" fill="#f9a8d4"/>' +
            '<ellipse rx="8" ry="13" transform="rotate(0)"   fill="#fbcfe8" opacity="0.85"/>' +
            '<ellipse rx="8" ry="13" transform="rotate(60)"  fill="#fbcfe8" opacity="0.8"/>' +
            '<ellipse rx="8" ry="13" transform="rotate(120)" fill="#fce7f3" opacity="0.8"/>' +
            '<ellipse rx="8" ry="13" transform="rotate(180)" fill="#fbcfe8" opacity="0.8"/>' +
            '<ellipse rx="8" ry="13" transform="rotate(240)" fill="#fce7f3" opacity="0.75"/>' +
            '<ellipse rx="8" ry="13" transform="rotate(300)" fill="#fbcfe8" opacity="0.75"/>' +
            '<circle r="7" fill="#f472b6" opacity="0.75"/>' +
            '<circle r="3" fill="#ec4899" opacity="0.65"/>' +
            '</g>' +
            /* ── Rose 2 — mid bloom ── */
            '<g transform="translate(42,283)">' +
            '<circle r="13" fill="#fbcfe8"/>' +
            '<ellipse rx="6" ry="10" transform="rotate(0)"   fill="#fce7f3" opacity="0.85"/>' +
            '<ellipse rx="6" ry="10" transform="rotate(72)"  fill="#fbcfe8" opacity="0.8"/>' +
            '<ellipse rx="6" ry="10" transform="rotate(144)" fill="#fce7f3" opacity="0.8"/>' +
            '<ellipse rx="6" ry="10" transform="rotate(216)" fill="#fbcfe8" opacity="0.8"/>' +
            '<ellipse rx="6" ry="10" transform="rotate(288)" fill="#fce7f3" opacity="0.75"/>' +
            '<circle r="5.5" fill="#f9a8d4" opacity="0.72"/>' +
            '<circle r="2.5" fill="#f472b6" opacity="0.6"/>' +
            '</g>' +
            /* ── Rose 3 — upper ── */
            '<g transform="translate(44,140)">' +
            '<circle r="11" fill="#fce7f3"/>' +
            '<ellipse rx="5" ry="8" transform="rotate(0)"   fill="#fbcfe8" opacity="0.82"/>' +
            '<ellipse rx="5" ry="8" transform="rotate(90)"  fill="#fce7f3" opacity="0.78"/>' +
            '<ellipse rx="5" ry="8" transform="rotate(180)" fill="#fbcfe8" opacity="0.8"/>' +
            '<ellipse rx="5" ry="8" transform="rotate(270)" fill="#fce7f3" opacity="0.76"/>' +
            '<circle r="4.5" fill="#f9a8d4" opacity="0.68"/>' +
            '</g>' +
            /* ── Rosebud (top) ── */
            '<g transform="translate(56,36)">' +
            '<ellipse rx="6" ry="9" fill="#fbcfe8" opacity="0.72"/>' +
            '<ellipse ry="6" rx="3.5" fill="#f9a8d4" opacity="0.65" transform="translate(0,-2)"/>' +
            '<circle cy="-6" r="2.5" fill="#f472b6" opacity="0.55"/>' +
            '</g>' +
            /* ── Leaves ── */
            '<ellipse cx="94" cy="462" rx="14" ry="5.5" fill="#306D29" opacity="0.52" transform="rotate(-30 94 462)"/>' +
            '<ellipse cx="72" cy="316" rx="12" ry="5"   fill="#306D29" opacity="0.48" transform="rotate(-25 72 316)"/>' +
            '<ellipse cx="70" cy="175" rx="10" ry="4.5" fill="#306D29" opacity="0.44" transform="rotate(-20 70 175)"/>' +
            '<ellipse cx="76" cy="60"  rx="9"  ry="4"   fill="#306D29" opacity="0.4"  transform="rotate(-18 76 60)"/>' +
            /* small buds */
            '<circle cx="82" cy="410" r="3.5" fill="#fbcfe8" opacity="0.5"/>' +
            '<circle cx="65" cy="255" r="3"   fill="#fce7f3" opacity="0.45"/>' +
            '</g></svg>'
        );
    }

    /* ─────────────────────────────────────────────────────────
       LUXURY: Gold frame brackets + center diamond (Dark Elegant)
    ───────────────────────────────────────────────────────── */
    function luxurySvg(side) {
        var anchor = side === 'l' ? 'right:0;top:0' : 'left:0;top:0';
        var flip   = side === 'r' ? ';transform:scaleX(-1)' : '';
        return (
            '<svg viewBox="0 0 200 620" xmlns="http://www.w3.org/2000/svg"' +
            ' style="position:absolute;' + anchor + ';width:62%;height:100%;pointer-events:none' + flip + '">' +
            '<g opacity="0.58">' +
            /* inner edge glow line */
            '<line x1="198" y1="0" x2="198" y2="620"' +
            '  stroke="#C9A84C" stroke-width="1" opacity="0.38"/>' +
            /* top corner L-bracket */
            '<polyline points="152,14 198,14 198,105"' +
            '  stroke="#C9A84C" stroke-width="1.3" fill="none"/>' +
            '<polyline points="170,14 198,14 198,58"' +
            '  stroke="#C9A84C" stroke-width="0.65" fill="none" opacity="0.55"/>' +
            '<circle cx="198" cy="14" r="3.2" fill="#C9A84C" opacity="0.85"/>' +
            '<circle cx="152" cy="14" r="1.8" fill="#C9A84C" opacity="0.6"/>' +
            /* bottom corner L-bracket */
            '<polyline points="152,606 198,606 198,515"' +
            '  stroke="#C9A84C" stroke-width="1.3" fill="none"/>' +
            '<polyline points="170,606 198,606 198,562"' +
            '  stroke="#C9A84C" stroke-width="0.65" fill="none" opacity="0.55"/>' +
            '<circle cx="198" cy="606" r="3.2" fill="#C9A84C" opacity="0.85"/>' +
            '<circle cx="152" cy="606" r="1.8" fill="#C9A84C" opacity="0.6"/>' +
            /* center diamond ornament */
            '<polygon points="198,291 186,310 198,329 210,310"' +
            '  fill="#C9A84C" opacity="0.72"/>' +
            '<polygon points="198,295 189,310 198,325 207,310"' +
            '  fill="none" stroke="#C9A84C" stroke-width="0.6" opacity="0.45"/>' +
            '<circle cx="198" cy="310" r="3.5" fill="#C9A84C" opacity="0.6"/>' +
            /* horizontal tick marks */
            '<line x1="187" y1="155" x2="198" y2="155" stroke="#C9A84C" stroke-width="0.9" opacity="0.48"/>' +
            '<line x1="192" y1="210" x2="198" y2="210" stroke="#C9A84C" stroke-width="0.65" opacity="0.38"/>' +
            '<line x1="187" y1="260" x2="198" y2="260" stroke="#C9A84C" stroke-width="0.9" opacity="0.48"/>' +
            '<line x1="187" y1="360" x2="198" y2="360" stroke="#C9A84C" stroke-width="0.9" opacity="0.48"/>' +
            '<line x1="192" y1="410" x2="198" y2="410" stroke="#C9A84C" stroke-width="0.65" opacity="0.38"/>' +
            '<line x1="187" y1="465" x2="198" y2="465" stroke="#C9A84C" stroke-width="0.9" opacity="0.48"/>' +
            /* inner corner dots */
            '<circle cx="198" cy="105" r="1.8" fill="#C9A84C" opacity="0.55"/>' +
            '<circle cx="198" cy="515" r="1.8" fill="#C9A84C" opacity="0.55"/>' +
            '</g></svg>'
        );
    }

    /* ─────────────────────────────────────────────────────────
       ISLAMIC: Half-geometric mandala on seam edge (Emerald Islamic)
    ───────────────────────────────────────────────────────── */
    function islamicSvg(side) {
        var anchor = side === 'l' ? 'right:-4px' : 'left:-4px';
        var flip   = side === 'r' ? ';transform:scaleX(-1)' : '';
        var cid    = 'coi-iclip-' + side;
        return (
            '<svg viewBox="0 0 200 440" xmlns="http://www.w3.org/2000/svg"' +
            ' style="position:absolute;' + anchor + ';top:50%;transform:translateY(-50%)' +
            (side === 'r' ? ' scaleX(-1)' : '') + ';width:68%;height:80%;pointer-events:none;">' +
            '<defs>' +
            '<clipPath id="' + cid + '">' +
            '<rect x="90" y="0" width="130" height="440"/>' +
            '</clipPath>' +
            '</defs>' +
            /* All ornaments clipped to inner half (seam side) */
            '<g clip-path="url(#' + cid + ')" opacity="0.38">' +
            /* 8-pointed Islamic star, anchor at (200,220) = seam edge */
            '<g transform="translate(200,220)">' +
            /* outer 8 petals */
            '<ellipse rx="20" ry="68" transform="rotate(0)"    fill="#306D29"/>' +
            '<ellipse rx="20" ry="68" transform="rotate(22.5)" fill="#0D530E" opacity="0.82"/>' +
            '<ellipse rx="20" ry="68" transform="rotate(45)"   fill="#306D29"/>' +
            '<ellipse rx="20" ry="68" transform="rotate(67.5)" fill="#0D530E" opacity="0.82"/>' +
            '<ellipse rx="20" ry="68" transform="rotate(90)"   fill="#306D29"/>' +
            '<ellipse rx="20" ry="68" transform="rotate(112.5)" fill="#0D530E" opacity="0.82"/>' +
            '<ellipse rx="20" ry="68" transform="rotate(135)"  fill="#306D29"/>' +
            '<ellipse rx="20" ry="68" transform="rotate(157.5)" fill="#0D530E" opacity="0.82"/>' +
            /* inner accent ring */
            '<ellipse rx="11" ry="38" transform="rotate(22.5)" fill="#E7E1B1" opacity="0.55"/>' +
            '<ellipse rx="11" ry="38" transform="rotate(67.5)" fill="#E7E1B1" opacity="0.55"/>' +
            '<ellipse rx="11" ry="38" transform="rotate(112.5)" fill="#E7E1B1" opacity="0.55"/>' +
            '<ellipse rx="11" ry="38" transform="rotate(157.5)" fill="#E7E1B1" opacity="0.55"/>' +
            /* center */
            '<circle r="20" fill="#306D29" opacity="0.88"/>' +
            '<circle r="11" fill="#FBF5DD" opacity="0.65"/>' +
            '<circle r="5"  fill="#306D29" opacity="0.78"/>' +
            /* outer ring */
            '<circle r="88"  fill="none" stroke="#306D29" stroke-width="1" opacity="0.28"/>' +
            '<circle r="100" fill="none" stroke="#306D29" stroke-width="0.5" opacity="0.16"/>' +
            '</g>' +
            /* corner calligraphy accent lines */
            '<line x1="130" y1="28"  x2="200" y2="28"  stroke="#306D29" stroke-width="0.9" opacity="0.32"/>' +
            '<line x1="130" y1="412" x2="200" y2="412" stroke="#306D29" stroke-width="0.9" opacity="0.32"/>' +
            '<line x1="156" y1="14"  x2="200" y2="14"  stroke="#306D29" stroke-width="0.5" opacity="0.22"/>' +
            '<line x1="156" y1="426" x2="200" y2="426" stroke="#306D29" stroke-width="0.5" opacity="0.22"/>' +
            '</g></svg>'
        );
    }

    /* ─────────────────────────────────────────────────────────
       JAWA: Batik kawung pattern + half-gapura arch (Jawa Klasik)
    ───────────────────────────────────────────────────────── */
    function jawaSvg(side) {
        var anchor = side === 'l' ? 'right:0;top:0' : 'left:0;top:0';
        var flip   = side === 'r' ? ';transform:scaleX(-1)' : '';
        return (
            '<svg viewBox="0 0 200 620" xmlns="http://www.w3.org/2000/svg"' +
            ' style="position:absolute;' + anchor + ';width:58%;height:100%;pointer-events:none' + flip + '">' +
            /* kawung batik repeat pattern */
            '<g opacity="0.22" stroke="#BA9F54" stroke-width="0.75" fill="none">' +
            '<ellipse cx="100" cy="40"  rx="16" ry="20"/>' +
            '<ellipse cx="100" cy="100" rx="16" ry="20"/>' +
            '<ellipse cx="100" cy="160" rx="16" ry="20"/>' +
            '<ellipse cx="100" cy="220" rx="16" ry="20"/>' +
            '<ellipse cx="100" cy="280" rx="16" ry="20"/>' +
            '<ellipse cx="100" cy="340" rx="16" ry="20"/>' +
            '<ellipse cx="100" cy="400" rx="16" ry="20"/>' +
            '<ellipse cx="100" cy="460" rx="16" ry="20"/>' +
            '<ellipse cx="100" cy="520" rx="16" ry="20"/>' +
            '<ellipse cx="100" cy="580" rx="16" ry="20"/>' +
            '<circle  cx="100" cy="70"  r="5" stroke-width="0.5"/>' +
            '<circle  cx="100" cy="130" r="5" stroke-width="0.5"/>' +
            '<circle  cx="100" cy="190" r="5" stroke-width="0.5"/>' +
            '<circle  cx="100" cy="250" r="5" stroke-width="0.5"/>' +
            '<circle  cx="100" cy="310" r="5" stroke-width="0.5"/>' +
            '<circle  cx="100" cy="370" r="5" stroke-width="0.5"/>' +
            '<circle  cx="100" cy="430" r="5" stroke-width="0.5"/>' +
            '<circle  cx="100" cy="490" r="5" stroke-width="0.5"/>' +
            '<circle  cx="100" cy="550" r="5" stroke-width="0.5"/>' +
            '</g>' +
            /* ── Half gapura arch on inner (right) edge ── */
            '<g opacity="0.65">' +
            /* pillar body */
            '<rect x="155" y="390" width="24" height="230" rx="4" fill="#BA9F54" opacity="0.68"/>' +
            '<rect x="180" y="390" width="20" height="230" rx="0" fill="#600101" opacity="0.35"/>' +
            /* pillar decorative rings */
            '<rect x="155" y="408" width="24" height="8"  rx="2" fill="#150303" opacity="0.55"/>' +
            '<rect x="155" y="450" width="24" height="6"  rx="2" fill="#150303" opacity="0.45"/>' +
            '<rect x="155" y="510" width="24" height="6"  rx="2" fill="#150303" opacity="0.45"/>' +
            '<rect x="155" y="570" width="24" height="6"  rx="2" fill="#150303" opacity="0.45"/>' +
            /* arch curve from pillar top to seam */
            '<path d="M155 390 Q168 295 200 260 L200 310 Q180 338 172 390 Z"' +
            '  fill="#BA9F54" opacity="0.62"/>' +
            /* arch top finial / meru */
            '<polygon points="162,262 172,228 182,262" fill="#BA9F54" opacity="0.72"/>' +
            '<circle cx="172" cy="222" r="9"  fill="#BA9F54" opacity="0.65"/>' +
            '<circle cx="172" cy="222" r="4.5" fill="#150303" opacity="0.75"/>' +
            '<circle cx="172" cy="222" r="1.5" fill="#BA9F54" opacity="0.7"/>' +
            /* gold inner edge line */
            '<line x1="199" y1="0"   x2="199" y2="620" stroke="#BA9F54" stroke-width="1.6" opacity="0.52"/>' +
            '<line x1="195" y1="0"   x2="195" y2="620" stroke="#BA9F54" stroke-width="0.5" opacity="0.22"/>' +
            /* top & bottom horizontal lines */
            '<line x1="110" y1="16" x2="200" y2="16" stroke="#BA9F54" stroke-width="0.9" opacity="0.42"/>' +
            '<line x1="110" y1="604" x2="200" y2="604" stroke="#BA9F54" stroke-width="0.9" opacity="0.42"/>' +
            '</g></svg>'
        );
    }

    /* ─────────────────────────────────────────────────────────
       MINIMAL: Clean diagonal line (Minimalist Modern)
    ───────────────────────────────────────────────────────── */
    function minimalSvg(side) {
        var anchor = side === 'l' ? 'right:0;top:0' : 'left:0;top:0';
        var flip   = side === 'r' ? ';transform:scaleX(-1)' : '';
        return (
            '<svg viewBox="0 0 200 620" xmlns="http://www.w3.org/2000/svg"' +
            ' style="position:absolute;' + anchor + ';width:60%;height:100%;pointer-events:none' + flip + '">' +
            '<g opacity="0.38">' +
            /* thin vertical inner edge line */
            '<line x1="198" y1="0" x2="198" y2="620" stroke="#9CA3AF" stroke-width="1" opacity="0.45"/>' +
            /* diagonal from top-right to mid center */
            '<line x1="100" y1="0"   x2="200" y2="310" stroke="#D1D5DB" stroke-width="0.9" opacity="0.42"/>' +
            /* diagonal from bottom-right to mid center */
            '<line x1="100" y1="620" x2="200" y2="310" stroke="#D1D5DB" stroke-width="0.9" opacity="0.42"/>' +
            /* small rectangle at center (negative space) */
            '<rect x="188" y="298" width="12" height="24" rx="1"' +
            '  fill="none" stroke="#6B7280" stroke-width="0.9" opacity="0.38"/>' +
            /* corner accent ticks */
            '<line x1="172" y1="20" x2="200" y2="20" stroke="#9CA3AF" stroke-width="0.9" opacity="0.38"/>' +
            '<line x1="172" y1="600" x2="200" y2="600" stroke="#9CA3AF" stroke-width="0.9" opacity="0.38"/>' +
            '<circle cx="200" cy="20"  r="2" fill="#9CA3AF" opacity="0.4"/>' +
            '<circle cx="200" cy="600" r="2" fill="#9CA3AF" opacity="0.4"/>' +
            '</g></svg>'
        );
    }

    /* ─────────────────────────────────────────────────────────
       ROYAL: Gold royal gate + crown (Batavia Royale)
    ───────────────────────────────────────────────────────── */
    function royalSvg(side) {
        var anchor = side === 'l' ? 'right:0;top:0' : 'left:0;top:0';
        var flip   = side === 'r' ? ';transform:scaleX(-1)' : '';
        return (
            '<svg viewBox="0 0 200 620" xmlns="http://www.w3.org/2000/svg"' +
            ' style="position:absolute;' + anchor + ';width:65%;height:100%;pointer-events:none' + flip + '">' +
            '<g opacity="0.62">' +
            /* inner edge gold line */
            '<line x1="199" y1="0" x2="199" y2="620" stroke="#D4AF37" stroke-width="1.6" opacity="0.52"/>' +
            '<line x1="195" y1="0" x2="195" y2="620" stroke="#D4AF37" stroke-width="0.5" opacity="0.22"/>' +
            /* royal arch pillar */
            '<rect x="148" y="290" width="22" height="225" rx="4" fill="#D4AF37" opacity="0.48"/>' +
            '<rect x="172" y="290" width="28" height="225" rx="0" fill="#D4AF37" opacity="0.18"/>' +
            /* pillar decoration */
            '<rect x="148" y="308" width="22" height="10" rx="2" fill="#0f172a" opacity="0.5"/>' +
            '<rect x="148" y="355" width="22" height="8"  rx="2" fill="#0f172a" opacity="0.4"/>' +
            '<rect x="148" y="415" width="22" height="8"  rx="2" fill="#0f172a" opacity="0.4"/>' +
            '<rect x="148" y="475" width="22" height="8"  rx="2" fill="#0f172a" opacity="0.4"/>' +
            /* arch curve from pillar to seam */
            '<path d="M148 290 Q172 188 200 150 L200 198 Q180 228 168 290 Z"' +
            '  fill="#D4AF37" opacity="0.48"/>' +
            /* crown ornament at arch apex */
            /* crown base */
            '<rect x="142" y="118" width="38" height="14" rx="3" fill="#D4AF37" opacity="0.6"/>' +
            /* crown points */
            '<polygon points="144,118 151,88 158,118" fill="#D4AF37" opacity="0.68"/>' +
            '<polygon points="158,118 165,76 172,118" fill="#D4AF37" opacity="0.72"/>' +
            '<polygon points="172,118 179,90 186,118" fill="#D4AF37" opacity="0.68"/>' +
            /* crown jewels */
            '<circle cx="151" cy="90" r="4.5" fill="#D4AF37" opacity="0.72"/>' +
            '<circle cx="165" cy="77" r="5.5" fill="#D4AF37" opacity="0.78"/>' +
            '<circle cx="179" cy="91" r="4.5" fill="#D4AF37" opacity="0.72"/>' +
            '<circle cx="165" cy="77" r="2.5" fill="#1e293b" opacity="0.6"/>' +
            /* top corner L-bracket */
            '<polyline points="152,18 200,18 200,80"' +
            '  stroke="#D4AF37" stroke-width="1.3" fill="none" opacity="0.55"/>' +
            '<circle cx="200" cy="18" r="3" fill="#D4AF37" opacity="0.72"/>' +
            '<circle cx="152" cy="18" r="1.8" fill="#D4AF37" opacity="0.55"/>' +
            /* bottom corner L-bracket */
            '<polyline points="152,602 200,602 200,540"' +
            '  stroke="#D4AF37" stroke-width="1.3" fill="none" opacity="0.55"/>' +
            '<circle cx="200" cy="602" r="3" fill="#D4AF37" opacity="0.72"/>' +
            /* horizontal ticks */
            '<line x1="186" y1="190" x2="200" y2="190" stroke="#D4AF37" stroke-width="0.9" opacity="0.42"/>' +
            '<line x1="186" y1="490" x2="200" y2="490" stroke="#D4AF37" stroke-width="0.9" opacity="0.42"/>' +
            '</g></svg>'
        );
    }

    /* ─────────────────────────────────────────────────────────
       BUTTERFLY: Wing silhouette with veins (Blue Butterfly)
       Left panel = left wing / Right panel = right wing
       Together they form one complete butterfly at rest.
    ───────────────────────────────────────────────────────── */
    function butterflySvg(side) {
        /* Left wing: body root at x≈198 (right/seam edge), position right:0.
           Right wing: scaleX(-1) flips body root to near seam of right panel.
           IMPORTANT: both transforms must be in ONE property — two transform
           declarations cause the second to override the first in CSS. */
        var isLeft    = (side === 'l');
        var transform = isLeft ? 'translateY(-50%)' : 'translateY(-50%) scaleX(-1)';
        return (
            '<svg viewBox="0 0 200 560" xmlns="http://www.w3.org/2000/svg"' +
            ' preserveAspectRatio="xMaxYMid meet"' +
            ' style="position:absolute;right:0;top:50%;transform:' + transform +
            ';width:100%;height:82%;pointer-events:none;">' +
            /* ── Upper wing (body root at right = seam side) ── */
            '<path d="M198 248' +
            '  Q168 130 105 85' +
            '  Q48  48  18  100' +
            '  Q-4  148  24  210' +
            '  Q56  272  120 268' +
            '  Q168 264  198 278 Z"' +
            '  fill="#1e3a5f" opacity="0.88"/>' +
            /* upper wing inner highlight */
            '<path d="M198 248' +
            '  Q168 130 105 85' +
            '  Q48  48  18  100' +
            '  Q-4  148  24  210' +
            '  Q56  272  120 268' +
            '  Q168 264  198 278 Z"' +
            '  fill="none" stroke="#38bdf8" stroke-width="1.2" opacity="0.32"/>' +
            /* upper wing eye-spot */
            '<circle cx="78" cy="185" r="22" fill="#172d4a" stroke="#38bdf8" stroke-width="1.5" opacity="0.55"/>' +
            '<circle cx="78" cy="185" r="12" fill="#38bdf8" opacity="0.22"/>' +
            '<circle cx="78" cy="185" r="5"  fill="#c8a96e" opacity="0.45"/>' +
            /* wing vein lines */
            '<line x1="198" y1="262" x2="60"  y2="100" stroke="#38bdf8" stroke-width="0.7" opacity="0.2"/>' +
            '<line x1="198" y1="262" x2="20"  y2="185" stroke="#38bdf8" stroke-width="0.7" opacity="0.18"/>' +
            '<line x1="198" y1="262" x2="50"  y2="265" stroke="#38bdf8" stroke-width="0.7" opacity="0.18"/>' +
            /* gold iridescent inner edge */
            '<path d="M198 248 Q168 264 120 268 Q80 270 55 258"' +
            '  fill="none" stroke="#c8a96e" stroke-width="2.5" opacity="0.42"/>' +
            /* ── Lower wing ── */
            '<path d="M198 278' +
            '  Q172 282  145 290' +
            '  Q88  298  46  346' +
            '  Q14  384  28  426' +
            '  Q50  462  100 452' +
            '  Q155 440  198 398 Z"' +
            '  fill="#172d4a" opacity="0.82"/>' +
            '<path d="M198 278' +
            '  Q172 282  145 290' +
            '  Q88  298  46  346' +
            '  Q14  384  28  426' +
            '  Q50  462  100 452' +
            '  Q155 440  198 398 Z"' +
            '  fill="none" stroke="#38bdf8" stroke-width="1" opacity="0.28"/>' +
            /* lower wing spot */
            '<circle cx="90" cy="390" r="16" fill="#1e3a5f" stroke="#38bdf8" stroke-width="1" opacity="0.45"/>' +
            '<circle cx="90" cy="390" r="7"  fill="#38bdf8" opacity="0.18"/>' +
            /* lower wing vein */
            '<line x1="198" y1="338" x2="50" y2="380" stroke="#38bdf8" stroke-width="0.7" opacity="0.18"/>' +
            /* gold edge of lower wing toward seam */
            '<path d="M198 278 Q165 278 145 290"' +
            '  fill="none" stroke="#c8a96e" stroke-width="2" opacity="0.38"/>' +
            '</svg>'
        );
    }

    /* ══════════════════════════════════════════════════════════
       FALLBACK: setup reveal once Alpine `opened` becomes true
       (for templates that have their own opening animation,
        e.g. jawa-exclusive with its own door system)
    ══════════════════════════════════════════════════════════ */
    function initLenisWhenOpened() {
        var root = document.querySelector('[x-data]');
        if (!root) return;
        var poll = setInterval(function () {
            try {
                if (window.Alpine && window.Alpine.$data(root).opened) {
                    clearInterval(poll);
                    setTimeout(setupSectionReveal, 300);
                }
            } catch (e) { clearInterval(poll); }
        }, 150);
    }

    /* ══════════════════════════════════════════════════════════
       POST-OPEN SETUP — native scroll, no lag
    ══════════════════════════════════════════════════════════ */
    function initLenis() {
        /* Clear any lingering GSAP inline styles on main container.
           Critical: will-change / transform-origin on a parent break
           position:fixed children (navbar, floating buttons, etc.)
           by creating an unwanted CSS stacking context. */
        var main = document.querySelector('[data-coi-main]');
        if (main) {
            main.style.willChange  = 'auto';
            main.style.transformOrigin = '';
            main.style.transform   = '';
            /* ensure opacity is fully reset via gsap clearProps */
            gsap.set(main, { clearProps: 'willChange,transformOrigin,transform' });
        }

        /* Section reveal transitions via native ScrollTrigger */
        setupSectionReveal();

        if (typeof AOS !== 'undefined') {
            setTimeout(function () { AOS.refresh(); }, 120);
        }
    }

    /* ══════════════════════════════════════════════════════════
       SECTION REVEAL — Scroll-triggered entrance animations
       Targets sections inside [data-coi-main] automatically.
       Uses ScrollTrigger so it coordinates with Lenis.
    ══════════════════════════════════════════════════════════ */
    function setupSectionReveal() {
        var main = document.querySelector('[data-coi-main]');
        if (!main) return;

        /* Skip hero (first section / nav-top) — already handled by GSAP timeline */
        var sections = Array.from(
            main.querySelectorAll('section, .section')
        ).filter(function (el) {
            return el.id !== 'nav-top' && !el.closest('#nav-top');
        });

        sections.forEach(function (sec, i) {
            /* Cards / named children animate individually */
            var children = sec.querySelectorAll(
                '.card, [class*="card"], ' +
                'h2, h3, ' +
                '[class*="title"], [class*="heading"], ' +
                '.couple-card, .event-card, .gallery-item, ' +
                '[data-aos]'
            );
            var targets = children.length >= 2 ? Array.from(children) : [sec];

            gsap.fromTo(targets,
                { opacity: 0, y: 48, scale: 0.97 },
                {
                    opacity: 1, y: 0, scale: 1,
                    duration: 0.75,
                    ease: 'power2.out',
                    stagger: { amount: 0.35, from: 'start' },
                    clearProps: 'all',
                    scrollTrigger: {
                        trigger: sec,
                        start: 'top 82%',
                        end: 'top 30%',
                        toggleActions: 'play none none none',
                        once: true,
                    },
                }
            );
        });

        /* Decorative dividers / separators fade in */
        main.querySelectorAll('hr, .divider, [class*="divider"], [class*="separator"]')
            .forEach(function (el) {
                gsap.fromTo(el,
                    { opacity: 0, scaleX: 0.4 },
                    {
                        opacity: 1, scaleX: 1, duration: 0.6, ease: 'power2.out',
                        clearProps: 'all',
                        scrollTrigger: {
                            trigger: el, start: 'top 88%', once: true,
                        },
                    }
                );
            });
    }

})();
</script>
