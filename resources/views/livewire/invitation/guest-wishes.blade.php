<div>
    <div class="space-y-4">
        @forelse($wishes as $wish)
        <div class="p-4 rounded-2xl bg-white/60 backdrop-blur-sm border border-white/40" data-aos="fade-up">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                         style="background: linear-gradient(135deg, #306D29, #0D530E)">
                        {{ substr($wish->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $wish->name }}</p>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($wish->created_at)->diffForHumans() }}</p>
                    </div>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full
                      {{ $wish->attendance === 'hadir' ? 'bg-green-50 text-green-700' : 'bg-gray-50 text-gray-500' }}">
                    {{ $wish->attendance === 'hadir' ? 'Hadir' : ($wish->attendance === 'tidak_hadir' ? 'Tidak Hadir' : 'Mungkin') }}
                </span>
            </div>
            @if($wish->message)
            <p class="text-sm text-gray-600 leading-relaxed pl-10">{{ $wish->message }}</p>
            @endif
        </div>
        @empty
        <div class="text-center py-8 text-gray-400">
            <p class="text-sm">Belum ada ucapan. Jadilah yang pertama!</p>
        </div>
        @endforelse
    </div>

    @if($hasMore)
    <button wire:click="loadMore" wire:loading.attr="disabled"
            class="w-full mt-6 py-3 text-sm border-2 border-forest/20 text-forest/60 rounded-xl hover:border-forest/40 transition-all">
        <span wire:loading.remove>Lihat lebih banyak ({{ $total - count($wishes) }} lagi)</span>
        <span wire:loading>Memuat...</span>
    </button>
    @endif

    {{-- Listen for new RSVP from sibling component --}}
    <script>
        document.addEventListener('livewire:dispatched', e => {
            if (e.detail.name === 'rsvp-submitted') {
                @this.refreshWishes();
            }
        });
    </script>
</div>
