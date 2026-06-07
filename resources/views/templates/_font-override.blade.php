@php
    $theme       = $invitation->theme ?? [];
    $fontBody    = $theme['font_body']    ?? null;
    $fontHeading = $theme['font_heading'] ?? null;
    $fontScale   = (float) ($theme['font_scale'] ?? 1.0);

    $googleFonts = [
        'Playfair Display'   => 'Playfair+Display:ital,wght@0,400;0,600;1,400',
        'Cormorant Garamond' => 'Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400',
        'Cinzel'             => 'Cinzel:wght@400;600',
        'DM Serif Display'   => 'DM+Serif+Display:ital@0;1',
        'Lora'               => 'Lora:ital,wght@0,400;0,600;1,400',
        'Libre Baskerville'  => 'Libre+Baskerville:ital,wght@0,400;0,700;1,400',
        'Montserrat'         => 'Montserrat:wght@300;400;500;600',
        'Poppins'            => 'Poppins:wght@300;400;500;600',
        'Nunito'             => 'Nunito:wght@300;400;600',
        'Raleway'            => 'Raleway:wght@300;400;500;600',
        'Great Vibes'        => 'Great+Vibes',
        'Pinyon Script'      => 'Pinyon+Script',
    ];

    $fontsToLoad = [];
    if ($fontBody    && isset($googleFonts[$fontBody]))
        $fontsToLoad[] = $googleFonts[$fontBody];
    if ($fontHeading && $fontHeading !== $fontBody && isset($googleFonts[$fontHeading]))
        $fontsToLoad[] = $googleFonts[$fontHeading];
@endphp

@if(count($fontsToLoad))
<link href="https://fonts.googleapis.com/css2?{{ implode('&', array_map(fn($f) => 'family='.$f, $fontsToLoad)) }}&display=swap" rel="stylesheet">
@endif

@php $galleryOrient = $theme['gallery_orientation'] ?? 'auto'; @endphp

@if($fontBody || $fontHeading || abs($fontScale - 1.0) > 0.01 || $galleryOrient !== 'auto')
<style id="theme-font-override">
@if(abs($fontScale - 1.0) > 0.01)
html { font-size: {{ number_format($fontScale * 16, 1) }}px !important; }
@endif
@if($fontBody)
body { font-family: '{{ $fontBody }}', sans-serif !important; }
@endif
@if($fontHeading)
h1, h2, h3, h4, h5, h6,
.fp, .fc,
.ff-garamond, .ff-cinzel, .ff-amiri,
.font-great, .font-cormo,
.font-serif-luxury, .font-cormorant,
.font-cinzel, .font-cinzel-reg, .font-pinyon { font-family: '{{ $fontHeading }}', serif !important; }
@endif
@php
$galAspect = match($galleryOrient) {
    'portrait'  => '2/3',
    'landscape' => '16/9',
    'square'    => '1/1',
    default     => null,
};
@endphp
@if($galAspect)
.al-gallery-grid a,
.br-gallery .br-gitem,
.je-gallery > a,
.je-gallery > div { aspect-ratio: {{ $galAspect }} !important; height: auto !important; min-height: unset !important; }
@endif
</style>
@endif
