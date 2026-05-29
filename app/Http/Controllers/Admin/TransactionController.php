<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,failed,expired',
        ]);

        $newStatus = $request->status;
        $oldStatus = $transaction->status;

        DB::transaction(function () use ($transaction, $newStatus, $oldStatus) {
            $transaction->update([
                'status'  => $newStatus,
                'paid_at' => $newStatus === 'paid' ? now() : $transaction->paid_at,
            ]);

            if ($newStatus === 'paid' && $oldStatus !== 'paid') {
                $this->activateInvitation($transaction);
                Log::info('Admin manually marked transaction as paid', ['order_id' => $transaction->order_id]);
            }

            if ($newStatus !== 'paid' && $oldStatus === 'paid' && $transaction->invitation_id) {
                DB::table('invitations')
                    ->where('id', $transaction->invitation_id)
                    ->where('transaction_id', $transaction->id)
                    ->update(['is_active' => false, 'updated_at' => now()]);
            }
        });

        return back()->with('success', "Status transaksi #{$transaction->order_id} diubah ke {$newStatus}.");
    }

    private function activateInvitation(Transaction $transaction): void
    {
        if (!$transaction->invitation_id) return;

        $package   = $transaction->package;
        $expiresAt = now()->addDays((int) $package->duration_days);

        DB::table('invitations')
            ->where('id', $transaction->invitation_id)
            ->update([
                'is_active'      => true,
                'package_id'     => $transaction->package_id,
                'transaction_id' => $transaction->id,
                'activated_at'   => now(),
                'expires_at'     => $expiresAt,
                'updated_at'     => now(),
            ]);
    }
}
