<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScriptConfig
</head>
<body class="min-h-screen bg-gradient-luxury flex items-center justify-center p-4">

    {{-- Decorative background --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-forest/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-sand/40 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-cream/60 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-gradient-forest rounded-2xl flex items-center justify-center shadow-luxury group-hover:shadow-luxury-lg transition-shadow">
                    <svg class="w-7 h-7 text-cream" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                    </svg>
                </div>
                <span class="font-serif text-2xl text-forest">Undangan<span class="text-sand-dark">.</span>id</span>
            </a>
            <p class="text-sm text-gray-500 mt-1 font-light">Platform Undangan Online Premium</p>
        </div>

        {{-- Card --}}
        <div class="card-glass p-8">
            @yield('content')
            {{ $slot ?? '' }}
        </div>
    </div>

</body>
</html>
