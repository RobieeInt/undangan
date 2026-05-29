<div>

{{-- ══════════════════════════════════════════════
     STEP 1 — CARI NAMA
     Muncul kalau buka tanpa link ?tamu=
══════════════════════════════════════════════ --}}
@if($step === 'search')
<div class="space-y-4">

    {{-- Intro --}}
    <div class="text-center pb-1">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto mb-3"
             style="background:var(--rsvp-accent-bg,rgba(48,109,41,0.1))">
            <svg class="w-6 h-6" style="color:var(--rsvp-accent,#306D29)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <p class="font-serif text-base text-gray-800 leading-snug">Konfirmasi Kehadiran</p>
        <p class="text-xs text-gray-400 mt-1 leading-relaxed">
            Masukkan nama Anda sesuai undangan<br>untuk melanjutkan
        </p>
    </div>

    {{-- Search input --}}
    <div class="relative">
        <label class="rsvp-label">Cari Nama Anda</label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text"
                   wire:model.live.debounce.350ms="searchName"
                   placeholder="Ketik nama sesuai undangan…"
                   autocomplete="off"
                   class="rsvp-input pl-9">
            @if(strlen(trim($searchName)) > 0)
            <button type="button" wire:click="$set('searchName','')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300 hover:text-gray-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            @endif
        </div>
    </div>

    {{-- Results list --}}
    @if(!empty($searchResults))
    <div class="rounded-xl overflow-hidden border divide-y"
         style="border-color:var(--rsvp-border,rgba(217,210,142,0.6));divide-color:var(--rsvp-border,rgba(217,210,142,0.4))">
        @foreach($searchResults as $r)
        <button type="button"
                wire:click="selectGuest({{ $r['id'] }})"
                wire:key="guest-{{ $r['id'] }}"
                class="w-full flex items-center gap-3 px-4 py-3 text-left transition-colors hover:bg-opacity-60 group"
                style="background:var(--rsvp-input-bg,rgba(251,245,221,0.3))">
            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-sm font-bold text-white"
                 style="background:var(--rsvp-accent,#306D29)">
                {{ mb_strtoupper(mb_substr($r['name'], 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ $r['name'] }}</p>
                @if(($r['allocated_seats'] ?? 1) > 1)
                <p class="text-[10px] text-gray-400">{{ $r['allocated_seats'] }} kursi</p>
                @endif
            </div>
            <svg class="w-4 h-4 text-gray-300 group-hover:text-forest transition-colors flex-shrink-0"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
        @endforeach
    </div>
    @endif

    {{-- Not found state --}}
    @if($notFound)
    <div class="rounded-xl px-4 py-4 text-center border"
         style="background:rgba(239,68,68,0.04);border-color:rgba(239,68,68,0.15)">
        <svg class="w-8 h-8 text-red-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm font-medium text-red-500">Nama tidak ditemukan</p>
        <p class="text-xs text-gray-400 mt-1 leading-relaxed">
            Pastikan penulisan sesuai undangan yang Anda terima.<br>
            Jika ada kendala, hubungi penyelenggara acara.
        </p>
    </div>
    @endif

    {{-- Hint ketika belum ketik --}}
    @if(strlen(trim($searchName)) === 0 && empty($searchResults) && !$notFound)
    <p class="text-center text-xs text-gray-400 pt-1">
        🔒 Form hanya tersedia bagi tamu yang tercantum dalam undangan
    </p>
    @endif

</div>

{{-- ══════════════════════════════════════════════
     STEP 2 — SUCCESS / FORM
══════════════════════════════════════════════ --}}
@else

@if($submitted)
{{-- ══════════ SUCCESS + E-TICKET ══════════ --}}
<div class="text-center py-4">
    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-3"
         style="background: rgba(var(--rsvp-accent-rgb, 48 109 41)/.1, var(--rsvp-accent-rgb, 48 109 41))">
        <div class="w-14 h-14 rounded-full flex items-center justify-center"
             style="background:rgba(0,0,0,0.07)">
            <svg class="w-7 h-7" style="color:var(--rsvp-accent,#306D29)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
    </div>
    <p class="font-serif text-xl text-gray-800 mb-1">Terima kasih!</p>
    <p class="text-sm text-gray-500">Konfirmasi kehadiran Anda telah kami terima.</p>
    @if($totalHadir > 0)
    <p class="text-xs text-gray-400 mt-1">Total tamu hadir: <strong>{{ number_format($totalHadir) }}</strong> orang</p>
    @endif

    {{-- KARTU KEHADIRAN --}}
    @if($guestQrUrl)
    <div class="mt-6" style="display:flex;justify-content:center">
        <div style="width:100%;max-width:260px">
            {{-- Card — overflow:hidden so absolute notch circles get half-clipped at card edge --}}
            <div style="border-radius:14px;border:1px solid #e5e7eb;box-shadow:0 4px 24px rgba(0,0,0,0.10);overflow:hidden;background:white">

                {{-- Header band --}}
                <div style="text-align:center;padding:10px 16px;background:var(--rsvp-gradient,linear-gradient(135deg,#306D29,#0D530E))">
                    <p style="color:white;font-size:10px;font-weight:600;letter-spacing:0.18em;text-transform:uppercase;margin:0">Kartu Kehadiran</p>
                </div>

                {{-- Ticket tear separator — circles are absolute so overflow:hidden clips their outer halves --}}
                <div style="position:relative;height:24px;background:white;display:flex;align-items:center">
                    <div style="position:absolute;left:-12px;top:50%;transform:translateY(-50%);width:24px;height:24px;border-radius:50%;background:var(--rsvp-section-bg,#f3f4f6)"></div>
                    <div style="flex:1;border-top:2px dashed #e5e7eb;margin:0 14px"></div>
                    <div style="position:absolute;right:-12px;top:50%;transform:translateY(-50%);width:24px;height:24px;border-radius:50%;background:var(--rsvp-section-bg,#f3f4f6)"></div>
                </div>

                {{-- QR code + guest info --}}
                <div style="background:white;padding:16px 24px 20px;text-align:center">
                    <div style="display:inline-block;background:white;padding:8px;border-radius:10px;border:1px solid #f3f4f6;box-shadow:0 1px 6px rgba(0,0,0,0.06)">
                        <img src="{{ $guestQrUrl }}" alt="QR Kehadiran" style="width:144px;height:144px;display:block;object-fit:contain">
                    </div>
                    @if($name)
                    <p style="font-family:Georgia,serif;color:#111827;font-size:1rem;margin-top:14px;font-weight:500;text-align:center">{{ $name }}</p>
                    @endif
                    <p style="font-size:11px;color:#9ca3af;margin-top:4px;text-align:center">
                        {{ $attendance === 'hadir' ? '✓ Hadir' : ($attendance === 'mungkin' ? '? Mungkin' : '✗ Tidak Hadir') }}
                        {{ ($attendance === 'hadir' && $guest_count > 1) ? '· '.$guest_count.' kursi' : '' }}
                    </p>
                </div>

                {{-- Footer instruction --}}
                <div style="background:#f9fafb;border-top:1px dashed #e5e7eb;padding:10px 16px;text-align:center">
                    <p style="font-size:11px;color:#6b7280;line-height:1.5;margin:0">Tunjukkan kepada panitia saat tiba di venue</p>
                </div>
            </div>
            <p style="font-size:11px;color:#9ca3af;margin-top:10px;text-align:center">💡 Screenshot halaman ini untuk menyimpan kartu</p>
        </div>
    </div>
    @endif
</div>

@else
{{-- ══════════ RSVP FORM ══════════ --}}
<form wire:submit="submit" class="space-y-4">

    {{-- Nama (locked) --}}
    <div>
        <label class="rsvp-label">Nama <span class="text-red-400">*</span></label>
        <div class="relative">
            <input type="text" wire:model="name" value="{{ $name }}"
                   readonly
                   class="rsvp-input rsvp-input--locked pr-9">
            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none"
                 style="color:var(--rsvp-accent,#306D29)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- No HP --}}
    <div>
        <label class="rsvp-label">No. HP / WhatsApp</label>
        <input type="tel" wire:model="phone" placeholder="08xxxxxxxxxx" class="rsvp-input">
    </div>

    {{-- Konfirmasi Kehadiran --}}
    <div>
        <label class="rsvp-label">Konfirmasi Kehadiran <span class="text-red-400">*</span></label>
        <div class="grid grid-cols-3 gap-2">
            @foreach(['hadir'=>'✓ Hadir','tidak_hadir'=>'✗ Tidak Hadir','mungkin'=>'? Mungkin'] as $val=>$lbl)
            <button type="button"
                    wire:click="$set('attendance','{{ $val }}')"
                    wire:key="att-{{ $val }}"
                    class="text-center py-2.5 px-1 rounded-xl border-2 text-xs font-medium transition-all cursor-pointer select-none"
                    style="{{ $attendance===$val
                        ? 'border-color:var(--rsvp-accent,#306D29);background:var(--rsvp-accent-bg,rgba(48,109,41,0.1));color:var(--rsvp-accent,#306D29)'
                        : 'border-color:#e5e7eb;color:#6b7280' }}">
                {{ $lbl }}
            </button>
            @endforeach
        </div>
        @error('attendance') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    @if($attendance === 'hadir')
    <div wire:key="guest-count-box">
        <label class="rsvp-label">Jumlah Tamu yang Hadir</label>
        <input type="number" wire:model="guest_count" min="1" max="20" class="rsvp-input">
    </div>
    @endif

    <div>
        <label class="rsvp-label">Pesan & Ucapan</label>
        <textarea wire:model="message" rows="3" placeholder="Tuliskan doa dan ucapan Anda..."
                  class="rsvp-input" style="resize:none"></textarea>
    </div>

    <button type="submit" wire:loading.attr="disabled"
            class="w-full py-3 rounded-xl text-white text-sm font-medium tracking-wide transition-all hover:opacity-90 active:scale-[0.99] disabled:opacity-60"
            style="background: var(--rsvp-gradient, linear-gradient(135deg,#306D29,#0D530E))">
        <span wire:loading.remove>Kirim Konfirmasi</span>
        <span wire:loading>Mengirim...</span>
    </button>
</form>
@endif

@endif {{-- end step --}}

{{-- ── Scoped styles for RSVP form ── --}}
<style>
.rsvp-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--rsvp-label, rgba(48,109,41,0.8));
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.375rem;
}
.rsvp-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border-radius: 0.75rem;
    border: 1.5px solid var(--rsvp-border, rgba(217,210,142,0.6));
    background: var(--rsvp-input-bg, rgba(251,245,221,0.4));
    color: var(--rsvp-text, #1f2937);
    font-size: 0.875rem;
    font-family: inherit;
    transition: all 0.2s ease;
    outline: none;
}
.rsvp-input::placeholder { color: var(--rsvp-placeholder, #9ca3af); }
.rsvp-input:focus {
    border-color: var(--rsvp-accent, #306D29);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--rsvp-accent, #306D29) 15%, transparent);
}
.rsvp-input--locked {
    background: var(--rsvp-locked-bg, rgba(48,109,41,0.04));
    border-color: var(--rsvp-accent, #306D29);
    opacity: 0.85;
    cursor: default;
}
</style>
</div>
