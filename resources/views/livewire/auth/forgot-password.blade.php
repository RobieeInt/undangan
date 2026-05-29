<div>
    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-gray-800">Reset Password</h2>
        <p class="text-sm text-gray-500 mt-1">Kami akan kirim link reset ke email Anda</p>
    </div>

    @if($sent)
    <div class="text-center py-6">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="font-medium text-gray-800 mb-1">Email Terkirim!</h3>
        <p class="text-sm text-gray-500 mb-4">Cek inbox email Anda untuk link reset password.</p>
        <a href="{{ route('login') }}" class="btn-luxury">Kembali ke Login</a>
    </div>
    @else
    <form wire:submit="sendLink" class="space-y-4">
        <div>
            <label class="label-luxury">Email</label>
            <input type="email" wire:model="email" placeholder="email@terdaftar.com"
                   class="input-luxury @error('email') border-red-400 @enderror">
            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="btn-luxury w-full" wire:loading.attr="disabled">
            <span wire:loading.remove>Kirim Link Reset</span>
            <span wire:loading>Mengirim...</span>
        </button>
    </form>
    <p class="text-center text-sm text-gray-500 mt-6">
        <a href="{{ route('login') }}" class="text-forest hover:text-emerald transition-colors">← Kembali ke Login</a>
    </p>
    @endif
</div>
