@extends('layouts.admin')
@section('page-title', 'Dashboard')
@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @php
    $cards = [
        ['label'=>'Total User', 'value'=> number_format($stats['totalUsers']), 'icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
        ['label'=>'Undangan Aktif', 'value'=>number_format($stats['totalInvitations']), 'icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
        ['label'=>'Total Revenue', 'value'=>'Rp '.number_format($stats['totalRevenue'],0,',','.'), 'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Revenue Bulan Ini', 'value'=>'Rp '.number_format($stats['monthlyRevenue'],0,',','.'), 'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
            <div class="w-10 h-10 bg-forest/10 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $card['icon'] }}"/></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Template popularity --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-4">Template Terpopuler</h3>
        <div class="space-y-3">
            @foreach($stats['templatePopularity'] as $tpl)
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-700 flex-1">{{ $tpl->name }}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-2">
                    @php $max = $stats['templatePopularity']->max('count'); @endphp
                    <div class="h-2 bg-forest rounded-full" style="width: {{ $max > 0 ? round(($tpl->count / $max) * 100) : 0 }}%"></div>
                </div>
                <span class="text-sm font-medium text-gray-500 w-8 text-right">{{ $tpl->count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Package popularity --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-800 mb-4">Penjualan per Paket</h3>
        <div class="space-y-3">
            @foreach($stats['packagePopularity'] as $pkg)
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-700">{{ $pkg->name }}</span>
                <span class="badge-active">{{ $pkg->count }} transaksi</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
