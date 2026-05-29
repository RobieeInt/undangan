<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $transactions = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('packages', 'transactions.package_id', '=', 'packages.id')
            ->select(
                'transactions.*',
                'users.name as user_name',
                'users.email as user_email',
                'packages.name as package_name'
            )
            ->when($search, fn($q) => $q->where('transactions.order_id', 'like', "%$search%")
                ->orWhere('users.name', 'like', "%$search%"))
            ->when($status, fn($q) => $q->where('transactions.status', $status))
            ->orderByDesc('transactions.created_at')
            ->paginate(20);

        return view('admin.transactions.index', compact('transactions', 'search', 'status'));
    }
}
