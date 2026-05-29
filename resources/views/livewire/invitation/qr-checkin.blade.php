<div class="space-y-6">
    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="card-luxury p-4 text-center">
            <div class="text-3xl font-bold text-forest">{{ $totalGuests }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Tamu</div>
        </div>
        <div class="card-luxury p-4 text-center">
            <div class="text-3xl font-bold text-green-600">{{ $totalCheckedIn }}</div>
            <div class="text-xs text-gray-500 mt-1">Sudah Hadir</div>
        </div>
        <div class="card-luxury p-4 text-center">
            <div class="text-3xl font-bold text-amber-500">{{ $totalGuests - $totalCheckedIn }}</div>
            <div class="text-xs text-gray-500 mt-1">Belum Hadir</div>
        </div>
    </div>

    {{-- Last result --}}
    @if($lastResult)
    <div class="p-4 rounded-2xl text-center {{ $lastResult['success'] ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
        <p class="font-semibold {{ $lastResult['success'] ? 'text-green-700' : 'text-red-700' }}">
            {{ $lastResult['message'] }}
        </p>
        @if(isset($lastResult['seats']) && $lastResult['seats'] > 1)
        <p class="text-xs text-gray-500 mt-1">{{ $lastResult['seats'] }} kursi</p>
        @endif
    </div>
    @endif

    {{-- QR Scanner --}}
    <div x-data="qrScanner($wire)" class="card-luxury overflow-hidden">
        {{-- Toolbar --}}
        <div class="flex items-center justify-between px-5 py-4">
            <h3 class="font-serif text-lg text-gray-800">Scanner QR</h3>
            <button @click="scanning ? stopScan() : startScan()"
                    :class="scanning ? 'bg-red-50 text-red-600 border-red-200' : 'bg-forest text-cream border-transparent'"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all border">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span x-text="scanning ? 'Stop Scanner' : 'Mulai Scan'"></span>
            </button>
        </div>

        {{-- Camera view --}}
        <div x-show="scanning" class="relative w-full bg-black" style="aspect-ratio:4/3">
            <video id="qr-video" autoplay playsinline muted
                   class="w-full h-full object-cover"></video>
            <canvas id="qr-canvas" class="hidden"></canvas>

            {{-- Scan frame overlay --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="relative w-52 h-52">
                    {{-- Corner brackets --}}
                    <span class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-white rounded-tl-lg"></span>
                    <span class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-white rounded-tr-lg"></span>
                    <span class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-white rounded-bl-lg"></span>
                    <span class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-white rounded-br-lg"></span>
                    {{-- Scan line --}}
                    <div class="absolute left-2 right-2 h-0.5 bg-green-400 opacity-80 animate-bounce" style="top:50%"></div>
                </div>
            </div>
            <p class="absolute bottom-3 inset-x-0 text-center text-xs text-white/70">Arahkan kamera ke QR code tamu</p>
        </div>

        {{-- Idle state --}}
        <div x-show="!scanning" class="py-10 text-center px-5">
            <div class="w-16 h-16 bg-forest/10 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-500">Klik <strong>Mulai Scan</strong> untuk mengaktifkan kamera</p>
        </div>
    </div>

    {{-- Recent check-ins --}}
    @if(count($recentCheckins) > 0)
    <div class="card-luxury p-6">
        <h3 class="font-serif text-lg text-gray-800 mb-4">Check-in Terakhir</h3>
        <div class="space-y-2">
            @foreach($recentCheckins as $checkin)
            <div class="flex items-center justify-between py-2 border-b border-cream-dark/20 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-800">{{ $checkin->name }}</span>
                </div>
                <span class="text-xs text-gray-400">
                    {{ \Carbon\Carbon::parse($checkin->checked_in_at)->format('H:i') }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Refresh --}}
    <button wire:click="loadStats" wire:loading.attr="disabled"
            class="btn-sand w-full text-sm py-3">
        <svg wire:loading class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        </svg>
        <span wire:loading.remove>Refresh Data</span>
        <span wire:loading>Memuat...</span>
    </button>
</div>
