@extends('layouts.admin')
@section('page-title', 'Manajemen Transaksi')
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex items-center gap-3">
        <form method="GET" class="flex items-center gap-3 flex-1">
            <input type="text" name="search" value="{{ $search }}" class="input-luxury w-64" placeholder="Cari order ID / nama...">
            <select name="status" class="input-luxury w-40">
                <option value="">Semua Status</option>
                @foreach(['pending','paid','failed','expired'] as $s)
                <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="btn-sand py-2.5 px-4 text-sm">Filter</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Order ID</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">User</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Paket</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Jumlah</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($transactions as $trx)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-mono text-xs">{{ $trx->order_id }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $trx->user_name }}</p>
                        <p class="text-xs text-gray-400">{{ $trx->user_email }}</p>
                    </td>
                    <td class="px-6 py-4">{{ $trx->package_name }}</td>
                    <td class="px-6 py-4 font-medium text-forest">Rp {{ number_format($trx->gross_amount, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="{{ $trx->status === 'paid' ? 'badge-active' : ($trx->status === 'pending' ? 'badge-pending' : 'badge-expired') }}">
                            {{ ucfirst($trx->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-gray-100">{{ $transactions->links() }}</div>
</div>
@endsection
