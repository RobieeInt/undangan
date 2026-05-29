<div>
    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-gray-800">Buat Password Baru</h2>
        <p class="text-sm text-gray-500 mt-1">Masukkan password baru Anda</p>
    </div>

    <form wire:submit="resetPassword" class="space-y-4">
        <div>
            <label class="label-luxury">Email</label>
            <input type="email" wire:model="email" class="input-luxury @error('email') border-red-400 @enderror"
                   placeholder="email@terdaftar.com" readonly>
            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label-luxury">Password Baru</label>
            <input type="password" wire:model="password" class="input-luxury @error('password') border-red-400 @enderror"
                   placeholder="Minimal 8 karakter">
            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label-luxury">Konfirmasi Password</label>
            <input type="password" wire:model="password_confirmation" class="input-luxury"
                   placeholder="Ulangi password baru">
        </div>

        <button type="submit" class="btn-luxury w-full" wire:loading.attr="disabled">
            <svg wire:loading class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <span wire:loading.remove>Simpan Password Baru</span>
            <span wire:loading>Menyimpan...</span>
        </button>
    </form>
</div>
