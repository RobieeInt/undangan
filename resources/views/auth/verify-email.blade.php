@extends('layouts.auth')
@section('title', 'Verifikasi Email')
@section('content')
<div class="text-center">
    <div class="w-16 h-16 bg-cream rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
    </div>
    <h2 class="font-serif text-2xl text-gray-800 mb-2">Verifikasi Email</h2>
    <p class="text-sm text-gray-500 mb-6">Kami telah mengirim link verifikasi ke email Anda. Silakan periksa inbox Anda.</p>

    @if (session('status') == 'verification-link-sent')
    <div class="p-3 rounded-xl bg-green-50 border border-green-200 text-sm text-green-700 mb-4">
        Link verifikasi baru telah dikirim ke email Anda.
    </div>
    @endif

    <div class="flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button class="btn-luxury w-full">Kirim Ulang Email Verifikasi</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm text-gray-500 hover:text-forest transition-colors">Keluar</button>
        </form>
    </div>
</div>
@endsection
