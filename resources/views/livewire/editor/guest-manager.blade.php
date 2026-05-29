<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h3 class="font-serif text-base text-gray-700">Manajemen Tamu</h3>
        <button wire:click="$toggle('showForm')" class="text-xs btn-sand py-1.5 px-3">+ Tambah</button>
    </div>

    {{-- Flash --}}
    @if(session('message'))
    <div class="text-xs text-green-700 bg-green-50 border border-green-200 rounded-xl px-3 py-2">
        {{ session('message') }}
    </div>
    @endif
    @if(session('error'))
    <div class="text-xs text-red-600 bg-red-50 border border-red-200 rounded-xl px-3 py-2">
        {{ session('error') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-2 text-center">
        <div class="bg-cream/60 rounded-xl p-2">
            <div class="text-lg font-bold text-forest">{{ $stats['total'] }}</div>
            <div class="text-xs text-gray-500">Tamu</div>
        </div>
        <div class="bg-cream/60 rounded-xl p-2">
            <div class="text-lg font-bold text-forest">{{ $stats['rsvped'] }}</div>
            <div class="text-xs text-gray-500">RSVP</div>
        </div>
        <div class="bg-cream/60 rounded-xl p-2">
            <div class="text-lg font-bold text-forest">{{ $stats['checked_in'] }}</div>
            <div class="text-xs text-gray-500">Check-in</div>
        </div>
    </div>

    {{-- Add Form --}}
    @if($showForm)
    <div class="card-luxury p-4 space-y-3 border-2 border-forest/20">
        <input type="text" wire:model="newName" class="input-luxury" placeholder="Nama tamu *">
        @error('newName') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        <input type="text" wire:model="newPhone" class="input-luxury" placeholder="No. HP / WA (opsional)">
        <div>
            <label class="label-luxury">Jumlah Kursi</label>
            <input type="number" wire:model="newSeats" min="1" max="20" class="input-luxury">
        </div>
        <textarea wire:model="newNotes" rows="2" class="input-luxury resize-none" placeholder="Catatan (opsional)"></textarea>
        <div class="flex gap-2">
            <button wire:click="addGuest" class="btn-luxury flex-1 text-xs py-2.5">Simpan</button>
            <button wire:click="$toggle('showForm')" class="btn-sand text-xs py-2.5 px-4">Batal</button>
        </div>
    </div>
    @endif

    {{-- ── Import Section ─────────────────────────── --}}
    <div class="rounded-xl border border-cream-dark/40 overflow-hidden">
        <div class="flex divide-x divide-cream-dark/40">
            {{-- Tab: Via Teks --}}
            <button wire:click="toggleTextImport"
                    class="flex-1 text-xs py-2 font-medium transition-colors
                           {{ $showImport ? 'bg-forest text-white' : 'bg-cream/40 text-gray-500 hover:text-forest' }}">
                Via Teks
            </button>
            {{-- Tab: Via Excel --}}
            <button wire:click="toggleExcelImport"
                    class="flex-1 text-xs py-2 font-medium transition-colors
                           {{ $showExcelImport ? 'bg-forest text-white' : 'bg-cream/40 text-gray-500 hover:text-forest' }}">
                Via Excel / CSV
            </button>
        </div>

        {{-- Panel: Via Teks --}}
        @if($showImport)
        <div class="p-3 space-y-2 bg-white/60">
            <p class="text-[10px] text-gray-400">Format: <code>Nama, Nomor HP</code> — satu tamu per baris</p>
            <textarea wire:model="importText" rows="5" class="input-luxury resize-none text-xs"
                      placeholder="Bapak Andi, 08123456789&#10;Ibu Sari&#10;Bapak Budi, 08987654321"></textarea>
            <div class="flex gap-2">
                <button wire:click="bulkImport" class="btn-luxury text-xs py-2.5 flex-1">Import</button>
                <button wire:click="toggleTextImport" class="btn-sand text-xs py-2.5 px-3">Batal</button>
            </div>
        </div>
        @endif

        {{-- Panel: Via Excel --}}
        @if($showExcelImport)
        <div class="p-3 space-y-3 bg-white/60">

            {{-- Download template --}}
            <div class="flex items-center gap-2 p-2.5 rounded-lg bg-cream/60 border border-cream-dark/40">
                <svg class="w-8 h-8 text-green-600 flex-shrink-0" viewBox="0 0 40 40" fill="none">
                    <rect width="40" height="40" rx="8" fill="#16a34a" opacity=".1"/>
                    <path d="M10 10h12l8 8v12H10V10z" fill="#16a34a" opacity=".3"/>
                    <path d="M22 10v8h8" fill="none" stroke="#16a34a" stroke-width="1.5"/>
                    <path d="M15 22l3 4 3-4M18 26v-6" stroke="#16a34a" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-700">Download Template</p>
                    <p class="text-[10px] text-gray-400">Isi template lalu upload kembali</p>
                </div>
                <button wire:click="downloadTemplate"
                        class="text-xs text-green-700 font-semibold hover:text-green-900 border border-green-200 rounded-lg px-3 py-1.5 bg-green-50 hover:bg-green-100 transition-colors flex-shrink-0">
                    ↓ Download
                </button>
            </div>

            {{-- Upload file --}}
            <div>
                <label class="label-luxury">Upload File CSV / Excel</label>
                <input type="file" wire:model="importFile" accept=".xlsx,.xls,.csv"
                       class="block w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-3
                              file:rounded-lg file:border-0 file:text-xs file:font-medium
                              file:bg-forest/10 file:text-forest hover:file:bg-forest/20
                              border border-cream-dark/50 rounded-xl px-3 py-2 bg-white/60">
                @error('importFile') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-[10px] text-gray-400 mt-1">Format: .xlsx atau .xls · Kolom: Nama, Nomor HP, Jumlah Kursi, Catatan</p>
            </div>

            <div class="flex gap-2">
                <button wire:click="importFromFile"
                        wire:loading.attr="disabled"
                        class="btn-luxury text-xs py-2.5 flex-1 disabled:opacity-50">
                    <span wire:loading.remove wire:target="importFromFile">Import Tamu</span>
                    <span wire:loading wire:target="importFromFile">Memproses...</span>
                </button>
                <button wire:click="toggleExcelImport" class="btn-sand text-xs py-2.5 px-3">Batal</button>
            </div>
        </div>
        @endif
    </div>

    {{-- Search --}}
    <input type="text" wire:model.live.debounce.300ms="search" class="input-luxury" placeholder="Cari tamu...">

    {{-- Guest List --}}
    <div class="space-y-2">
        @forelse($guests as $guest)
        <div class="flex items-center gap-2 p-3 bg-white rounded-xl border border-cream-dark/40">
            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ $guest->name }}</p>
                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                    @if($guest->checked_in_at)
                    <span class="text-xs text-green-600">✓ Check-in</span>
                    @elseif($guest->attendance)
                    <span class="text-xs {{ $guest->attendance === 'hadir' ? 'text-blue-600' : ($guest->attendance === 'tidak_hadir' ? 'text-red-400' : 'text-gray-400') }}">
                        {{ ['hadir' => '✓ Hadir', 'tidak_hadir' => '✗ Tidak Hadir', 'mungkin' => '? Mungkin'][$guest->attendance] ?? $guest->attendance }}
                    </span>
                    @else
                    <span class="text-xs text-gray-400">Belum RSVP</span>
                    @endif

                    @if($guest->phone)
                    <span class="text-[10px] text-gray-300">·</span>
                    <span class="text-[10px] text-gray-400 truncate max-w-[80px]">{{ $guest->phone }}</span>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-0.5 flex-shrink-0">
                {{-- WhatsApp (hanya kalau ada no HP) --}}
                @if($guest->phone)
                <a href="{{ $this->whatsappUrl($guest->phone, $guest->name, $guest->slug) }}"
                   target="_blank" rel="noopener"
                   title="Kirim undangan via WhatsApp"
                   class="p-1.5 rounded-lg text-gray-300 hover:text-green-600 hover:bg-green-50 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                </a>
                @else
                {{-- Placeholder agar layout konsisten --}}
                <span class="p-1.5 w-7"></span>
                @endif

                {{-- Link personal --}}
                <a href="{{ url('/' . $invitation->slug . '?tamu=' . $guest->slug) }}" target="_blank"
                   title="Buka link undangan personal"
                   class="p-1.5 rounded-lg text-gray-300 hover:text-forest hover:bg-forest/5 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>

                {{-- Hapus --}}
                <button wire:click="deleteGuest({{ $guest->id }})"
                        wire:confirm="Hapus tamu {{ $guest->name }}?"
                        title="Hapus tamu"
                        class="p-1.5 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @empty
        <p class="text-xs text-center text-gray-400 py-6">Belum ada tamu ditambahkan</p>
        @endforelse
    </div>

    {{ $guests->links() }}

    {{-- Export CSV --}}
    @if($stats['total'] > 0 && $this->invitation->package?->has_rsvp_export)
    <button wire:click="exportCsv" class="btn-sand w-full text-xs py-2.5">
        Export CSV
    </button>
    @endif

</div>
