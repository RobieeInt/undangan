{{--
    Bottom Navigation Bar — include di dalam `x-show="opened"` setiap template.
    Variabel yang dibutuhkan dari parent:
      - $invitation (Invitation)
      - $events (Collection)
      - $navStyle (optional): 'light'|'dark'|'blue' — default 'light'
--}}
@php
    $navStyle = $navStyle ?? 'light';
    $firstMapUrl = $events->firstWhere('venue_maps_url', '!=', null)?->venue_maps_url ?? null;

    // Warna per style
    $styles = [
        'light' => [
            'wrap'    => 'rgba(255,255,255,0.88)',
            'border'  => 'rgba(0,0,0,0.06)',
            'active'  => '#1a1a1a',
            'text'    => '#1a1a1a',
            'label'   => '#6b7280',
            'icon'    => '#6b7280',
        ],
        'dark' => [
            'wrap'    => 'rgba(20,20,30,0.88)',
            'border'  => 'rgba(255,255,255,0.08)',
            'active'  => '#ffffff',
            'text'    => '#ffffff',
            'label'   => 'rgba(255,255,255,0.5)',
            'icon'    => 'rgba(255,255,255,0.5)',
        ],
        'green' => [
            'wrap'    => 'rgba(48,109,41,0.92)',
            'border'  => 'rgba(255,255,255,0.1)',
            'active'  => '#ffffff',
            'text'    => '#ffffff',
            'label'   => 'rgba(255,255,255,0.55)',
            'icon'    => 'rgba(255,255,255,0.55)',
        ],
        'blue' => [
            'wrap'    => 'rgba(180,200,230,0.85)',
            'border'  => 'rgba(30,58,95,0.08)',
            'active'  => '#1e3a5f',
            'text'    => '#1e3a5f',
            'label'   => 'rgba(30,58,95,0.5)',
            'icon'    => 'rgba(30,58,95,0.5)',
        ],
        'andalusia' => [
            'wrap'        => 'rgba(8,18,8,0.96)',        /* deep emerald, more opaque */
            'border'      => 'rgba(212,175,55,0.38)',    /* gold border, more visible */
            'active'      => 'rgba(212,175,55,0.18)',    /* gold tint active bg */
            'active_icon' => '#D4AF37',                  /* gold icon when active */
            'text'        => '#D4AF37',                  /* gold label when active */
            'label'       => 'rgba(251,245,221,0.55)',   /* ivory, readable */
            'icon'        => 'rgba(251,245,221,0.50)',   /* ivory icons */
        ],
    ];
    $s = $styles[$navStyle] ?? $styles['light'];
    // active_icon fallback for styles that don't define it
    if (!isset($s['active_icon'])) {
        $s['active_icon'] = $navStyle === 'light' ? '#ffffff' : '#1a1a1a';
    }
@endphp

<nav x-data="{ activeNav: 'opening' }"
     class="fixed bottom-0 left-0 right-0 z-[90] px-3 pb-3 pointer-events-none">
    <div class="max-w-sm mx-auto rounded-2xl overflow-hidden pointer-events-auto shadow-xl"
         style="background: {{ $s['wrap'] }}; border: 1px solid {{ $s['border'] }}; backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);">
        <div class="flex items-stretch">

            {{-- Opening --}}
            <button class="flex-1 flex flex-col items-center justify-center gap-0.5 py-2.5 transition-all relative"
                    @click="activeNav='opening'; document.getElementById('nav-top')?.scrollIntoView({behavior:'smooth'})">
                <div class="w-9 h-9 flex items-center justify-center rounded-xl transition-all"
                     :style="activeNav==='opening' ? 'background:{{ $s['active'] }}' : 'background:transparent'">
                    <svg class="w-4 h-4 transition-colors" :style="activeNav==='opening' ? 'color:{{ $s['active_icon'] }}' : 'color:{{ $s['icon'] }}'"
                         fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </div>
                <span class="text-[9px] font-medium transition-colors"
                      :style="activeNav==='opening' ? 'color:{{ $s['text'] }}' : 'color:{{ $s['label'] }}'">
                    Opening
                </span>
            </button>

            {{-- Mempelai --}}
            <button class="flex-1 flex flex-col items-center justify-center gap-0.5 py-2.5 transition-all"
                    @click="activeNav='mempelai'; document.getElementById('nav-couple')?.scrollIntoView({behavior:'smooth'})">
                <div class="w-9 h-9 flex items-center justify-center rounded-xl transition-all"
                     :style="activeNav==='mempelai' ? 'background:{{ $s['active'] }}' : 'background:transparent'">
                    <svg class="w-4 h-4 transition-colors" :style="activeNav==='mempelai' ? 'color:{{ $s['active_icon'] }}' : 'color:{{ $s['icon'] }}'"
                         fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-[9px] font-medium transition-colors"
                      :style="activeNav==='mempelai' ? 'color:{{ $s['text'] }}' : 'color:{{ $s['label'] }}'">
                    Mempelai
                </span>
            </button>

            {{-- Acara --}}
            @if($events->isNotEmpty())
            <button class="flex-1 flex flex-col items-center justify-center gap-0.5 py-2.5 transition-all"
                    @click="activeNav='acara'; document.getElementById('nav-events')?.scrollIntoView({behavior:'smooth'})">
                <div class="w-9 h-9 flex items-center justify-center rounded-xl transition-all"
                     :style="activeNav==='acara' ? 'background:{{ $s['active'] }}' : 'background:transparent'">
                    <svg class="w-4 h-4 transition-colors" :style="activeNav==='acara' ? 'color:{{ $s['active_icon'] }}' : 'color:{{ $s['icon'] }}'"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <span class="text-[9px] font-medium transition-colors"
                      :style="activeNav==='acara' ? 'color:{{ $s['text'] }}' : 'color:{{ $s['label'] }}'">
                    Acara
                </span>
            </button>
            @endif

            {{-- Galeri --}}
            @if(isset($galleries) && $galleries->isNotEmpty())
            <button class="flex-1 flex flex-col items-center justify-center gap-0.5 py-2.5 transition-all"
                    @click="activeNav='galeri'; document.getElementById('nav-gallery')?.scrollIntoView({behavior:'smooth'})">
                <div class="w-9 h-9 flex items-center justify-center rounded-xl transition-all"
                     :style="activeNav==='galeri' ? 'background:{{ $s['active'] }}' : 'background:transparent'">
                    <svg class="w-4 h-4 transition-colors" :style="activeNav==='galeri' ? 'color:{{ $s['active_icon'] }}' : 'color:{{ $s['icon'] }}'"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
                <span class="text-[9px] font-medium transition-colors"
                      :style="activeNav==='galeri' ? 'color:{{ $s['text'] }}' : 'color:{{ $s['label'] }}'">
                    Galeri
                </span>
            </button>
            @endif

            {{-- Maps / Lokasi --}}
            @if($firstMapUrl)
            <a href="{{ $firstMapUrl }}" target="_blank" rel="noopener"
               class="flex-1 flex flex-col items-center justify-center gap-0.5 py-2.5 transition-all">
                <div class="w-9 h-9 flex items-center justify-center rounded-xl" style="background:transparent">
                    <svg class="w-4 h-4" style="color:{{ $s['icon'] }}"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <span class="text-[9px] font-medium" style="color:{{ $s['label'] }}">Maps</span>
            </a>
            @endif

        </div>
    </div>
</nav>
