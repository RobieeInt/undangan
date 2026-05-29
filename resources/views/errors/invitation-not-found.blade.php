<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Undangan Tidak Ditemukan</title>
@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body class="bg-cream min-h-screen flex items-center justify-center p-6">
<div class="text-center max-w-sm">
    <div class="w-20 h-20 bg-gradient-forest rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-luxury">
        <svg class="w-10 h-10 text-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <h1 class="font-serif text-3xl text-gray-800 mb-2">Undangan Tidak Ditemukan</h1>
    <p class="text-gray-500 text-sm mb-6">Link undangan <code class="bg-gray-100 px-1.5 py-0.5 rounded text-xs">{{ $slug }}</code> tidak ditemukan, sudah kadaluarsa, atau belum dipublikasikan.</p>
    <a href="{{ config('app.url') }}" class="btn-luxury">Buat Undangan Anda</a>
</div>
</body>
</html>
