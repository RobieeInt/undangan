@extends('layouts.admin')
@section('page-title', 'Manajemen Paket')
@section('content')

@if(session('success'))
<div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
    {{ session('error') }}
</div>
@endif

<div class="flex justify-end mb-4">
    <a href="{{ route('admin.packages.create') }}" class="btn-sand py-2.5 px-5 text-sm inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Paket
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium w-8">#</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Nama</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Harga</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Durasi</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Fitur</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($packages as $pkg)
                <tr class="hover:bg-gray-50 transition-colors {{ !$pkg->is_active ? 'opacity-50' : '' }}">
                    <td class="px-6 py-4 text-gray-400">{{ $pkg->sort_order }}</td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-800">{{ $pkg->name }}</p>
                        @if($pkg->description)
                        <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($pkg->description, 60) }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-forest">
                        Rp {{ number_format($pkg->price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $pkg->duration_label }}</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @if($pkg->has_analytics)   <span class="badge-active text-[10px]">Analytics</span> @endif
                            @if($pkg->has_rsvp_export) <span class="badge-active text-[10px]">RSVP Export</span> @endif
                            @if($pkg->has_qr_checkin)  <span class="badge-active text-[10px]">QR Checkin</span> @endif
                            @if($pkg->has_all_templates) <span class="badge-active text-[10px]">All Templates</span> @endif
                            @if(!$pkg->has_watermark)  <span class="badge-active text-[10px]">No Watermark</span> @endif
                            @if($pkg->has_custom_domain) <span class="badge-active text-[10px]">Custom Domain</span> @endif
                            <span class="badge-pending text-[10px]">{{ $pkg->max_guests }} tamu</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="{{ $pkg->is_active ? 'badge-active' : 'badge-expired' }}">
                            {{ $pkg->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.packages.edit', $pkg) }}"
                               class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.packages.toggle-active', $pkg) }}">
                                @csrf
                                <button type="submit"
                                    class="text-xs {{ $pkg->is_active ? 'bg-amber-100 hover:bg-amber-200 text-amber-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} px-3 py-1.5 rounded-lg transition-colors">
                                    {{ $pkg->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.packages.destroy', $pkg) }}"
                                  onsubmit="return confirm('Hapus paket {{ $pkg->name }}? Aksi ini tidak bisa dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
