<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
        $cards = [
            ['label'=>'Total User',      'value'=> number_format($stats['totalUsers']),                               'color'=>'text-blue-600',  'bg'=>'bg-blue-50'],
            ['label'=>'Undangan Aktif',  'value'=> number_format($stats['totalInvitations']),                         'color'=>'text-forest',    'bg'=>'bg-green-50'],
            ['label'=>'Total Revenue',   'value'=>'Rp '.number_format($stats['totalRevenue'],0,',','.'),              'color'=>'text-emerald',   'bg'=>'bg-emerald-50'],
            ['label'=>'Revenue Bulan Ini','value'=>'Rp '.number_format($stats['monthlyRevenue'],0,',','.'),           'color'=>'text-purple-600','bg'=>'bg-purple-50'],
        ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">{{ $card['label'] }}</p>
            <p class="text-2xl font-bold {{ $card['color'] }}">{{ $card['value'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-4">Template Terpopuler</h3>
            <div class="space-y-3">
                @foreach($stats['templatePopularity'] as $tpl)
                @php $max = $stats['templatePopularity']->max('count'); @endphp
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-700 flex-1">{{ $tpl->name }}</span>
                    <div class="flex-1 bg-gray-100 rounded-full h-2">
                        <div class="h-2 bg-forest rounded-full transition-all" style="width: {{ $max > 0 ? round(($tpl->count / $max) * 100) : 0 }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-500 w-6 text-right">{{ $tpl->count }}</span>
                </div>
                @endforeach
                @if($stats['templatePopularity']->isEmpty())
                <p class="text-sm text-gray-400">Belum ada data</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-800 mb-4">Penjualan per Paket</h3>
            <div class="space-y-3">
                @foreach($stats['packagePopularity'] as $pkg)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">{{ $pkg->name }}</span>
                    <span class="badge-active">{{ $pkg->count }} transaksi</span>
                </div>
                @endforeach
                @if($stats['packagePopularity']->isEmpty())
                <p class="text-sm text-gray-400">Belum ada transaksi</p>
                @endif
            </div>
        </div>
    </div>
</div>
