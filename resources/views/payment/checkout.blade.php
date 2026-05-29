@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="max-w-lg mx-auto px-4 py-12">
    <div class="text-center mb-8">
        <h1 class="font-serif text-3xl text-gray-800">Checkout</h1>
        <p class="text-gray-500 mt-1">Selesaikan pembayaran Anda</p>
    </div>

    <div class="card-luxury p-6 mb-6">
        <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Order ID</span><strong>{{ $transaction->order_id }}</strong></div>
            <div class="flex justify-between"><span class="text-gray-500">Paket</span><strong>{{ $transaction->package->name }}</strong></div>
            <div class="flex justify-between"><span class="text-gray-500">Total</span><strong class="text-forest text-lg">{{ $transaction->formatted_amount }}</strong></div>
        </div>
    </div>

    {{-- Status box (hidden by default, muncul saat polling) --}}
    <div id="status-box" class="hidden mb-4 p-4 rounded-xl text-sm text-center font-medium"></div>

    <div id="snap-container" class="text-center">
        <button id="pay-button" class="btn-luxury w-full text-base py-4">
            Bayar Sekarang
        </button>
    </div>

    <p class="text-center text-xs text-gray-400 mt-4">Pembayaran aman menggunakan Midtrans. Berbagai metode pembayaran tersedia.</p>
</div>

@push('scripts')
<script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
<script>
    const CHECK_URL  = '{{ route('payment.check-status', $transaction->id) }}';
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
    const FINISH_URL = '{{ route('editor', $invitation->id) }}';
    const PENDING_URL= '{{ route('payment.pending') }}';
    const ERROR_URL  = '{{ route('payment.error') }}';

    let pollInterval = null;

    // Cek status ke server kita → server cek ke Midtrans
    async function checkPaymentStatus() {
        try {
            const res  = await fetch(CHECK_URL, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            });
            const data = await res.json();

            if (data.is_paid) {
                stopPolling();
                showStatus('✅ Pembayaran berhasil! Mengaktifkan undangan...', 'bg-green-50 text-green-700 border border-green-200');
                setTimeout(() => window.location.href = FINISH_URL, 1500);
                return true;
            }

            if (data.status === 'failed' || data.status === 'expired') {
                stopPolling();
                showStatus('❌ Pembayaran gagal atau kadaluarsa.', 'bg-red-50 text-red-700 border border-red-200');
                return false;
            }

            if (data.status === 'pending') {
                showStatus('⏳ Menunggu konfirmasi pembayaran...', 'bg-yellow-50 text-yellow-700 border border-yellow-200');
            }
        } catch (e) {
            console.warn('Status check failed:', e);
        }
        return false;
    }

    function startPolling() {
        // Cek langsung sekali, lalu tiap 3 detik
        checkPaymentStatus();
        pollInterval = setInterval(async () => {
            const done = await checkPaymentStatus();
            if (done) stopPolling();
        }, 3000);

        // Stop polling setelah 5 menit
        setTimeout(stopPolling, 5 * 60 * 1000);
    }

    function stopPolling() {
        if (pollInterval) { clearInterval(pollInterval); pollInterval = null; }
    }

    function showStatus(msg, classes) {
        const box = document.getElementById('status-box');
        box.className = 'mb-4 p-4 rounded-xl text-sm text-center font-medium ' + classes;
        box.textContent = msg;
    }

    document.getElementById('pay-button').addEventListener('click', function() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                // Snap bilang sukses → verifikasi ke server, polling sampai benar-benar paid
                showStatus('✅ Pembayaran berhasil! Mengaktifkan undangan...', 'bg-green-50 text-green-700 border border-green-200');
                startPolling(); // polling proper, redirect hanya kalau is_paid = true
            },
            onPending: function(result) {
                // Bank transfer / VA / QRIS — polling sampai settlement, jangan redirect dulu
                showStatus('⏳ Menunggu konfirmasi pembayaran... Jangan tutup halaman ini.', 'bg-yellow-50 text-yellow-700 border border-yellow-200');
                startPolling();
                // Kalau 5 menit belum paid juga, baru redirect ke pending page
                setTimeout(function(){
                    if (pollInterval) {
                        stopPolling();
                        window.location.href = PENDING_URL;
                    }
                }, 5 * 60 * 1000);
            },
            onError: function(result) {
                showStatus('❌ Pembayaran gagal. Silakan coba lagi.', 'bg-red-50 text-red-700 border border-red-200');
                setTimeout(() => window.location.href = ERROR_URL, 2000);
            },
            onClose: function() {
                // User tutup popup tanpa bayar — cek status sekali (mungkin sudah bayar sebelumnya)
                checkPaymentStatus();
            }
        });
    });

    // Kalau buka halaman ini ulang (setelah pending) → cek status langsung
    @if($transaction->status === 'pending')
    startPolling();
    @endif
</script>
@endpush
@endsection
