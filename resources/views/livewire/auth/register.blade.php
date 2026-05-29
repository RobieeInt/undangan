<div>
    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-gray-800">Buat Akun</h2>
        <p class="text-sm text-gray-500 mt-1">Mulai buat undangan cantik Anda hari ini</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <div>
            <label class="label-luxury">Nama Lengkap</label>
            <input type="text" wire:model="name" placeholder="Nama Anda"
                   class="input-luxury @error('name') border-red-400 @enderror">
            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label-luxury">Email</label>
            <input type="email" wire:model="email" placeholder="nama@email.com"
                   class="input-luxury @error('email') border-red-400 @enderror">
            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label-luxury">Password</label>
            <input type="password" wire:model="password" placeholder="Minimal 8 karakter"
                   class="input-luxury @error('password') border-red-400 @enderror">
            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label-luxury">Konfirmasi Password</label>
            <input type="password" wire:model="password_confirmation" placeholder="Ulangi password"
                   class="input-luxury">
        </div>

        <div>
            <label class="flex items-start gap-2 cursor-pointer">
                <input type="checkbox" wire:model="agreeTerms" class="mt-0.5 rounded border-sand-dark text-forest focus:ring-forest/30">
                <span class="text-sm text-gray-600">Saya menyetujui <a href="#" class="text-forest hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-forest hover:underline">Kebijakan Privasi</a></span>
            </label>
            @error('agreeTerms') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn-luxury w-full mt-2" wire:loading.attr="disabled">
            <svg wire:loading class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            <span wire:loading.remove>Buat Akun Gratis</span>
            <span wire:loading>Memproses...</span>
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-forest font-medium hover:text-emerald transition-colors">Masuk</a>
    </p>
</div>
