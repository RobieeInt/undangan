@if($invitation->gift_address)
<div class="rounded-2xl overflow-hidden mb-5" style="border:1.5px solid rgba(175,147,52,0.3);background:rgba(175,147,52,0.07)">
    <div class="flex items-center gap-2.5 px-4 py-2.5" style="border-bottom:1px solid rgba(175,147,52,0.15)">
        <svg class="w-4 h-4 flex-shrink-0" style="color:rgba(140,110,30,0.8)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        <p style="font-size:0.65rem;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:rgba(140,110,30,0.85);margin:0">Hadiah Fisik</p>
    </div>
    <div class="px-4 py-3 space-y-1">
        <p style="font-size:0.72rem;color:rgba(120,92,20,0.7);margin:0">Kirim langsung ke alamat:</p>
        <p style="font-size:0.85rem;line-height:1.65;color:inherit;font-weight:500;opacity:0.85;margin:0">{{ $invitation->gift_address }}</p>
    </div>
</div>
@endif
