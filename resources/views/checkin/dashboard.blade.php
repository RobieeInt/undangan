<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Check-in — {{ $invitation->getCoupleName() }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScriptConfig
</head>
<body class="bg-cream/30 min-h-screen">

{{-- Header --}}
<div class="sticky top-0 z-10 bg-white/90 backdrop-blur border-b border-cream-dark/30 shadow-sm">
    <div class="max-w-lg mx-auto px-4 py-3 flex items-center gap-3">
        <a href="{{ route('dashboard') }}"
           class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1 min-w-0">
            <p class="font-serif text-base text-forest truncate">{{ $invitation->getCoupleName() }}</p>
            <p class="text-xs text-gray-400">Check-in Tamu</p>
        </div>
        <div class="w-8 h-8 bg-gradient-forest rounded-lg flex items-center justify-center">
            <svg class="w-4 h-4 text-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
    </div>
</div>

{{-- Content --}}
<div class="max-w-lg mx-auto px-4 py-6">
    <livewire:invitation.qr-checkin :invitation="$invitation" />
</div>

</body>
</html>
