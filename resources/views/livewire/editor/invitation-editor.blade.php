<div class="editor-layout">

{{-- TOP BAR ──────────────────────────────────────────────────────────── --}}
<header class="flex-shrink-0 h-14 bg-white border-b border-cream-dark/40 flex items-center justify-between px-4 z-40 shadow-sm">
    <div class="flex items-center gap-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-gray-500 hover:text-forest transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span class="text-xs hidden sm:block">Dashboard</span>
        </a>
        <div class="h-4 w-px bg-cream-dark/60"></div>
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 bg-gradient-forest rounded-md flex items-center justify-center">
                <svg class="w-3 h-3 text-cream" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
            </div>
            <span class="text-sm font-medium text-gray-700">{{ $invitation->getCoupleName() ?: 'Editor Undangan' }}</span>
        </div>
    </div>

    <div class="flex items-center gap-2">
        {{-- Auto-save status --}}
        <span class="text-xs text-gray-400 hidden sm:block">
            {{ $autoSaveStatus ?: 'Siap' }}
        </span>

        {{-- Preview --}}
        <a href="{{ route('invitation.preview', $invitation->id) }}" target="_blank"
           class="btn-sand text-xs py-2 px-3 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Preview
        </a>

        {{-- Publish --}}
        @if($invitation->is_published)
        <button wire:click="unpublish" class="btn-sand text-xs py-2 px-3 bg-red-50 text-red-600 hover:bg-red-100">
            Sembunyikan
        </button>
        @else
        <button wire:click="publish" class="btn-luxury text-xs py-2 px-4 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Publikasikan
        </button>
        @endif
    </div>
</header>

