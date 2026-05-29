@extends('layouts.admin')
@section('page-title', 'Manajemen User')
@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-800">Daftar User</h3>
        <form method="GET">
            <input type="text" name="search" value="{{ $search }}" class="input-luxury w-64" placeholder="Cari nama/email...">
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">User</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Email</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Bergabung</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-forest flex items-center justify-center text-cream text-xs font-bold">{{ substr($user->name, 0, 1) }}</div>
                            <span class="font-medium text-gray-800">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="{{ $user->status === 'active' ? 'badge-active' : 'badge-expired' }}">{{ ucfirst($user->status) }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-xs">{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}">
                            @csrf
                            <button class="text-xs text-gray-500 hover:text-forest transition-colors">
                                {{ $user->status === 'active' ? 'Suspend' : 'Aktifkan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-gray-100">{{ $users->links() }}</div>
</div>
@endsection
