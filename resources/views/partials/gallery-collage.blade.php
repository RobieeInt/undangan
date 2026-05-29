{{--
    Reusable Gallery Collage
    ─────────────────────────────────────────────────────────────────
    Variables (passed via @include):
      $galleries    – Collection of GalleryPhoto models (->image, ->caption)
      $gcCellClass  – Extra CSS class(es) for each cell  (optional, e.g. 'rounded-2xl')
      $gcCapClass   – CSS class(es) for caption text     (optional, e.g. 'text-forest/60')
      $gcGap        – Gap between cells in px            (optional, default 4)
--}}

@php
$gcCount   = $galleries->count();
$gcItems   = $galleries->values();
$gcPat     = $gcCount === 0 ? 'empty'
           : ($gcCount >= 10 ? 'stream' : (string)$gcCount);
$gcGapPx   = $gcGap ?? 4;

// Parallax speed per slot — subtle variation for depth (0 = no parallax, 1 = full)
$gcSpeeds  = [0.55, 0.40, 0.65, 0.45, 0.58, 0.38, 0.62, 0.42, 0.50, 0.48, 0.60, 0.35];
@endphp

{{-- ── Shared CSS (rendered only once even if included multiple times) ── --}}
@once
<style>
/* ════════════════════════════════════════════════════════
   GALLERY COLLAGE — base grid + cell styles + parallax
   ════════════════════════════════════════════════════════ */
.gc-grid {
    display: grid;
    width: 100%;
}
.gc-cell {
    overflow: hidden;
    position: relative;
    min-height: 0;
    min-width: 0;
}
.gc-inner {
    width: 100%;
    height: 100%;
    overflow: hidden;
    position: relative;
}
/* Image: taller than container to allow parallax shift ±25 px */
.gc-img {
    position: absolute;
    left: 0;
    top: -25px;
    width: 100%;
    height: calc(100% + 50px);
    object-fit: cover;
    object-position: center;
    display: block;
    will-change: transform;
    transition: transform 0.05s linear;
}
.gc-caption {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    padding: 6px 8px;
    font-size: 10px;
    background: rgba(0,0,0,.35);
    color: #fff;
    text-align: center;
    line-height: 1.3;
}

/* ── LAYOUT PATTERNS ───────────────────────────────── */

/* 1 photo — full-width landscape */
.gc-n1 {
    grid-template-columns: 1fr;
    grid-auto-rows: clamp(180px, 55vw, 260px);
}

/* 2 photos — two portrait columns */
.gc-n2 {
    grid-template-columns: 1fr 1fr;
    grid-auto-rows: clamp(150px, 50vw, 240px);
}

/* 3 photos — big left (portrait×2) + 2 stacked right */
.gc-n3 {
    grid-template-columns: 3fr 2fr;
    grid-template-rows: clamp(120px, 32vw, 160px) clamp(120px, 32vw, 160px);
}
.gc-n3 .gc-cell:nth-child(1) { grid-row: span 2; }

/* 4 photos — 2 × 2 grid */
.gc-n4 {
    grid-template-columns: 1fr 1fr;
    grid-auto-rows: clamp(140px, 40vw, 200px);
}

/* 5 photos — wide+narrow top row, 3-col bottom row */
.gc-n5 {
    grid-template-columns: 1fr 1fr 1fr;
    grid-template-rows: clamp(110px, 34vw, 170px) clamp(110px, 30vw, 150px);
}
.gc-n5 .gc-cell:nth-child(1) { grid-column: span 2; }

/* 6 photos — 3 × 2 grid */
.gc-n6 {
    grid-template-columns: 1fr 1fr 1fr;
    grid-auto-rows: clamp(100px, 30vw, 150px);
}

/* 7 photos — full-width banner + 2 rows of 3 */
.gc-n7 {
    grid-template-columns: 1fr 1fr 1fr;
    grid-auto-rows: clamp(95px, 28vw, 140px);
}
.gc-n7 .gc-cell:nth-child(1) { grid-column: span 3; }

