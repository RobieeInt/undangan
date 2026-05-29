@extends('layouts.app')
@section('title', 'Pilih Paket')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">

    {{-- Header --}}
    <div class="text-center mb-10">
        <p class="text-sm text-forest/60 uppercase tracking-widest mb-2">Langkah 2 dari 2</p>
        <h1 class="font-serif text-4xl text-gray-800">Pilih Paket Undangan</h1>
        <p class="text-gray-500 mt-2">Aktifkan undangan Anda untuk mulai mengedit dan membagikannya</p>
    </div>

    {{-- Template yang dipilih --}}
    @if($template)
    <div class="max-w-sm mx-auto mb-8">
        <div class="card-luxury p-4 flex items-center gap-4">
            {{-- Thumbnail --}}
            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gradient-luxury shrink-0">
                @if($template->thumbnail)
                    <img src="{{ Storage::url($template->thumbnail) }}" class="w-full h-full object-cover" alt="{{ $template->name }}">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-forest/30" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-gray-400 mb-0.5">Template dipilih</p>
                <p class="font-semibold text-gray-800 truncate">{{ $template->name }}</p>
                <span class="inline-block text-[10px] font-semibold px-2 py-0.5 rounded-full mt-1
                    {{ $template->is_exclusive ? 'bg-amber-100 text-amber-700' : ($template->is_premium ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700') }}">
                    {{ $template->getTierLabel() }}
                </span>
            </div>
            <div class="shrink-0">
                <svg class="w-5 h-5 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        {{-- Notice kalau template premium/exclusive --}}
        @if($needsAllTemplates)
        <div class="mt-3 flex items-start gap-2.5 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-800">
            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Template <strong>{{ $template->name }}</strong> membutuhkan paket Premium atau Exclusive.
        </div>
        @endif
    </div>
    @endif

    {{-- Nama pasangan --}}
    <p class="text-center text-sm text-gray-400 mb-8">
        Untuk undangan: <span class="font-semibold text-gray-700">{{ $invitation->getCoupleName() }}</span>
    </p>

    {{-- Grid paket --}}
    <div class="grid grid-cols-1 md:grid-cols-{{ $packages->count() == 1 ? '1' : ($packages->count() == 2 ? '2' : '3') }} gap-6 max-w-4xl mx-auto">
        @foreach($packages as $pkg)
        <div class="card-luxury p-6 relative flex flex-col {{ $pkg->slug === 'premium' ? 'ring-2 ring-forest' : '' }}"
             data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

            @if($pkg->slug === 'premium')
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-forest text-cream text-xs font-semibold px-4 py-1.5 rounded-full whitespace-nowrap">
                Paling Populer
            </div>
            @elseif($pkg->slug === 'exclusive')
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-500 to-yellow-400 text-white text-xs font-semibold px-4 py-1.5 rounded-full whitespace-nowrap">
                Terlengkap
            </div>
            @endif

            <div class="text-center mb-6">
                <h3 class="font-serif text-2xl text-gray-800">{{ $pkg->name }}</h3>
                <p class="text-3xl font-bold text-forest mt-2">{{ $pkg->formatted_price }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $pkg->duration_label }}</p>
            </div>

            <ul class="space-y-2.5 mb-8 flex-1">
                @foreach($pkg->features ?? [] as $feature)
                <li class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-forest flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>

            <form method="POST" action="{{ route('payment.checkout', $invitation->id) }}">
                @csrf
                <input type="hidden" name="package_id" value="{{ $pkg->id }}">
                <button type="submit"
                        class="{{ $pkg->slug === 'premium' || $pkg->slug === 'exclusive' ? 'btn-luxury' : 'btn-luxury-outline' }} w-full">
                    Pilih Paket {{ $pkg->name }}
                </button>
            </form>
        </div>
        @endforeach
    </div>

    {{-- Back to dashboard --}}
    <div class="text-center mt-8">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
            ← Kembali ke Dashboard
        </a>
    </div>

</div>
@endsection
