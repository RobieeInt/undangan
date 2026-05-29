@extends('layouts.app')
@section('title', 'Invora.id — Platform Undangan Online Premium')
@section('content')
<div class="min-h-screen flex flex-col items-center justify-center text-center px-6 py-20">
    <div class="max-w-2xl mx-auto">
        <div class="w-20 h-20 bg-gradient-forest rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-luxury" data-aos="fade-down">
            <svg class="w-10 h-10 text-cream" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
        </div>
        <h1 class="font-serif text-5xl md:text-6xl text-gray-800 leading-tight mb-4" data-aos="fade-up">
            Undangan Online<br><span class="text-gradient-forest">Premium & Modern</span>
        </h1>
        <p class="text-lg text-gray-500 mb-10 font-light" data-aos="fade-up" data-aos-delay="100">
            Buat undangan digital yang elegan, personal, dan berkesan. Bagikan dengan mudah ke semua tamu Anda.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('register') }}" class="btn-luxury text-base py-4 px-8">Mulai Gratis Sekarang</a>
            <a href="{{ route('login') }}" class="btn-luxury-outline text-base py-4 px-8">Masuk</a>
        </div>
    </div>
</div>
@endsection