<div class="editor-body overflow-hidden">

    {{-- LEFT SIDEBAR ──────────────────────────────────────────────── --}}
    <aside class="editor-sidebar flex flex-col">

        {{-- Tab Navigation --}}
        <div class="p-3 border-b border-cream-dark/30 bg-cream/40">
            <div class="grid grid-cols-4 gap-1">
                @php
                $tabs = [
                    ['id'=>'informasi', 'icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label'=>'Info'],
                    ['id'=>'event',     'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label'=>'Event'],
                    ['id'=>'gallery',   'icon'=>'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'label'=>'Foto'],
                    ['id'=>'theme',     'icon'=>'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01', 'label'=>'Tema'],
                ];
                $tabs2 = [
                    ['id'=>'music',  'icon'=>'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3', 'label'=>'Musik'],
                    ['id'=>'gift',   'icon'=>'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7', 'label'=>'Hadiah'],
                    ['id'=>'rsvp',   'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label'=>'RSVP'],
                    ['id'=>'tamu',   'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label'=>'Tamu'],
                ];
                @endphp

                @foreach([...$tabs, ...$tabs2] as $tab)
                <button wire:click="$set('activeTab', '{{ $tab['id'] }}')"
                        class="tab-btn {{ $activeTab === $tab['id'] ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $tab['icon'] }}"/>
                    </svg>
                    <span>{{ $tab['label'] }}</span>
                </button>
                @endforeach
            </div>
        </div>

        {{-- Tab Content ────────────────────────────────── --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-4">

        {{-- ── INFORMASI ─────────────────────────── --}}
        @if($activeTab === 'informasi')
        <div class="space-y-4" wire:key="tab-informasi">
            <h3 class="font-serif text-base text-gray-700">Informasi Pengantin</h3>

            {{-- Cover Photo --}}
            <div>
                <label class="label-luxury">Foto Cover</label>
                <div x-data="photoUpload('cover_photo', 'uploadCoverPhoto')"
                     class="border-2 border-dashed border-cream-dark/60 rounded-xl p-4 text-center bg-cream/30 hover:border-forest/40 transition-colors">
                    {{-- Preview: swap langsung saat file dipilih --}}
                    <img :src="previewUrl || '{{ $invitation->cover_photo_url }}'"
                         x-show="previewUrl{{ $invitation->cover_photo ? ' || true' : '' }}"
                         class="w-full h-28 object-cover rounded-lg mb-2"
                         :class="{'opacity-50': loading}">

                    <input type="file" accept="image/*" x-ref="inp" @change="pick($event)" class="hidden">

                    <button type="button" @click="$refs.inp.click()" :disabled="loading"
                            class="text-xs text-forest hover:text-emerald font-medium cursor-pointer disabled:opacity-40">
                        <span x-show="!loading">{{ $invitation->cover_photo ? 'Ganti foto' : 'Upload foto cover' }}</span>
                        <span x-show="loading" x-text="statusMsg" class="text-amber-600 font-semibold"></span>
                    </button>

                    {{-- Progress bar --}}
                    <div x-show="loading" class="mt-2 w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-forest h-1.5 rounded-full transition-all duration-300" :style="'width:'+progress+'%'"></div>
                    </div>
                </div>
                @error('cover_photo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Groom & Bride Photos --}}
            <div class="grid grid-cols-2 gap-3">
                {{-- Groom --}}
                <div>
                    <label class="label-luxury">Foto Pengantin Pria</label>
                    <div x-data="photoUpload('groom_photo', 'uploadGroomPhoto')"
                         class="border-2 border-dashed border-cream-dark/60 rounded-xl p-3 text-center bg-cream/30">
                        <img :src="previewUrl || '{{ $invitation->groom_photo_url }}'"
                             x-show="previewUrl{{ $invitation->groom_photo ? ' || true' : '' }}"
                             class="w-16 h-16 object-cover rounded-full mx-auto mb-2"
                             :class="{'opacity-40': loading}">
                        <input type="file" accept="image/*" x-ref="inp" @change="pick($event)" class="hidden">
                        <button type="button" @click="$refs.inp.click()" :disabled="loading"
                                class="text-xs text-forest cursor-pointer disabled:opacity-40">
                            <span x-show="!loading">Upload</span>
                            <span x-show="loading" x-text="statusMsg" class="text-amber-600 text-[10px]"></span>
                        </button>
                        <div x-show="loading" class="mt-1.5 w-full bg-gray-100 rounded-full h-1 overflow-hidden">
                            <div class="bg-forest h-1 rounded-full transition-all" :style="'width:'+progress+'%'"></div>
                        </div>
                    </div>
                </div>

                {{-- Bride --}}
                <div>
                    <label class="label-luxury">Foto Pengantin Wanita</label>
                    <div x-data="photoUpload('bride_photo', 'uploadBridePhoto')"
                         class="border-2 border-dashed border-cream-dark/60 rounded-xl p-3 text-center bg-cream/30">
                        <img :src="previewUrl || '{{ $invitation->bride_photo_url }}'"
                             x-show="previewUrl{{ $invitation->bride_photo ? ' || true' : '' }}"
                             class="w-16 h-16 object-cover rounded-full mx-auto mb-2"
                             :class="{'opacity-40': loading}">
                        <input type="file" accept="image/*" x-ref="inp" @change="pick($event)" class="hidden">
                        <button type="button" @click="$refs.inp.click()" :disabled="loading"
                                class="text-xs text-forest cursor-pointer disabled:opacity-40">
                            <span x-show="!loading">Upload</span>
                            <span x-show="loading" x-text="statusMsg" class="text-amber-600 text-[10px]"></span>
                        </button>
                        <div x-show="loading" class="mt-1.5 w-full bg-gray-100 rounded-full h-1 overflow-hidden">
                            <div class="bg-forest h-1 rounded-full transition-all" :style="'width:'+progress+'%'"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Names --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label-luxury">Nama Pria <span class="text-red-400">*</span></label>
                    <input type="text" wire:model.live.debounce.800ms="groom_name" class="input-luxury" placeholder="Nama singkat">
                    @error('groom_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label-luxury">Nama Wanita <span class="text-red-400">*</span></label>
                    <input type="text" wire:model.live.debounce.800ms="bride_name" class="input-luxury" placeholder="Nama singkat">
                    @error('bride_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="label-luxury">Nama Lengkap Pengantin Pria</label>
                <input type="text" wire:model.live.debounce.800ms="groom_full_name" class="input-luxury" placeholder="Contoh: Yujang Lesmana, S.Kom">
            </div>
            <div>
                <label class="label-luxury">Nama Lengkap Pengantin Wanita</label>
                <input type="text" wire:model.live.debounce.800ms="bride_full_name" class="input-luxury" placeholder="Contoh: Bella , S.E">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="label-luxury">Nama Ayah Pria</label>
                    <input type="text" wire:model.live.debounce.800ms="groom_father" class="input-luxury" placeholder="Nama ayah">
                </div>
                <div>
                    <label class="label-luxury">Nama Ibu Pria</label>
                    <input type="text" wire:model.live.debounce.800ms="groom_mother" class="input-luxury" placeholder="Nama ibu">
                </div>
                <div>
                    <label class="label-luxury">Nama Ayah Wanita</label>
                    <input type="text" wire:model.live.debounce.800ms="bride_father" class="input-luxury" placeholder="Nama ayah">
                </div>
                <div>
                    <label class="label-luxury">Nama Ibu Wanita</label>
                    <input type="text" wire:model.live.debounce.800ms="bride_mother" class="input-luxury" placeholder="Nama ibu">
                </div>
            </div>

            {{-- Kutipan Pembuka + Preset Picker --}}
            <div x-data="{ showQuotes: false }" class="space-y-3">

                <div>
                    <label class="label-luxury">Kutipan Pembuka</label>
                    <textarea wire:model.live.debounce.800ms="opening_quote" rows="3"
                              class="input-luxury resize-none text-sm" placeholder="QS. Ar-Rum: 21 — Dan di antara tanda-tanda kekuasaan-Nya…"></textarea>
                </div>
                <div>
                    <label class="label-luxury">Sumber Kutipan</label>
                    <input type="text" wire:model.live.debounce.800ms="opening_quote_source" class="input-luxury" placeholder="Sumber ayat / kutipan">
                </div>

                {{-- Toggle button --}}
                <button type="button" @click="showQuotes = !showQuotes"
                        class="flex items-center gap-1.5 text-xs font-medium transition-colors"
                        :class="showQuotes ? 'text-forest' : 'text-gray-400 hover:text-forest'">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <span x-text="showQuotes ? 'Sembunyikan saran kutipan' : 'Pilih dari kutipan populer'"></span>
                    <svg class="w-3 h-3 transition-transform duration-200" :class="showQuotes ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Quote picker panel --}}
                <div x-show="showQuotes"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="rounded-xl border border-cream-dark/50 bg-cream/60 overflow-hidden"
                     style="display:none">

                    @php
                    $presetQuotes = [
                        /* ── Al-Qur'an ── */
                        [
                            'tag' => 'Al-Qur\'an',
                            'q'   => 'Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu istri-istri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya, dan dijadikan-Nya di antaramu rasa kasih dan sayang.',
                            's'   => 'QS. Ar-Rum: 21',
                        ],
                        [
                            'tag' => 'Al-Qur\'an',
                            'q'   => 'Mereka adalah pakaian bagimu, dan kamu adalah pakaian bagi mereka.',
                            's'   => 'QS. Al-Baqarah: 187',
                        ],
                        [
                            'tag' => 'Al-Qur\'an',
                            'q'   => 'Ya Tuhan kami, anugerahkanlah kepada kami pasangan kami dan keturunan kami sebagai penyenang hati kami, dan jadikanlah kami pemimpin bagi orang-orang yang bertakwa.',
                            's'   => 'QS. Al-Furqan: 74',
                        ],
                        [
                            'tag' => 'Al-Qur\'an',
                            'q'   => 'Dan Allah menjadikan bagimu pasangan dari jenis kamu sendiri dan menjadikan anak dan cucu bagimu dari pasanganmu, serta memberimu rezeki dari yang baik-baik.',
                            's'   => 'QS. An-Nahl: 72',
                        ],
                        [
                            'tag' => 'Al-Qur\'an',
                            'q'   => 'Dan Kami menciptakan kamu berpasang-pasangan.',
                            's'   => 'QS. An-Naba: 8',
                        ],
                        /* ── Hadits ── */
                        [
                            'tag' => 'Hadits',
                            'q'   => 'Nikah itu adalah sunnahku. Barangsiapa yang tidak mengamalkan sunnahku, maka ia bukan dari golonganku.',
                            's'   => 'HR. Ibnu Majah',
                        ],
                        [
                            'tag' => 'Hadits',
                            'q'   => 'Dunia adalah perhiasan, dan sebaik-baik perhiasan dunia adalah wanita shalihah.',
                            's'   => 'HR. Muslim',
                        ],
                        [
                            'tag' => 'Hadits',
                            'q'   => 'Apabila seorang hamba menikah, maka ia telah menyempurnakan separuh agamanya.',
                            's'   => 'HR. Al-Baihaqi',
                        ],
                        /* ── Sastra & Umum ── */
                        [
                            'tag' => 'Sastra',
                            'q'   => 'Engkau adalah rumah yang selalu ingin aku pulang, dan cintamu adalah tempat terindah untuk berdiam.',
                            's'   => 'Sapardi Djoko Damono',
                        ],
                        [
                            'tag' => 'Sastra',
                            'q'   => 'Kamu tumbuh di hatiku seperti musim semi yang tak pernah habis.',
                            's'   => 'Dee Lestari',
                        ],
                        [
                            'tag' => 'Sastra',
                            'q'   => 'Pernikahan adalah awal dari persahabatan, tumbuh menjadi cinta, mengakar menjadi keluarga, dan mekar menjadi keabadian.',
                            's'   => 'Kahlil Gibran',
                        ],
                        [
                            'tag' => 'Sastra',
                            'q'   => 'Cinta sejati bukan tentang menemukan orang yang sempurna, melainkan belajar mencintai orang yang tidak sempurna dengan cara yang sempurna.',
                            's'   => '',
                        ],
                        [
                            'tag' => 'Sastra',
                            'q'   => 'Engkaulah yang aku pilih kemarin, hari ini, dan setiap hari yang akan datang.',
                            's'   => '',
                        ],
                        [
                            'tag' => 'Sastra',
                            'q'   => 'Dua jiwa, satu hati. Dalam cinta kita menemukan arti, dalam pernikahan kita menemukan rumah.',
                            's'   => '',
                        ],
                    ];

                    $tagColors = [
                        'Al-Qur\'an' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'Hadits'     => 'bg-teal-50 text-teal-700 border-teal-200',
                        'Sastra'     => 'bg-rose-50 text-rose-600 border-rose-200',
                    ];
                    @endphp

                    <div class="p-2.5 border-b border-cream-dark/40 bg-white/60">
                        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wider">Klik kutipan untuk langsung mengisi form</p>
                    </div>

                    <div class="max-h-72 overflow-y-auto p-2 space-y-1.5">
                        @foreach($presetQuotes as $pq)
                        @php $tc = $tagColors[$pq['tag']] ?? 'bg-gray-50 text-gray-600 border-gray-200'; @endphp
                        <button type="button"
                                @click="$wire.set('opening_quote', @js($pq['q'])); $wire.set('opening_quote_source', @js($pq['s'])); showQuotes = false"
                                class="w-full text-left p-2.5 rounded-lg bg-white hover:bg-forest/5 border border-transparent hover:border-forest/20 transition-all group">
                            <div class="flex items-start gap-2">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-semibold border flex-shrink-0 mt-0.5 {{ $tc }}">
                                    {{ $pq['tag'] }}
                                </span>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-700 italic leading-relaxed line-clamp-2 group-hover:line-clamp-none transition-all">"{{ $pq['q'] }}"</p>
                                    @if($pq['s'])
                                    <p class="text-[10px] text-forest/70 font-medium mt-0.5">— {{ $pq['s'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div>
                <label class="label-luxury">Cerita Cinta</label>
                <textarea wire:model.live.debounce.800ms="story" rows="4" class="input-luxury resize-none" placeholder="Ceritakan perjalanan cinta Anda..."></textarea>
            </div>

            <button wire:click="saveInformation" class="btn-luxury w-full text-sm">
                Simpan Informasi
            </button>
        </div>

        {{-- ── EVENT ────────────────────────────── --}}
        @elseif($activeTab === 'event')
        <div class="space-y-4" wire:key="tab-event">
            <h3 class="font-serif text-base text-gray-700">Jadwal Acara</h3>

            {{-- Existing events --}}
            @foreach($events as $event)
            <div class="card-luxury p-4 space-y-1">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="font-medium text-sm text-gray-800">{{ $event['name'] }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($event['date'])->translatedFormat('d F Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $event['time_start'] }} {{ $event['time_end'] ? '– '.$event['time_end'] : '' }}</p>
                        <p class="text-xs text-forest/70 mt-1">{{ $event['venue'] }}</p>
                    </div>
                    <button wire:click="deleteEvent({{ $event['id'] }})" wire:confirm="Hapus event ini?"
                            class="text-red-400 hover:text-red-600 p-1 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @endforeach

            {{-- Add new event form --}}
            <div class="card-luxury p-4 space-y-3 border-2 border-dashed border-cream-dark/60">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Tambah Acara</p>
                <input type="text" wire:model="newEvent.name" class="input-luxury" placeholder="Nama acara (misal: Akad Nikah)">
                @error('newEvent.name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                <input type="date" wire:model="newEvent.date" class="input-luxury">
                @error('newEvent.date') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs text-gray-500 mb-1 block">Mulai</label>
                        <input type="time" wire:model="newEvent.time_start" class="input-luxury">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 mb-1 block">Selesai</label>
                        <input type="time" wire:model="newEvent.time_end" class="input-luxury">
                    </div>
                </div>
                <input type="text" wire:model="newEvent.venue" class="input-luxury" placeholder="Nama gedung / venue">
                @error('newEvent.venue') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                <textarea wire:model="newEvent.venue_address" rows="2" class="input-luxury resize-none" placeholder="Alamat lengkap"></textarea>
                <input type="url" wire:model="newEvent.venue_maps_url" class="input-luxury" placeholder="Link Google Maps (opsional)">
                <button wire:click="addEvent" class="btn-luxury w-full text-xs py-2.5">
                    + Tambah Acara
                </button>
            </div>
        </div>

        {{-- ── GALLERY ──────────────────────────── --}}
        @elseif($activeTab === 'gallery')
        <div class="space-y-4" wire:key="tab-gallery">
            <div class="flex items-center justify-between">
                <h3 class="font-serif text-base text-gray-700">Galeri Foto</h3>
                <span class="text-xs text-gray-400">{{ count($galleries) }}/{{ $this->packageLimits['max_gallery'] }}</span>
            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-3 gap-2">
                @foreach($galleries as $photo)
                <div class="relative group aspect-square rounded-xl overflow-hidden">
                    <img src="{{ Storage::url($photo['image']) }}" class="w-full h-full object-cover" alt="{{ $photo['caption'] ?? '' }}">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <button wire:click="deleteGallery({{ $photo['id'] }})" wire:confirm="Hapus foto ini?"
                                class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Upload Gallery (Bulk) --}}
            @if(count($galleries) < $this->packageLimits['max_gallery'])
            <div x-data="galleryBulkUpload()"
                 class="border-2 border-dashed border-cream-dark/60 rounded-xl p-4 bg-cream/30">

                <input type="file" accept="image/*" multiple x-ref="inp" @change="pick($event)" class="hidden">

                {{-- Idle state --}}
                <div x-show="!loading" @click="$refs.inp.click()"
                     class="flex flex-col items-center gap-2 cursor-pointer text-center">
                    <svg class="w-8 h-8 text-forest/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="text-xs font-medium text-forest/70">Pilih Foto (bisa banyak sekaligus)</span>
                    <span class="text-[10px] text-gray-400">JPG, PNG, WEBP — dikompres otomatis ke WebP</span>
                    <span x-show="statusMsg" x-text="statusMsg" class="text-xs text-emerald-600 font-semibold mt-1"></span>
                </div>

                {{-- Uploading state --}}
                <div x-show="loading" class="text-center py-1">
                    <p class="text-sm font-semibold text-forest mb-2">
                        Mengupload <span x-text="statusMsg" class="text-amber-600"></span>
                    </p>
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-forest h-2 rounded-full transition-all duration-500"
                             :style="'width:'+progress+'%'"></div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1.5">Jangan tutup halaman ini...</p>
                </div>

                @error('galleryUpload') <p class="text-xs text-red-500 mt-2 text-center">{{ $message }}</p> @enderror
            </div>
            @else
            <p class="text-xs text-center text-gray-400 p-4 bg-gray-50 rounded-xl">Batas maksimal foto tercapai ({{ $this->packageLimits['max_gallery'] }}). Upgrade paket untuk lebih banyak foto.</p>
            @endif
        </div>

        {{-- ── THEME ────────────────────────────── --}}
        @elseif($activeTab === 'theme')
        <div class="space-y-4" wire:key="tab-theme">
            <h3 class="font-serif text-base text-gray-700">Tema & Gaya</h3>
            <p class="text-xs text-gray-500">Template aktif: <strong>{{ $invitation->template->name }}</strong></p>
            <p class="text-xs text-amber-600 bg-amber-50 p-3 rounded-xl">Untuk mengganti template, silakan buat undangan baru.</p>
        </div>

        {{-- ── MUSIC ────────────────────────────── --}}
        @elseif($activeTab === 'music')
        <div class="space-y-4" wire:key="tab-music">
            <h3 class="font-serif text-base text-gray-700">Musik Latar</h3>

            <div>
                <label class="label-luxury">URL Musik</label>
                <input type="text"
                       wire:model.live.debounce.600ms="music_url"
                       class="input-luxury"
                       placeholder="https://youtube.com/watch?v=... atau ...mp3"
                       @paste="
                           setTimeout(() => {
                               const val = $el.value.trim();
                               if (!val) return;
                               const isYt = val.includes('youtube.com') || val.includes('youtu.be');
                               if (isYt) {
                                   window.dispatchEvent(new CustomEvent('show-toast', {
                                       detail: { msg: '🎵 YouTube URL terdeteksi — klik Simpan Musik untuk menyimpan', type: 'success' }
                                   }));
                               } else if (val.startsWith('http')) {
                                   window.dispatchEvent(new CustomEvent('show-toast', {
                                       detail: { msg: '🎵 URL audio terdeteksi — klik Simpan Musik untuk menyimpan', type: 'success' }
                                   }));
                               }
                           }, 100)
                       ">
                {{-- YouTube thumbnail preview --}}
                @php
                    $ytId = null;
                    if ($music_url && (str_contains($music_url, 'youtube.com') || str_contains($music_url, 'youtu.be'))) {
                        preg_match('/(?:[?&]v=|youtu\.be\/|\/embed\/|\/shorts\/)([^?&#]+)/', $music_url, $m);
                        $ytId = $m[1] ?? null;
                    }
                @endphp
                @if($ytId)
                <div class="mt-2 rounded-xl overflow-hidden border border-cream-dark/40"
                     @if($youtubeEmbedStatus === 'checking')
                         wire:key="yt-checker-{{ $ytId }}"
                         x-data="ytEmbedChecker('{{ $ytId }}')"
                     @endif>
                    <img src="https://img.youtube.com/vi/{{ $ytId }}/mqdefault.jpg" class="w-full" alt="YouTube thumbnail">
                    @if($youtubeEmbedStatus === 'ok')
                        <p class="text-xs text-center text-forest bg-green-50 py-1.5">✓ Video dapat digunakan sebagai musik latar</p>
                    @elseif($youtubeEmbedStatus === 'blocked')
                        <p class="text-xs text-center text-red-600 bg-red-50 py-1.5 font-medium">⚠️ Video ini melarang embed — tidak bisa diputar di undangan. Ganti video!</p>
                    @elseif($youtubeEmbedStatus === 'checking')
                        <p class="text-xs text-center text-gray-400 bg-gray-50 py-1.5 animate-pulse">Memverifikasi apakah video bisa diputar...</p>
                    @else
                        <p class="text-xs text-center text-gray-500 bg-gray-50 py-1.5">YouTube terdeteksi — klik Simpan untuk verifikasi</p>
                    @endif
                </div>
                @elseif($music_url)
                <p class="text-xs text-gray-400 mt-1">Direct link MP3 / audio</p>
                @else
                <p class="text-xs text-gray-400 mt-1">Paste link YouTube <em>atau</em> direct link MP3</p>
                @endif
            </div>

            <div>
                <label class="label-luxury">Nama Lagu</label>
                <input type="text" wire:model.live="music_name" class="input-luxury" placeholder="Nama lagu (mis: Ed Sheeran – Perfect)">
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" wire:model.live="music_autoplay" class="rounded border-sand-dark text-forest focus:ring-forest/30 w-4 h-4">
                <span class="text-sm text-gray-700">Autoplay saat undangan dibuka</span>
            </label>

            <button wire:click="saveMusic" class="btn-luxury w-full text-sm">Simpan Musik</button>

            @if($music_url)
            <div class="text-xs text-gray-400 bg-gray-50 rounded-xl p-3 space-y-1">
                <p class="font-medium text-gray-500">Info:</p>
                @if($ytId)
                <p>• Musik diputar via YouTube embed tersembunyi</p>
                <p>• Tamu perlu klik tombol play di undangan</p>
                @else
                <p>• Pastikan link MP3 bisa diakses publik</p>
                <p>• Hosting rekomendasi: Google Drive (public), Dropbox, dll</p>
                @endif
            </div>
            @endif
        </div>

        {{-- ── GIFT ─────────────────────────────── --}}
        @elseif($activeTab === 'gift')
        <div class="space-y-4" wire:key="tab-gift">
            <h3 class="font-serif text-base text-gray-700">Hadiah Digital</h3>

            @foreach($gifts as $gift)
            <div class="card-luxury p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $gift['label'] ?: ($gift['bank_name'] ?: 'QRIS') }}</p>
                        <p class="text-xs text-gray-500">{{ $gift['account_number'] ?: strtoupper($gift['type']) }}</p>
                        <p class="text-xs text-gray-500">{{ $gift['account_name'] }}</p>
                    </div>
                    <button wire:click="deleteGift({{ $gift['id'] }})" wire:confirm="Hapus rekening ini?"
                            class="text-red-400 hover:text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @endforeach

            <div class="card-luxury p-4 space-y-3 border-2 border-dashed border-cream-dark/60">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Tambah Rekening / QRIS</p>
                <div>
                    <label class="label-luxury">Tipe</label>
                    <select wire:model.live="newGift.type" class="input-luxury">
                        <option value="bank">Transfer Bank</option>
                        <option value="qris">QRIS</option>
                        <option value="ewallet">E-Wallet</option>
                    </select>
                </div>
                <input type="text" wire:model="newGift.label" class="input-luxury" placeholder="Label (misal: BCA - Robby)">
                @if($newGift['type'] === 'bank' || $newGift['type'] === 'ewallet')
                <input type="text" wire:model="newGift.bank_name" class="input-luxury" placeholder="Nama Bank / E-Wallet">
                <input type="text" wire:model="newGift.account_number" class="input-luxury" placeholder="Nomor Rekening">
                <input type="text" wire:model="newGift.account_name" class="input-luxury" placeholder="Nama Pemilik Rekening">
                @else
                <div x-data="photoUploadOnly('qrisImage')"
                     class="border-2 border-dashed border-cream-dark/60 rounded-xl p-4 text-center">
                    <img :src="previewUrl{{ $qrisImage ? " || '".e($qrisImage->temporaryUrl())."'" : '' }}"
                         x-show="previewUrl{{ $qrisImage ? ' || true' : '' }}"
                         class="h-32 mx-auto mb-2 rounded-lg"
                         :class="{'opacity-50': loading}">
                    <input type="file" accept="image/*" x-ref="inp" @change="pick($event)" class="hidden">
                    <button type="button" @click="$refs.inp.click()" :disabled="loading"
                            class="text-xs text-forest cursor-pointer disabled:opacity-40">
                        <span x-show="!loading">Upload foto QRIS</span>
                        <span x-show="loading" x-text="statusMsg" class="text-amber-600"></span>
                    </button>
                    <div x-show="loading" class="mt-2 w-full bg-gray-100 rounded-full h-1 overflow-hidden">
                        <div class="bg-forest h-1 rounded-full transition-all" :style="'width:'+progress+'%'"></div>
                    </div>
                </div>
                @endif
                <button wire:click="addGift" class="btn-luxury w-full text-xs py-2.5">+ Tambah</button>
            </div>
        </div>

        {{-- ── RSVP ─────────────────────────────── --}}
        @elseif($activeTab === 'rsvp')
        <div class="space-y-4" wire:key="tab-rsvp">
            <h3 class="font-serif text-base text-gray-700">Pengaturan RSVP</h3>
            <div>
                <label class="label-luxury">Batas RSVP</label>
                <input type="date" wire:model.live="rsvp_deadline" class="input-luxury">
            </div>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" wire:model.live="is_open" class="rounded border-sand-dark text-forest focus:ring-forest/30 w-4 h-4">
                <span class="text-sm text-gray-700">RSVP terbuka</span>
            </label>
            <button wire:click="saveRsvpSettings" class="btn-luxury w-full text-sm">Simpan Pengaturan</button>

            {{-- RSVP stats --}}
            @php
            $rsvpStats = \Illuminate\Support\Facades\DB::table('invitation_rsvps')
                ->where('invitation_id', $invitation->id)
                ->selectRaw('attendance, COUNT(*) as count, SUM(guest_count) as total')
                ->groupBy('attendance')->get();
            @endphp
            <div class="pt-2 border-t border-cream-dark/30">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Statistik RSVP</p>
                @foreach($rsvpStats as $stat)
                <div class="flex items-center justify-between py-1.5">
                    <span class="text-sm text-gray-600">{{ ucfirst($stat->attendance) }}</span>
                    <span class="text-sm font-medium text-forest">{{ $stat->count }} tamu ({{ $stat->total }} orang)</span>
                </div>
                @endforeach
                @if($rsvpStats->isEmpty())
                <p class="text-xs text-center text-gray-400">Belum ada RSVP masuk</p>
                @endif
            </div>
        </div>

        {{-- ── TAMU ─────────────────────────────── --}}
        @elseif($activeTab === 'tamu')
        <div wire:key="tab-tamu">
            <livewire:editor.guest-manager :invitation="$invitation" />
        </div>
        @endif

        </div>{{-- end tab content --}}
    </aside>

    {{-- CENTER PREVIEW ──────────────────────────────────────────── --}}
    <div class="editor-preview hidden md:flex flex-col items-center">
        <div class="text-center mb-4 text-sm text-gray-400 font-medium tracking-wider uppercase text-xs">
            Live Preview
        </div>
        <div class="phone-frame">
            <div class="phone-screen overflow-y-auto">
                <iframe
                    src="{{ route('invitation.preview', $invitation->id) }}"
                    class="w-full h-full border-0"
                    id="preview-frame"
                    title="Preview Undangan">
                </iframe>
            </div>
        </div>
        <div class="mt-4 text-xs text-gray-400">
            URL: <a href="{{ url('/' . $invitation->slug) }}" target="_blank" class="text-forest hover:underline">{{ url('/' . $invitation->slug) }}</a>
        </div>
    </div>

</div>{{-- editor-body --}}
{{-- ── TOAST NOTIFICATIONS ─────────────────────────────────────── --}}
<div x-data="{
        toasts: [],
        add(msg, type = 'success', duration = 3500) {
            const id = Date.now();
            this.toasts.push({ id, msg, type });
            setTimeout(() => this.remove(id), duration);
        },
        remove(id) { this.toasts = this.toasts.filter(t => t.id !== id); }
     }"
     x-on:show-toast.window="add($event.detail.msg, $event.detail.type ?? 'success')"
     class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 pointer-events-none"
     style="max-width:320px">

    <template x-for="t in toasts" :key="t.id">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             :class="{
                'bg-white border-l-4 border-forest text-gray-700':   t.type === 'success',
                'bg-white border-l-4 border-yellow-400 text-gray-700': t.type === 'warning',
                'bg-white border-l-4 border-red-400 text-gray-700':  t.type === 'error',
             }"
             class="pointer-events-auto rounded-xl shadow-lg px-4 py-3 flex items-start gap-3 text-sm"
             style="min-width:220px">

            {{-- icon --}}
            <span x-show="t.type === 'success'" class="text-forest mt-0.5 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </span>
            <span x-show="t.type === 'warning'" class="text-yellow-500 mt-0.5 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            </span>
            <span x-show="t.type === 'error'" class="text-red-500 mt-0.5 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </span>

            <span x-text="t.msg" class="leading-snug"></span>
        </div>
    </template>
</div>

</div>{{-- editor-layout --}}

@push('scripts')
<script>
    // Reload preview iframe after Livewire saves
    document.addEventListener('livewire:dispatched', (e) => {
        if (['saved', 'published'].includes(e.detail.name)) {
            const frame = document.getElementById('preview-frame');
            if (frame) frame.contentWindow.location.reload();
        }
        // Toast for Livewire events
        if (e.detail.name === 'toast') {
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { msg: e.detail.params[0], type: e.detail.params[1] ?? 'success' }
            }));
        }
    });
</script>
@endpush