/* 8 photos — 2 × 4 equal grid */
.gc-n8 {
    grid-template-columns: 1fr 1fr;
    grid-auto-rows: clamp(130px, 36vw, 180px);
}

/* 9 photos — 3 × 3 grid */
.gc-n9 {
    grid-template-columns: 1fr 1fr 1fr;
    grid-auto-rows: clamp(90px, 26vw, 130px);
}

/* 10+ photos — stream: wide banner every 3rd, rest 2-col */
.gc-nstream {
    grid-template-columns: 1fr 1fr;
    grid-auto-rows: clamp(120px, 35vw, 175px);
}
.gc-nstream .gc-cell:nth-child(3n+1) { grid-column: span 2; }

/* ── Lightbox overlay ─────────────────────────────── */
#gc-lightbox {
    display: none;
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.92);
    align-items: center; justify-content: center;
    padding: 16px;
}
#gc-lightbox.open { display: flex; }
#gc-lightbox img {
    max-width: 100%; max-height: 90vh;
    object-fit: contain;
    border-radius: 4px;
    box-shadow: 0 8px 40px rgba(0,0,0,.6);
    animation: gcLbIn .25s ease-out;
}
@keyframes gcLbIn {
    from { opacity:0; transform:scale(.92); }
    to   { opacity:1; transform:scale(1); }
}
#gc-lightbox-close {
    position: absolute; top: 16px; right: 20px;
    font-size: 28px; color: #fff; cursor: pointer;
    line-height: 1; opacity: .7;
}
#gc-lightbox-close:hover { opacity: 1; }
#gc-lightbox-prev, #gc-lightbox-next {
    position: absolute; top: 50%; transform: translateY(-50%);
    font-size: 36px; color: #fff; cursor: pointer;
    opacity: .6; padding: 12px; user-select: none;
    line-height: 1;
}
#gc-lightbox-prev:hover, #gc-lightbox-next:hover { opacity: 1; }
#gc-lightbox-prev { left: 8px; }
#gc-lightbox-next { right: 8px; }
#gc-lightbox-caption {
    position: absolute; bottom: 20px; left: 50%;
    transform: translateX(-50%);
    color: rgba(255,255,255,.7); font-size: 12px;
    white-space: nowrap; text-align: center;
}
</style>
@endonce

{{-- ── Collage grid HTML ──────────────────────────── --}}
@if($gcCount > 0)
<div class="gc-grid gc-n{{ $gcPat }}" style="gap:{{ $gcGapPx }}px" id="gc-collage">
    @foreach($gcItems as $gi => $gphoto)
    @php $gcSpeed = $gcSpeeds[$gi % count($gcSpeeds)]; @endphp
    <div class="gc-cell {{ $gcCellClass ?? '' }}"
         data-gc-speed="{{ $gcSpeed }}"
         data-gc-idx="{{ $gi }}"
         data-gc-src="{{ $gphoto->image_url }}"
         data-gc-cap="{{ $gphoto->caption ?? '' }}"
         style="cursor:pointer">
        <div class="gc-inner">
            <img src="{{ $gphoto->image_url }}"
                 alt="{{ $gphoto->caption ?? '' }}"
                 class="gc-img"
                 loading="{{ $gi < 4 ? 'eager' : 'lazy' }}"
                 decoding="async">
        </div>
        @if(!empty($gphoto->caption))
        <div class="gc-caption">{{ $gphoto->caption }}</div>
        @endif
    </div>
    @endforeach
</div>

{{-- Lightbox (rendered once) --}}
<div id="gc-lightbox" role="dialog" aria-modal="true">
    <span id="gc-lightbox-close" aria-label="Close">×</span>
    <span id="gc-lightbox-prev" aria-label="Previous">‹</span>
    <img id="gc-lightbox-img" src="" alt="">
    <span id="gc-lightbox-next" aria-label="Next">›</span>
    <div id="gc-lightbox-caption"></div>
</div>
@endif

