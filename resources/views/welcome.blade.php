@extends('layouts.app')
@section('title', 'Invora.id — Undangan Online Premium & Modern')
@section('description', 'Buat undangan digital elegan untuk pernikahanmu. 9 template eksklusif, RSVP online, QR check-in, dan banyak lagi.')

@push('head')
<style>
/* ── Hero gradient ── */
.lp-hero {
    background: linear-gradient(160deg,#0a1f0a 0%,#1a3a1a 40%,#2d5a2d 70%,#1a3a1a 100%);
    position: relative;
    overflow: hidden;
}
.lp-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 60% 50% at 80% 20%, rgba(201,168,106,.12) 0%, transparent 60%),
        radial-gradient(ellipse 40% 40% at 10% 80%, rgba(48,109,41,.18) 0%, transparent 55%);
    pointer-events: none;
}

/* ── Phone mockup ── */
.lp-phone {
    width: 110px;
    height: 188px;
    border-radius: 16px;
    border: 3px solid rgba(255,255,255,.12);
    box-shadow: 0 8px 32px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.08);
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
    transition: transform .3s ease, box-shadow .3s ease;
}
.lp-phone:hover {
    transform: translateY(-4px) scale(1.03);
    box-shadow: 0 16px 48px rgba(0,0,0,.4);
}
.lp-phone-notch {
    position: absolute;
    top: 0; left: 50%;
    transform: translateX(-50%);
    width: 36px; height: 6px;
    background: rgba(0,0,0,.5);
    border-radius: 0 0 6px 6px;
    z-index: 2;
}
/* Simulated UI lines */
.lp-ui-line { height: 3px; border-radius: 2px; margin-bottom: 5px; }
.lp-ui-dot  { width: 28px; height: 28px; border-radius: 50%; margin: 8px auto 6px; }

/* ── Pricing card highlight ── */
.lp-price-popular {
    transform: scale(1.02);
    box-shadow: 0 20px 60px rgba(48,109,41,.2), 0 0 0 2px #306D29;
}
@media(max-width:767px){ .lp-price-popular{ transform:none } }

/* ── Checklist ── */
.lp-check { color: #306D29 }
.lp-cross  { color: #d1d5db }

/* ── Pricing grid ── */
.lp-pkg-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
    max-width: 26rem;
    margin: 0 auto;
    padding: 0 1.25rem;
}
@media (min-width: 768px) {
    .lp-pkg-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        max-width: 72rem;
        padding: 0 1.25rem;
        align-items: start;
    }
    .lp-pkg-popular {
        margin-top: -0.875rem;
        margin-bottom: 0.875rem;
    }
}

