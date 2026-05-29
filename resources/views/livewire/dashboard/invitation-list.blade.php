<div
    x-data="{
        toast: { show: false, msg: '', editUrl: '' },
        showToast(msg, editUrl) {
            this.toast = { show: true, msg, editUrl };
            setTimeout(() => this.toast.show = false, 6000);
        }
    }"
    @invitation-created.window="showToast($event.detail.invitationId ? 'Undangan berhasil dibuat! 🎉' : '', $event.detail.editUrl)"
>

    {{-- ── Success Toast ── --}}
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[300] flex items-center gap-3
                bg-white border border-green-200 shadow-xl rounded-2xl px-5 py-3.5
                min-w-[280px] max-w-sm"
         style="display:none">
        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800" x-text="toast.msg"></p>
            <p class="text-xs text-gray-500 mt-0.5">Undangan muncul di daftar di bawah</p>
        </div>
        <a :href="toast.editUrl"
           class="shrink-0 text-xs font-semibold text-forest hover:text-emerald
                  bg-forest/10 hover:bg-forest/20 px-3 py-1.5 rounded-lg transition-all">
            Edit →
        </a>
        <button @click="toast.show = false" class="shrink-0 text-gray-300 hover:text-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <div>
            <h1 class="font-serif text-3xl text-gray-800">Undangan Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola semua undangan digital Anda</p>
        </div>
        @if($atLimit)
        <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-2.5 text-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Batas undangan belum aktif tercapai ({{ $inactiveCount }}/2) — aktifkan atau hapus yang ada
        </div>
        @else
        <button wire:click="openCreateModal" class="btn-luxury shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Undangan Baru
        </button>
        @endif
    </div>

    {{-- Error limit (fallback dari createInvitation) --}}
    @error('limit')
    <div class="mb-6 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ $message }}
    </div>
    @enderror

    {{-- Empty state --}}
    @if($invitations->isEmpty())
    <div class="text-center py-20 card-luxury" data-aos="fade-up">
        <div class="w-20 h-20 bg-cream rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-forest/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <h3 class="font-serif text-xl text-gray-700 mb-2">Belum ada undangan</h3>
        <p class="text-sm text-gray-400 mb-6 max-w-sm mx-auto">Buat undangan digital pertama Anda dan bagikan momen spesial Anda</p>
        @if(!$atLimit)
        <button wire:click="openCreateModal" class="btn-luxury">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Undangan Pertama
        </button>
        @endif
    </div>
    @else
    {{-- Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($invitations as $inv)
        <div class="card-luxury overflow-hidden group hover:-translate-y-1 transition-all duration-300
                    {{ $newInvitationId === $inv->id ? 'ring-2 ring-forest/50 shadow-lg' : '' }}"
             data-aos="fade-up"
             wire:key="inv-{{ $inv->id }}">
            {{-- Template thumbnail --}}
            <div class="relative h-48 bg-gradient-luxury overflow-hidden">
                @if($inv->template_thumbnail)
                <img src="{{ Storage::url($inv->template_thumbnail) }}" alt="{{ $inv->template_name }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-forest/30 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                        <p class="text-xs text-forest/50 font-medium">{{ $inv->template_name }}</p>
                    </div>
                </div>
                @endif

                {{-- Status badge --}}
                <div class="absolute top-3 left-3">
                    @if($inv->is_active && $inv->is_published && !\Carbon\Carbon::parse($inv->expires_at)->isPast())
                        <span class="badge-active"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Aktif & Publik</span>
                    @elseif($inv->is_active && !\Carbon\Carbon::parse($inv->expires_at)->isPast())
                        <span class="badge-pending"><span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>Draft</span>
                    @elseif(!$inv->is_active)
                        <span class="badge-draft">Belum Dibayar</span>
                    @else
                        <span class="badge-expired">Kadaluarsa</span>
                    @endif
                </div>

                {{-- View count --}}
                <div class="absolute top-3 right-3 bg-black/30 backdrop-blur-sm rounded-full px-2.5 py-1 flex items-center gap-1">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span class="text-white text-xs">{{ number_format($inv->view_count) }}</span>
                </div>
            </div>

            {{-- Info --}}
            <div class="p-5">
                <h3 class="font-serif text-lg text-gray-800 mb-0.5">
                    {{ $inv->groom_name }} & {{ $inv->bride_name }}
                </h3>
                <p class="text-xs text-gray-400">{{ $inv->template_name }}
                    @if($inv->package_name) · {{ $inv->package_name }} @endif
                </p>

                @if($inv->expires_at)
                @php $expiredCard = $inv->is_active && \Carbon\Carbon::parse($inv->expires_at)->isPast(); @endphp
                @if($expiredCard)
                <div class="mt-2 text-xs text-red-500 flex items-center gap-1 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Kadaluarsa {{ \Carbon\Carbon::parse($inv->expires_at)->format('d M Y') }}
                </div>
                @else
                <div class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-forest/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Aktif hingga {{ \Carbon\Carbon::parse($inv->expires_at)->format('d M Y') }}
                </div>
                @endif
                @endif

                {{-- URL --}}
                <div class="mt-3 flex items-center gap-2 bg-cream/60 rounded-lg px-3 py-2">
                    <span class="text-xs text-forest/60 truncate flex-1">{{ url('/' . $inv->slug) }}</span>
                    <button onclick="navigator.clipboard.writeText('{{ url('/' . $inv->slug) }}')"
                            class="text-forest/40 hover:text-forest transition-colors flex-shrink-0" title="Salin link">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    </button>
                </div>

                {{-- Actions --}}
                @php
                    $isExpiredCard = $inv->is_active && $inv->expires_at && \Carbon\Carbon::parse($inv->expires_at)->isPast();
                @endphp
                <div class="flex items-center gap-2 mt-4">
                    @if(!$inv->is_active)
                    {{-- Belum bayar → aktifkan + hapus --}}
                    <a href="{{ route('payment.select-package', $inv->id) }}" class="btn-luxury flex-1 text-xs py-2.5 justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Aktifkan Sekarang
                    </a>
                    <button wire:click="confirmDelete({{ $inv->id }})"
                            title="Hapus undangan"
                            class="shrink-0 w-9 h-9 flex items-center justify-center rounded-xl
                                   border border-red-200 bg-red-50 text-red-400
                                   hover:bg-red-100 hover:text-red-600 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                    @elseif($isExpiredCard)
                    {{-- Kadaluarsa → edit + lihat + perpanjang, NO check-in --}}
                    <a href="{{ route('editor', $inv->id) }}" class="btn-luxury flex-1 text-xs py-2.5 justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <a href="{{ url('/' . $inv->slug) }}" target="_blank" class="btn-sand text-xs py-2.5 px-3">
                        Lihat
                    </a>
                    <a href="{{ route('payment.select-package', $inv->id) }}"
                       class="shrink-0 text-xs py-2.5 px-3 rounded-xl font-semibold
                              bg-amber-50 text-amber-700 border border-amber-200
                              hover:bg-amber-100 transition-all flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Perpanjang
                    </a>
                    @else
                    {{-- Aktif & valid → edit + lihat + check-in --}}
                    <a href="{{ route('editor', $inv->id) }}" class="btn-luxury flex-1 text-xs py-2.5 justify-center">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <a href="{{ url('/' . $inv->slug) }}" target="_blank" class="btn-sand text-xs py-2.5 px-3">
                        Lihat
                    </a>
                    <a href="{{ route('checkin.dashboard', $inv->id) }}" title="Check-in Tamu"
                       class="btn-sand text-xs py-2.5 px-3">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Create Modal --}}
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
         x-data="{ previewUrl: '', showPreview: false }"
         @keydown.escape.window="showPreview ? showPreview=false : $wire.closeModal()">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeModal"></div>

        {{-- ── Preview Modal (phone frame) ── --}}
        <div x-show="showPreview" x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 scale-95"
             class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
             @click.self="showPreview=false">
            <div class="relative flex flex-col items-center">
                {{-- Close --}}
                <button @click="showPreview=false; previewUrl=''"
                        class="absolute -top-10 right-0 text-white/80 hover:text-white flex items-center gap-1.5 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tutup Preview
                </button>
                {{-- Phone frame --}}
                <div class="relative" style="width:320px">
                    {{-- Phone bezel --}}
                    <div class="absolute inset-0 rounded-[2.5rem] ring-[10px] ring-gray-800 shadow-2xl pointer-events-none z-10"></div>
                    {{-- Notch --}}
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-28 h-6 bg-gray-800 rounded-b-2xl z-20"></div>
                    {{-- Home bar --}}
                    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 w-24 h-1 bg-gray-600 rounded-full z-20"></div>
                    {{-- iframe --}}
                    <iframe :src="previewUrl" class="w-full rounded-[2.5rem] bg-white"
                            style="height:600px;border:none;display:block"
                            loading="lazy"></iframe>
                </div>
                <p class="text-white/50 text-xs mt-3">Preview menggunakan data contoh</p>
            </div>
        </div>

        <div class="relative w-full max-w-lg card-luxury p-6 sm:p-8 animate-slide-up">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-serif text-xl text-gray-800">Buat Undangan Baru</h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="createInvitation" class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label-luxury">Nama Pengantin Pria</label>
                        <input type="text" wire:model="groomName" placeholder="Contoh: Robby"
                               class="input-luxury @error('groomName') border-red-400 @enderror">
                        @error('groomName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label-luxury">Nama Pengantin Wanita</label>
                        <input type="text" wire:model="brideName" placeholder="Contoh: Bella"
                               class="input-luxury @error('brideName') border-red-400 @enderror">
                        @error('brideName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="label-luxury">Pilih Template</label>
                    @error('selectedTemplate') <p class="text-xs text-red-500 mb-2">{{ $message }}</p> @enderror
                    <div class="grid grid-cols-2 gap-3 max-h-80 overflow-y-auto pr-1">
                        @php
                        $gradients = [
                            'floral-luxury'        => 'from-[#FBF5DD] to-[#c8d89a]',
                            'dark-elegant'         => 'from-[#1a1a2e] to-[#16213e]',
                            'emerald-islamic'      => 'from-[#0D530E] to-[#306D29]',
                            'minimalist-modern'    => 'from-[#f8f9fa] to-[#e9ecef]',
                            'blue-butterfly'       => 'from-[#dbeafe] to-[#3b82f6]',
                            'jawa-klasik'          => 'from-[#150303] to-[#600101]',
                            'jawa-exclusive'       => 'from-[#FBF5DD] to-[#306D29]',
                            'andalusia-exclusive'  => 'from-[#0A1F0B] to-[#1A3A1B]',
                        ];
                        @endphp
                        @foreach($templates as $tpl)
                        @php $grad = $gradients[$tpl->slug] ?? 'from-cream to-sand'; @endphp
                        <div class="relative" wire:key="tpl-{{ $tpl->id }}">
                            <label class="cursor-pointer block">
                                <input type="radio" wire:model.live="selectedTemplate" value="{{ $tpl->id }}" class="sr-only">
                                <div class="rounded-xl overflow-hidden border-2 transition-all duration-200 select-none
                                            {{ $selectedTemplate == $tpl->id
                                                ? 'border-forest ring-2 ring-forest/30 shadow-lg scale-[1.02]'
                                                : 'border-gray-200 hover:border-forest/50' }}">
                                    {{-- Thumbnail / Gradient --}}
                                    <div class="h-28 overflow-hidden relative">
                                        @if($tpl->thumbnail)
                                        <img src="{{ Storage::url($tpl->thumbnail) }}" class="w-full h-full object-cover" alt="{{ $tpl->name }}">
                                        @else
                                        <div class="w-full h-full bg-gradient-to-br {{ $grad }} flex flex-col items-center justify-center gap-2">
                                            @if($tpl->slug === 'blue-butterfly')
                                            <svg class="w-10 h-10 text-blue-400/70" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C9 2 6 5 6 9c-3-1-5 1-5 4s3 5 6 4c0 2 2 5 5 5s5-3 5-5c3 1 6-1 6-4s-2-5-5-4c0-4-3-7-6-7z" opacity=".4"/><path d="M12 4c-2 0-4 2.5-4 6 1.5-.5 3-.5 4 0s2.5.5 4 0c0-3.5-2-6-4-6z"/></svg>
                                            @elseif($tpl->slug === 'dark-elegant')
                                            <svg class="w-8 h-8 text-yellow-400/60" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @elseif($tpl->slug === 'emerald-islamic')
                                            <svg class="w-8 h-8 text-white/60" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/></svg>
                                            @elseif($tpl->slug === 'jawa-klasik')
                                            {{-- Kawung motif icon --}}
                                            <svg class="w-10 h-10 opacity-70" viewBox="0 0 40 40" fill="none">
                                                <ellipse cx="20" cy="8"  rx="6" ry="8" fill="none" stroke="#BA9F54" stroke-width="1"/>
                                                <ellipse cx="20" cy="32" rx="6" ry="8" fill="none" stroke="#BA9F54" stroke-width="1"/>
                                                <ellipse cx="8"  cy="20" rx="8" ry="6" fill="none" stroke="#BA9F54" stroke-width="1"/>
                                                <ellipse cx="32" cy="20" rx="8" ry="6" fill="none" stroke="#BA9F54" stroke-width="1"/>
                                                <circle  cx="20" cy="20" r="3" fill="#BA9F54" opacity=".8"/>
                                            </svg>
                                            @elseif($tpl->slug === 'jawa-exclusive')
                                            {{-- Jasmine + parang icon --}}
                                            <svg class="w-10 h-10 opacity-75" viewBox="0 0 40 40" fill="none">
                                                {{-- Parang curve --}}
                                                <path d="M4 28 Q14 8 26 20 Q34 28 36 12" stroke="#C9A227" stroke-width="1" fill="none" opacity=".6"/>
                                                {{-- Jasmine flower --}}
                                                <ellipse cx="20" cy="13" rx="2" ry="5" fill="#306D29" opacity=".7"/>
                                                <ellipse cx="20" cy="13" rx="2" ry="5" fill="#306D29" opacity=".7" transform="rotate(72 20 20)"/>
                                                <ellipse cx="20" cy="13" rx="2" ry="5" fill="#306D29" opacity=".7" transform="rotate(144 20 20)"/>
                                                <ellipse cx="20" cy="13" rx="2" ry="5" fill="#306D29" opacity=".7" transform="rotate(216 20 20)"/>
                                                <ellipse cx="20" cy="13" rx="2" ry="5" fill="#306D29" opacity=".7" transform="rotate(288 20 20)"/>
                                                <circle  cx="20" cy="20" r="3.5" fill="white" opacity=".8"/>
                                                <circle  cx="20" cy="20" r="1.5" fill="#C9A227" opacity=".9"/>
                                            </svg>
                                            @elseif($tpl->slug === 'andalusia-exclusive')
                                            {{-- Arabesque 8-pointed star + arch --}}
                                            <svg class="w-10 h-10 opacity-80" viewBox="0 0 40 40" fill="none">
                                                {{-- Arch --}}
                                                <path d="M8,38 L8,18 Q8,4 20,4 Q32,4 32,18 L32,38" stroke="#D4AF37" stroke-width="0.8" fill="none" opacity=".6"/>
                                                {{-- 8-point star --}}
                                                <polygon points="20,9 21.5,16 28,17.5 21.5,19 20,26 18.5,19 12,17.5 18.5,16" fill="none" stroke="#D4AF37" stroke-width="0.9" opacity=".8"/>
                                                <polygon points="20,9 21.5,16 28,17.5 21.5,19 20,26 18.5,19 12,17.5 18.5,16" fill="none" stroke="#D4AF37" stroke-width="0.5" opacity=".4" transform="rotate(22.5 20 17.5)"/>
                                                <circle cx="20" cy="17.5" r="2.5" fill="#D4AF37" opacity=".5"/>
                                            </svg>
                                            @else
                                            <svg class="w-8 h-8 text-forest/40" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                    {{-- Label --}}
                                    <div class="p-2 {{ $selectedTemplate == $tpl->id ? 'bg-forest/5' : 'bg-white' }}">
                                        <p class="text-xs font-semibold text-gray-800 truncate">{{ $tpl->name }}</p>
                                        <p class="text-[10px] {{ $tpl->is_premium ? 'text-amber-500' : 'text-gray-400' }}">{{ $tpl->getTierLabel() }}</p>
                                    </div>
                                    {{-- Checkmark --}}
                                    @if($selectedTemplate == $tpl->id)
                                    <div class="absolute top-2 left-2 w-6 h-6 bg-forest rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    @endif
                                </div>
                            </label>
                            {{-- Preview button (top-right, above the radio label) --}}
                            <button type="button"
                                    @click.stop="previewUrl='{{ route('template.preview', $tpl->slug) }}'; showPreview=true"
                                    title="Preview {{ $tpl->name }}"
                                    class="absolute top-1.5 right-1.5 z-10 w-7 h-7 bg-white/90 hover:bg-white rounded-lg shadow flex items-center justify-center transition-all hover:scale-110 border border-gray-200/60">
                                <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn-luxury w-full" wire:loading.attr="disabled">
                    <span wire:loading.remove>Mulai Buat Undangan →</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Modal Konfirmasi Hapus --}}
    @if($confirmDeleteId)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="cancelDelete"></div>
        <div class="relative w-full max-w-sm card-luxury p-6 animate-slide-up text-center">
            {{-- Ikon --}}
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="font-serif text-xl text-gray-800 mb-1">Hapus Undangan?</h3>
            <p class="text-sm text-gray-500 mb-6">
                Undangan yang belum diaktifkan ini akan dihapus permanen beserta semua datanya. Tindakan ini tidak bisa dibatalkan.
            </p>
            <div class="flex gap-3">
                <button wire:click="cancelDelete"
                        class="btn-luxury-outline flex-1 text-sm py-2.5">
                    Batal
                </button>
                <button wire:click="deleteInvitation"
                        wire:loading.attr="disabled"
                        class="flex-1 text-sm py-2.5 px-4 rounded-xl font-semibold
                               bg-red-500 hover:bg-red-600 text-white transition-all
                               disabled:opacity-60">
                    <span wire:loading.remove wire:target="deleteInvitation">Hapus Sekarang</span>
                    <span wire:loading wire:target="deleteInvitation">Menghapus...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
