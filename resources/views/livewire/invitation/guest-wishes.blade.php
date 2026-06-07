<div>

{{-- ── Daftar Ucapan ────────────────────────────────────── --}}
<div class="space-y-3">
    @forelse($wishes as $wish)
    <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);box-shadow:0 2px 12px rgba(0,0,0,0.06)" data-aos="fade-up">

        {{-- Header: avatar + nama + status --}}
        <div style="display:flex;align-items:center;gap:12px;padding:14px 16px 10px">

            {{-- Avatar initial --}}
            <div style="width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.85rem;font-weight:700;color:inherit;opacity:0.55;border:1.5px solid currentColor">
                {{ mb_strtoupper(mb_substr($wish->name, 0, 1)) }}
            </div>

            <div style="flex:1;min-width:0">
                <p style="font-size:0.9rem;font-weight:600;margin:0;line-height:1.3">{{ $wish->name }}</p>
                <p style="font-size:0.7rem;opacity:0.45;margin:2px 0 0">{{ \Carbon\Carbon::parse($wish->created_at)->diffForHumans() }}</p>
            </div>

            {{-- Status badge --}}
            @php
            $badgeStyle = match($wish->attendance) {
                'hadir'       => 'background:rgba(34,197,94,0.12);color:rgb(22,163,74);border:1px solid rgba(34,197,94,0.25)',
                'tidak_hadir' => 'background:rgba(239,68,68,0.10);color:rgb(220,38,38);border:1px solid rgba(239,68,68,0.2)',
                default       => 'background:rgba(156,163,175,0.12);color:rgb(107,114,128);border:1px solid rgba(156,163,175,0.2)',
            };
            $badgeText = match($wish->attendance) {
                'hadir'       => 'Hadir',
                'tidak_hadir' => 'Tidak Hadir',
                default       => 'Mungkin',
            };
            @endphp
            <span style="font-size:0.65rem;font-weight:600;padding:3px 9px;border-radius:999px;white-space:nowrap;{{ $badgeStyle }}">
                {{ $badgeText }}
            </span>
        </div>

        {{-- Pesan --}}
        @if($wish->message)
        <p style="font-size:0.85rem;line-height:1.7;opacity:0.75;padding:0 16px 12px 64px;margin:0">
            "{{ $wish->message }}"
        </p>
        @endif

        {{-- Hadiah fisik --}}
        @if($wish->gift_name)
        <div style="display:flex;align-items:center;gap:6px;padding:8px 16px;border-top:1px solid rgba(175,147,52,0.12);background:rgba(175,147,52,0.07)">
            <span style="font-size:0.8rem">🎁</span>
            <p style="font-size:0.72rem;margin:0;opacity:0.75">
                Mengirimkan hadiah: <strong style="opacity:1;color:rgba(175,147,52,0.95)">{{ $wish->gift_name }}</strong>
            </p>
        </div>
        @endif

    </div>
    @empty
    <div class="text-center py-10" style="opacity:0.45">
        <p style="font-size:1.8rem;margin-bottom:8px">✉️</p>
        <p style="font-size:0.85rem">Belum ada ucapan. Jadilah yang pertama!</p>
    </div>
    @endforelse
</div>

{{-- ── Load More ────────────────────────────────────── --}}
@if($hasMore)
<button wire:click="loadMore" wire:loading.attr="disabled"
        class="w-full mt-6 py-3 text-sm rounded-xl transition-all disabled:opacity-50"
        style="border:1.5px solid rgba(255,255,255,0.2);background:rgba(255,255,255,0.05);color:inherit;opacity:0.7">
    <span wire:loading.remove>Lihat lebih banyak ({{ $total - count($wishes) }} lagi)</span>
    <span wire:loading>Memuat...</span>
</button>
@endif

{{-- Listen for new RSVP --}}
<script>
window.addEventListener('rsvp-submitted', () => @this.refreshWishes());
document.addEventListener('rsvp-submitted', () => @this.refreshWishes());
</script>
</div>