{{-- ── Parallax + lightbox JS (rendered only once) ── --}}
@once
<script>
(function () {
    'use strict';

    /* ── Parallax ───────────────────────────────────── */
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var gcImgs   = [];
    var heroImgs = []; // elements with data-parallax-hero
    var ticking  = false;

    function collectElements() {
        gcImgs   = Array.from(document.querySelectorAll('.gc-img'));
        heroImgs = Array.from(document.querySelectorAll('[data-parallax-hero]'));
    }

    function updateParallax() {
        var vh = window.innerHeight;
        var sy = window.scrollY;

        // Gallery cell inner parallax
        gcImgs.forEach(function (img) {
            var cell = img.closest('.gc-cell');
            if (!cell) return;
            var rect  = cell.getBoundingClientRect();
            if (rect.bottom < -80 || rect.top > vh + 80) return;
            // progress: 0 when cell bottom at viewport bottom, 1 when cell top at viewport top
            var progress = (vh - rect.top) / (vh + rect.height);
            var speed    = parseFloat(cell.dataset.gcSpeed || 0.5);
            // range: translateY from +25 (entering from bottom) to -25 (exiting at top)
            var offset   = (0.5 - progress) * 50 * speed;
            offset       = Math.max(-25, Math.min(25, offset));
            img.style.transform = 'translateY(' + offset.toFixed(2) + 'px)';
        });

        // Hero background parallax
        heroImgs.forEach(function (el) {
            var rect  = el.getBoundingClientRect();
            if (rect.bottom < 0 || rect.top > vh) return;
            var progress = -rect.top / (rect.height + vh);
            var offset   = progress * 60; // max 60px shift
            el.style.transform = 'translateY(' + offset.toFixed(2) + 'px)';
        });

        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }

    if (!prefersReducedMotion) {
        document.addEventListener('DOMContentLoaded', function () {
            collectElements();
            updateParallax();
            window.addEventListener('scroll', onScroll, { passive: true });
        });
        // Also run immediately if DOM already ready
        if (document.readyState !== 'loading') {
            collectElements();
            updateParallax();
            window.addEventListener('scroll', onScroll, { passive: true });
        }
    }

    /* ── Lightbox ───────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', function () {
        var lb      = document.getElementById('gc-lightbox');
        var lbImg   = document.getElementById('gc-lightbox-img');
        var lbCap   = document.getElementById('gc-lightbox-caption');
        var lbClose = document.getElementById('gc-lightbox-close');
        var lbPrev  = document.getElementById('gc-lightbox-prev');
        var lbNext  = document.getElementById('gc-lightbox-next');
        var cells   = Array.from(document.querySelectorAll('.gc-cell'));
        var curIdx  = 0;

        if (!lb || !cells.length) return;

        function openLb(idx) {
            curIdx = (idx + cells.length) % cells.length;
            var c  = cells[curIdx];
            lbImg.src  = c.dataset.gcSrc || '';
            lbImg.alt  = c.dataset.gcCap || '';
            lbCap.textContent = c.dataset.gcCap || '';
            lb.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeLb() {
            lb.classList.remove('open');
            document.body.style.overflow = '';
        }

        cells.forEach(function (c, i) {
            c.addEventListener('click', function () { openLb(i); });
        });

        lbClose.addEventListener('click', closeLb);
        lbPrev.addEventListener('click',  function () { openLb(curIdx - 1); });
        lbNext.addEventListener('click',  function () { openLb(curIdx + 1); });

        lb.addEventListener('click', function (e) {
            if (e.target === lb) closeLb();
        });

        document.addEventListener('keydown', function (e) {
            if (!lb.classList.contains('open')) return;
            if (e.key === 'ArrowRight') openLb(curIdx + 1);
            if (e.key === 'ArrowLeft')  openLb(curIdx - 1);
            if (e.key === 'Escape')     closeLb();
        });

        // Touch swipe on lightbox
        var touchStartX = 0;
        lb.addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].clientX;
        }, { passive: true });
        lb.addEventListener('touchend', function (e) {
            var dx = e.changedTouches[0].clientX - touchStartX;
            if (Math.abs(dx) > 40) { dx > 0 ? openLb(curIdx - 1) : openLb(curIdx + 1); }
        }, { passive: true });
    });
})();
</script>
@endonce
