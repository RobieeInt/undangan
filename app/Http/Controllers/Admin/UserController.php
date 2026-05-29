<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $users  = DB::table('users')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%"))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.users.index', compact('users', 'search'));
    }

    public function toggleStatus(User $user)
    {
        if ($user->isAdmin()) abort(403);

        $user->update(['status' => $user->status === 'active' ? 'suspended' : 'active']);

        return back()->with('success', "Status user {$user->name} berhasil diubah.");
    }
}
