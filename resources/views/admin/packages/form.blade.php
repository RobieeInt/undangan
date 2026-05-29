@extends('layouts.admin')
@section('page-title', $package->exists ? 'Edit Paket' : 'Tambah Paket')
@section('content')

@if($errors->any())
<div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm space-y-1">
    @foreach($errors->all() as $err)
    <p>{{ $err }}</p>
    @endforeach
</div>
@endif

<form method="POST"
      action="{{ $package->exists ? route('admin.packages.update', $package) : route('admin.packages.store') }}"
      class="max-w-3xl">
    @csrf
    @if($package->exists) @method('PUT') @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">

        {{-- Basic info --}}
        <div class="grid grid-cols-2 gap-6">
            <div class="col-span-2 sm:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Paket <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $package->name) }}"
                       class="input-luxury w-full" placeholder="misal: Premium" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Urutan Tampil <span class="text-red-500">*</span></label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $package->sort_order ?? 0) }}"
                       class="input-luxury w-full" min="0" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
            <textarea name="description" rows="2" class="input-luxury w-full"
                      placeholder="Deskripsi singkat paket...">{{ old('description', $package->description) }}</textarea>
        </div>

        {{-- Pricing --}}
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="price" value="{{ old('price', $package->price) }}"
                       class="input-luxury w-full" min="0" step="1000" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Durasi (hari) <span class="text-red-500">*</span></label>
                <input type="number" name="duration_days" value="{{ old('duration_days', $package->duration_days) }}"
                       class="input-luxury w-full" min="1" required>
                <p class="text-xs text-gray-400 mt-1">90 = 3bln, 365 = 1thn</p>
            </div>
        </div>

        {{-- Limits --}}
        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Maks. Tamu <span class="text-red-500">*</span></label>
                <input type="number" name="max_guests" value="{{ old('max_guests', $package->max_guests ?? 100) }}"
                       class="input-luxury w-full" min="1" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Maks. Galeri <span class="text-red-500">*</span></label>
                <input type="number" name="max_gallery" value="{{ old('max_gallery', $package->max_gallery ?? 10) }}"
                       class="input-luxury w-full" min="0" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Maks. Musik <span class="text-red-500">*</span></label>
                <input type="number" name="max_music" value="{{ old('max_music', $package->max_music ?? 1) }}"
                       class="input-luxury w-full" min="0" required>
            </div>
        </div>

        {{-- Features toggles --}}
        <div>
            <p class="text-sm font-medium text-gray-700 mb-3">Fitur</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @php
                $toggles = [
                    'has_watermark'      => ['Tampilkan Watermark', true],
                    'has_analytics'      => ['Analitik', false],
                    'has_rsvp_export'    => ['Export RSVP', false],
                    'has_qr_checkin'     => ['QR Check-in', false],
                    'has_all_templates'  => ['Semua Template', false],
                    'has_custom_domain'  => ['Custom Domain', false],
                ];
                @endphp
                @foreach($toggles as $field => [$label, $default])
                @php $checked = old($field) !== null ? (bool)old($field) : (bool)($package->{$field} ?? $default); @endphp
                <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="hidden" name="{{ $field }}" value="0">
                    <input type="checkbox" name="{{ $field }}" value="1" {{ $checked ? 'checked' : '' }}
                           class="w-4 h-4 accent-forest rounded">
                    <span class="text-sm text-gray-700">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Features list (display only) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Daftar Fitur (tampilan pricing)</label>
            <textarea name="features" rows="4" class="input-luxury w-full font-mono text-xs"
                      placeholder="Satu fitur per baris, akan ditampilkan di halaman pricing...">{{ old('features', is_array($package->features) ? implode("\n", $package->features) : $package->features) }}</textarea>
            <p class="text-xs text-gray-400 mt-1">Satu baris = satu bullet point di halaman pricing</p>
        </div>

        {{-- Status --}}
        <div>
            @php $isActive = old('is_active') !== null ? (bool)old('is_active') : (bool)($package->is_active ?? true); @endphp
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ $isActive ? 'checked' : '' }}
                       class="w-4 h-4 accent-forest rounded">
                <span class="text-sm font-medium text-gray-700">Paket Aktif</span>
            </label>
        </div>
    </div>

    <div class="flex items-center gap-3 mt-6">
        <button type="submit" class="btn-sand py-2.5 px-6 text-sm">
            {{ $package->exists ? 'Perbarui Paket' : 'Simpan Paket' }}
        </button>
        <a href="{{ route('admin.packages.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
            Batal
        </a>
    </div>
</form>

@endsection