/* ── Step connector ── */
.lp-step-line {
    position: absolute;
    top: 24px; left: calc(50% + 32px);
    width: calc(100% - 64px);
    height: 2px;
    background: linear-gradient(to right, #306D29, #d1fae5);
}
@media(max-width:767px){ .lp-step-line{ display:none } }

/* ── Pricing CTA hover ── */
.lp-pkg-cta:hover { filter: brightness(1.1); transform: translateY(-1px) }
.lp-cta-gray:hover { background: #e5e7eb !important }

/* ── Scroll fade ── */
[data-lp] {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity .65s ease, transform .65s ease;
}
[data-lp].visible { opacity:1; transform:translateY(0) }
</style>
@endpush

@section('content')

{{-- ════════════════════════════════════════
     HERO
════════════════════════════════════════ --}}
<section class="lp-hero min-h-[92vh] flex flex-col items-center justify-center text-center px-5 py-20">
    {{-- Badge --}}
    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 rounded-full px-4 py-1.5 text-xs text-white/75 mb-7 font-medium tracking-wide">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
        Platform Undangan Digital #1 Indonesia
    </div>

    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold text-white leading-tight max-w-3xl mx-auto mb-5">
        Undangan Online<br>
        <span style="background:linear-gradient(135deg,#C9A86A,#D4AF37,#E8C96A);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">
            Elegan & Premium
        </span>
    </h1>

    <p class="text-base sm:text-lg text-white/60 max-w-xl mx-auto mb-10 leading-relaxed font-light">
        Buat undangan pernikahan digital yang berkesan.<br class="hidden sm:block">
        9 template eksklusif, RSVP online, QR check-in tamu, dan berbagi mudah.
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center w-full max-w-sm sm:max-w-none">
        <a href="{{ route('register') }}"
           class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl font-semibold text-sm
                  bg-[#306D29] hover:bg-[#255520] text-white transition-all shadow-lg shadow-green-900/30
                  hover:shadow-xl hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Undangan Gratis
        </a>
        <a href="#templates"
           class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl font-semibold text-sm
                  bg-white/10 hover:bg-white/18 backdrop-blur-sm border border-white/20 text-white
                  transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Lihat Template
        </a>
    </div>

    {{-- Stats bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-16 w-full max-w-2xl mx-auto">
        @foreach([
            ['9+','Template Premium'],
            ['3','Paket Harga'],
            ['100%','Mobile Friendly'],
            ['∞','Tamu Unlimited*'],
        ] as $stat)
        <div class="bg-white/8 backdrop-blur-sm border border-white/12 rounded-xl py-3 px-2 text-center">
            <p class="text-xl font-bold text-white font-serif">{{ $stat[0] }}</p>
            <p class="text-[10px] text-white/50 mt-0.5 font-medium tracking-wide uppercase">{{ $stat[1] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Scroll hint --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <svg class="w-5 h-5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
    </div>
</section>

{{-- ════════════════════════════════════════
     CARA KERJA
════════════════════════════════════════ --}}
<section class="py-14 px-5 bg-white" data-lp>
    <div class="max-w-4xl mx-auto text-center mb-8">
        <p class="text-xs font-semibold tracking-[.22em] text-[#306D29] uppercase mb-2">Mudah & Cepat</p>
        <h2 class="text-xl sm:text-2xl font-serif font-bold text-gray-800">Cara Membuat Undangan</h2>
    </div>
    <div class="max-w-3xl mx-auto grid grid-cols-3 gap-3 sm:gap-6 text-center relative">
        @foreach([
            ['1','Pilih Template','Pilih dari 9 template eksklusif sesuai tema pernikahan.','M4 5a2 2 0 012-2h4.586A2 2 0 0112 3.586L15.414 7A2 2 0 0116 8.414V19a2 2 0 01-2 2H6a2 2 0 01-2-2V5z'],
            ['2','Aktifkan Paket','Bayar sekali, paket langsung aktif sesuai pilihanmu.','M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
            ['3','Bagikan ke Tamu','Salin link & bagikan. RSVP tamu masuk otomatis.','M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z'],
        ] as $i => $step)
        <div class="relative flex flex-col items-center
            {{ $i < 2 ? 'after:absolute after:top-4 sm:after:top-5 after:left-[calc(50%+20px)] sm:after:left-[calc(50%+28px)] after:w-[calc(100%-40px)] sm:after:w-[calc(100%-56px)] after:h-px after:bg-gradient-to-r after:from-[#306D29]/40 after:to-[#306D29]/10' : '' }}">
            <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center mb-2 sm:mb-3 shadow-md flex-shrink-0"
                 style="background:linear-gradient(135deg,#306D29,#4a8a3a)">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $step[3] }}"/>
                </svg>
            </div>
            <span class="text-[9px] sm:text-[10px] font-bold text-[#306D29] tracking-widest mb-0.5 sm:mb-1">STEP {{ $step[0] }}</span>
            <h3 class="font-semibold text-gray-800 text-xs sm:text-sm mb-1">{{ $step[1] }}</h3>
            <p class="text-[10px] sm:text-xs text-gray-500 leading-snug hidden sm:block">{{ $step[2] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- ════════════════════════════════════════
     TEMPLATE SHOWCASE
════════════════════════════════════════ --}}
<section id="templates" class="py-20 px-5 bg-gray-50" data-lp>
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <p class="text-xs font-semibold tracking-[.22em] text-[#306D29] uppercase mb-3">9 Pilihan Template</p>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-gray-800 mb-3">Template Eksklusif</h2>
            <p class="text-gray-500 text-sm max-w-md mx-auto">Dari modern minimalis hingga tradisional Jawa mewah — semua tersedia untuk momen spesialmu.</p>
        </div>

        {{-- Filter badges --}}
        <div class="flex flex-wrap justify-center gap-2 mb-10">
            @foreach([['Semua','all'],['Gratis','free'],['Premium','premium'],['Exclusive','exclusive']] as $f)
            <button onclick="filterTemplate('{{ $f[1] }}')" data-filter="{{ $f[1] }}"
                    class="lp-filter px-4 py-1.5 rounded-full text-xs font-semibold border transition-all
                           {{ $f[1]==='all' ? 'bg-[#306D29] text-white border-[#306D29]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#306D29] hover:text-[#306D29]' }}">
                {{ $f[0] }}
            </button>
            @endforeach
        </div>

        {{-- Template grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-4 sm:gap-6" id="template-grid">
            @php
            $templateMeta = [
                'floral-luxury'       => ['gradient'=>'linear-gradient(160deg,#0d2b0d 0%,#1e521e 50%,#306D29 100%)','accent'=>'rgba(201,168,106,.9)','desc'=>'Floral elegan, nuansa hijau segar'],
                'dark-elegant'        => ['gradient'=>'linear-gradient(160deg,#0a0a1a 0%,#1a1a3a 55%,#2a2a4a 100%)','accent'=>'rgba(201,168,106,.9)','desc'=>'Mewah gelap, aksen emas klasik'],
                'emerald-islamic'     => ['gradient'=>'linear-gradient(160deg,#051505 0%,#0a3a0a 55%,#155215 100%)','accent'=>'rgba(201,168,106,.8)','desc'=>'Islami, nuansa hijau emerald'],
                'minimalist-modern'   => ['gradient'=>'linear-gradient(160deg,#1a1a1a 0%,#2d2d2d 50%,#404040 100%)','accent'=>'rgba(255,255,255,.8)','desc'=>'Bersih, modern, tipografi premium'],
                'blue-butterfly'      => ['gradient'=>'linear-gradient(160deg,#050e28 0%,#0f254d 55%,#1e3d7a 100%)','accent'=>'rgba(147,197,253,.9)','desc'=>'Romantis, nuansa biru langit'],
                'jawa-klasik'         => ['gradient'=>'linear-gradient(160deg,#1e0c04 0%,#3d1c08 55%,#5c3010 100%)','accent'=>'rgba(201,168,106,.9)','desc'=>'Tradisional Jawa, batik klasik'],
                'jawa-exclusive'      => ['gradient'=>'linear-gradient(160deg,#120800 0%,#2e1a08 55%,#4A3324 100%)','accent'=>'rgba(212,175,55,1)','desc'=>'Keraton Jawa, mewah & sakral'],
                'andalusia-exclusive' => ['gradient'=>'linear-gradient(160deg,#050e08 0%,#0a2010 55%,#0d3a1a 100%)','accent'=>'rgba(212,175,55,1)','desc'=>'Arsitektur Andalusia Islam, Alhambra'],
                'batavia-royale'      => ['gradient'=>'linear-gradient(160deg,#030810 0%,#0a1428 55%,#0F1E3A 100%)','accent'=>'rgba(212,175,55,1)','desc'=>'Colonial Batavia, Navy & Gold'],
            ];
            @endphp

            @foreach($templates as $tpl)
            @php
                $meta  = $templateMeta[$tpl->slug] ?? ['gradient'=>'linear-gradient(135deg,#1a1a1a,#333)','accent'=>'rgba(255,255,255,.7)','desc'=>'Template elegan'];
                $tier  = $tpl->is_exclusive ? 'exclusive' : ($tpl->is_premium ? 'premium' : 'free');
                $tierLabel = $tpl->is_exclusive ? 'Exclusive' : ($tpl->is_premium ? 'Premium' : 'Gratis');
                $tierColor = $tpl->is_exclusive
                    ? 'bg-purple-600 text-white'
                    : ($tpl->is_premium ? 'bg-amber-500 text-white' : 'bg-emerald-600 text-white');
            @endphp
            <div class="lp-tpl-card group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl
                        transition-all duration-300 hover:-translate-y-1 border border-gray-100"
                 data-tier="{{ $tier }}">

                {{-- Phone mockup area --}}
                <div class="relative flex items-center justify-center py-6 px-4"
                     style="{{ $meta['gradient'] }}; background: {{ $meta['gradient'] }}">

                    {{-- Subtle bg pattern dots --}}
                    <div class="absolute inset-0 opacity-10"
                         style="background-image:radial-gradient(circle,rgba(255,255,255,.3) 1px,transparent 1px);background-size:16px 16px"></div>

                    {{-- Phone --}}
                    <div class="lp-phone" style="background:{{ $meta['gradient'] }}">
                        <div class="lp-phone-notch"></div>
                        {{-- Simulated invitation UI --}}
                        <div class="pt-7 px-2.5 flex flex-col items-center" style="gap:0">
                            {{-- Cover photo placeholder --}}
                            <div class="w-full h-16 rounded-md mb-2 opacity-30" style="background:rgba(255,255,255,.15)"></div>
                            {{-- Names --}}
                            <div class="lp-ui-line w-16" style="background:{{ $meta['accent'] }};opacity:.85"></div>
                            <div class="lp-ui-line w-10 opacity-50" style="background:{{ $meta['accent'] }}"></div>
                            <div class="lp-ui-line w-16" style="background:{{ $meta['accent'] }};opacity:.85"></div>
                            {{-- Date line --}}
                            <div class="lp-ui-line w-20 mt-1 opacity-40" style="background:rgba(255,255,255,.4)"></div>
                            {{-- CTA button --}}
                            <div class="mt-3 w-20 h-5 rounded-md opacity-60" style="background:{{ $meta['accent'] }}"></div>
                        </div>
                    </div>

                    {{-- Tier badge --}}
                    <span class="absolute top-3 right-3 text-[9px] font-bold tracking-wider px-2 py-0.5 rounded-full {{ $tierColor }}">
                        {{ $tierLabel }}
                    </span>
                </div>

                {{-- Info --}}
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 text-sm mb-0.5">{{ $tpl->name }}</h3>
                    <p class="text-[11px] text-gray-400 mb-3 leading-snug">{{ $meta['desc'] }}</p>
                    <a href="{{ route('template.preview', $tpl->slug) }}" target="_blank"
                       class="flex items-center justify-center gap-1.5 w-full py-2 rounded-lg text-xs font-semibold
                              border border-[#306D29]/30 text-[#306D29] hover:bg-[#306D29] hover:text-white
                              transition-all group-hover:border-[#306D29]">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat Preview
                    </a>
                </div>

            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════
     PRICING
════════════════════════════════════════ --}}
<section id="pricing" class="py-20 bg-white" data-lp>
    <div class="max-w-5xl mx-auto px-5">
        <div class="text-center mb-10">
            <p class="text-xs font-semibold tracking-[.22em] text-[#306D29] uppercase mb-3">Harga Transparan</p>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-gray-800 mb-3">Pilih Paket yang Sesuai</h2>
            <p class="text-gray-500 text-sm max-w-md mx-auto">Bayar sekali, nikmati selamanya selama masa aktif. Tidak ada biaya tersembunyi.</p>
        </div>
    </div>

    {{-- Cards --}}
    <div class="lp-pkg-grid">

        @foreach($packages as $pkg)
        @php
            $isPopular = $pkg->slug === 'premium';
            $isTop     = $pkg->slug === 'exclusive';

            $allFeatures = [
                ['Masa aktif '.($pkg->duration_days >= 365 ? '1 tahun' : $pkg->duration_days.' hari'), true],
                [$pkg->max_guests.' tamu',                                               true],
                [$pkg->max_gallery.' foto galeri',                                       true],
                ['RSVP online',                                                          true],
                ['Link undangan personal',                                               true],
                ['Template '.($pkg->has_all_templates ? 'semua + premium' : 'dasar'),   true],
                ['Tanpa watermark',                                                      !$pkg->has_watermark],
                ['QR Check-in tamu',                                                     $pkg->has_qr_checkin],
                ['Analytics & statistik',                                                $pkg->has_analytics],
                ['Export data RSVP',                                                     $pkg->has_rsvp_export],
                ['Custom domain',                                                        $pkg->has_custom_domain],
                ['Priority support',                                                     $pkg->slug === 'exclusive'],
            ];

            // Inline styles — aman dari Tailwind purge
            $headerStyle  = $isPopular
                ? 'background:#306D29'
                : ($isTop ? 'background:linear-gradient(135deg,#6d28d9,#4c1d95)' : 'background:#1e293b');
            $borderStyle  = $isPopular
                ? 'border-color:#306D29;box-shadow:0 0 0 3px rgba(48,109,41,.15)'
                : ($isTop ? 'border-color:#a855f7;box-shadow:0 0 0 3px rgba(168,85,247,.12)' : 'border-color:#e5e7eb');
            $ctaStyle     = $isPopular
                ? 'background:#306D29;color:#fff'
                : ($isTop ? 'background:#7c3aed;color:#fff' : 'background:#f3f4f6;color:#1f2937');
            $ctaHoverClass = $isPopular ? 'lp-cta-green' : ($isTop ? 'lp-cta-purple' : 'lp-cta-gray');
        @endphp

        <div class="relative bg-white rounded-2xl border-2 overflow-hidden shadow-sm transition-all duration-300 hover:shadow-xl {{ $isPopular ? 'lp-pkg-popular' : '' }}"
             style="{{ $borderStyle }}">

            {{-- Badge --}}
            @if($isPopular)
            <div class="absolute top-3 right-3 text-[10px] font-bold tracking-wide px-2.5 py-1 rounded-full"
                 style="background:#fbbf24;color:#78350f">⭐ TERPOPULER</div>
            @elseif($isTop)
            <div class="absolute top-3 right-3 text-[10px] font-bold tracking-wide px-2.5 py-1 rounded-full"
                 style="background:#7c3aed;color:#fff">👑 TERLENGKAP</div>
            @endif

            {{-- Header --}}
            <div class="px-5 pt-5 pb-4" style="{{ $headerStyle }}">
                <p style="color:rgba(255,255,255,.6);font-size:10px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;margin-bottom:.5rem">
                    {{ $pkg->name }}
                </p>
                <div style="display:flex;align-items:baseline;gap:4px">
                    <span style="font-size:11px;color:rgba(255,255,255,.65);font-weight:500">Rp</span>
                    <span style="font-size:2rem;font-weight:700;color:#fff;font-family:Georgia,serif;line-height:1">
                        {{ number_format($pkg->price / 1000, 0, ',', '.') }}rb
                    </span>
                </div>
                <p style="color:rgba(255,255,255,.45);font-size:11px;margin-top:.5rem">
                    Aktif {{ $pkg->duration_days >= 365 ? '1 tahun' : $pkg->duration_days.' hari' }}
                    &nbsp;·&nbsp; Bayar sekali
                </p>
            </div>

            {{-- Feature list --}}
            <div class="px-5 py-4">
                <ul class="space-y-2.5 mb-5">
                    @foreach($allFeatures as [$label, $has])
                    @if($has)
                    <li class="flex items-center gap-2.5 text-[13px]" style="color:#374151">
                        <span class="flex-shrink-0 w-4 h-4 rounded-full flex items-center justify-center"
                              style="background:rgba(48,109,41,.12)">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#306D29" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>{{ $label }}</span>
                    </li>
                    @endif
                    @endforeach
                </ul>

                <a href="{{ route('register') }}"
                   class="lp-pkg-cta {{ $ctaHoverClass }}"
                   style="{{ $ctaStyle }};display:flex;align-items:center;justify-content:center;width:100%;padding:.625rem 1rem;border-radius:.75rem;font-size:.875rem;font-weight:600;transition:filter .2s,transform .15s;text-decoration:none">
                    Mulai dengan {{ $pkg->name }}
                </a>
            </div>

        </div>
        @endforeach

    </div>

    <p class="text-center text-xs text-gray-400 mt-6 px-5">* Harga dapat berubah sewaktu-waktu.</p>
</section>

{{-- ════════════════════════════════════════
     FITUR UNGGULAN
════════════════════════════════════════ --}}
<section class="py-20 px-5 bg-gray-50" data-lp>
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-12">
            <p class="text-xs font-semibold tracking-[.22em] text-[#306D29] uppercase mb-3">Kenapa Invora?</p>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-gray-800">Fitur yang Membuatmu Berbeda</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach([
                ['📱','Mobile First','Tampil sempurna di semua perangkat. Tamu buka dari HP langsung terlihat indah.'],
                ['🎨','9 Template Eksklusif','Dari Batavia Royale hingga Jawa Exclusive. Setiap template punya animasi unik.'],
                ['✅','RSVP Real-time','Tamu konfirmasi kehadiran langsung di undangan. Kamu lihat datanya seketika.'],
                ['📷','Galeri Foto','Upload foto prewedding hingga 100 foto. Ditampilkan dalam grid yang elegan.'],
                ['📲','QR Check-in Tamu','Verifikasi kehadiran tamu di hari H dengan scan QR code. Efisien & modern.'],
                ['🎵','Musik Latar','Tambahkan lagu favorit sebagai backsound undangan. Suasana makin berkesan.'],
            ] as $f)
            <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-[#306D29]/30 hover:shadow-md transition-all">
                <div class="text-2xl mb-3">{{ $f[0] }}</div>
                <h3 class="font-semibold text-gray-800 text-sm mb-1.5">{{ $f[1] }}</h3>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $f[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════
     FINAL CTA
════════════════════════════════════════ --}}
<section class="py-20 px-5" style="background:linear-gradient(135deg,#0a1f0a 0%,#1a3a1a 50%,#2d5a2d 100%)" data-lp>
    <div class="max-w-xl mx-auto text-center">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xl"
             style="background:rgba(255,255,255,.1);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.15)">
            <svg class="w-7 h-7 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
            </svg>
        </div>
        <h2 class="text-2xl sm:text-3xl font-serif font-bold text-white mb-4">
            Siap Buat Undangan<br>Impianmu?
        </h2>
        <p class="text-white/55 text-sm mb-8 leading-relaxed">
            Daftar sekarang, pilih template, dan undanganmu siap dibagikan dalam hitungan menit.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('register') }}"
               class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl font-semibold text-sm
                      text-[#306D29] bg-white hover:bg-gray-50 transition-all shadow-lg hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Daftar Sekarang — Gratis
            </a>
            @guest
            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl font-semibold text-sm
                      text-white bg-white/10 hover:bg-white/18 border border-white/20 transition-all hover:-translate-y-0.5">
                Sudah punya akun? Masuk
            </a>
            @endguest
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════
     FOOTER
════════════════════════════════════════ --}}
<footer class="bg-gray-900 text-gray-400 py-8 px-5">
    <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-sm">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-md flex items-center justify-center" style="background:#306D29">
                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
            </div>
            <span class="font-semibold text-white font-serif">Invora<span class="text-[#C9A86A]">.</span>id</span>
        </div>
        <p class="text-xs text-gray-500">© {{ date('Y') }} Invora.id — Platform Undangan Online Premium</p>
        <div class="flex items-center gap-4 text-xs">
            <a href="#templates" class="hover:text-white transition-colors">Template</a>
            <a href="#pricing"   class="hover:text-white transition-colors">Harga</a>
            <a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk</a>
        </div>
    </div>
</footer>

{{-- ── JS: filter + scroll reveal ── --}}
<script>
/* Template filter */
function filterTemplate(tier) {
    document.querySelectorAll('.lp-filter').forEach(function(btn) {
        var active = btn.dataset.filter === tier;
        btn.classList.toggle('bg-[#306D29]',  active);
        btn.classList.toggle('text-white',     active);
        btn.classList.toggle('border-[#306D29]', active);
        btn.classList.toggle('bg-white',       !active);
        btn.classList.toggle('text-gray-600',  !active);
        btn.classList.toggle('border-gray-200',!active);
    });
    document.querySelectorAll('.lp-tpl-card').forEach(function(card) {
        var match = tier === 'all' || card.dataset.tier === tier;
        card.style.display = match ? '' : 'none';
    });
}

/* Scroll reveal */
(function(){
    var io = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if(e.isIntersecting){
                e.target.classList.add('visible');
                io.unobserve(e.target);
            }
        });
    },{threshold:.08});
    document.querySelectorAll('[data-lp]').forEach(function(el){ io.observe(el) });
})();
</script>

@endsection
