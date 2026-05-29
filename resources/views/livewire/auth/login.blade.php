<div>
    <div class="mb-6">
        <h2 class="font-serif text-2xl font-semibold text-gray-800">Selamat Datang</h2>
        <p class="text-sm text-gray-500 mt-1">Masuk ke akun Invora.id Anda</p>
    </div>

    @if(session('status'))
    <div class="mb-4 p-3 rounded-xl bg-green-50 border border-green-200 text-sm text-green-700">{{ session('status') }}</div>
    @endif

    <form wire:submit="login" class="space-y-4">
        <div>
            <label class="label-luxury">Email</label>
            <input type="email" wire:model="email" placeholder="nama@email.com"
                   class="input-luxury @error('email') border-red-400 @enderror">
            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label-luxury">Password</label>
            <div class="relative">
                <input type="password"
                       wire:model="password"
                       id="login-password"
                       placeholder="••••••••"
                       class="input-luxury pr-10 @error('password') border-red-400 @enderror">
                <button type="button"
                        onclick="(function(btn){var i=document.getElementById('login-password');var show=i.type==='text';i.type=show?'password':'text';btn.querySelector('.icon-eye').style.display=show?'block':'none';btn.querySelector('.icon-eye-off').style.display=show?'none':'block';})(this)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg class="icon-eye w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg class="icon-eye-off w-4 h-4" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" wire:model="remember" class="rounded border-sand-dark text-forest focus:ring-forest/30">
                <span class="text-sm text-gray-600">Ingat saya</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-forest hover:text-emerald transition-colors">Lupa password?</a>
        </div>

        <button type="submit" class="btn-luxury w-full mt-2" wire:loading.attr="disabled">
            <svg wire:loading class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            <span wire:loading.remove>Masuk</span>
            <span wire:loading>Memproses...</span>
        </button>
    </form>

    <div class="divider-luxury my-6">
        <span class="text-xs text-gray-400 px-3">Belum punya akun?</span>
    </div>

    <a href="{{ route('register') }}" class="btn-luxury-outline w-full text-center block">Daftar Gratis</a>
</div>
