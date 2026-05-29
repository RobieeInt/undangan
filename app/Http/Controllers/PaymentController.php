<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Package;
use App\Models\Transaction;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    /**
     * Show package selection page.
     */
    public function selectPackage(Invitation $invitation)
    {
        $this->authorize('update', $invitation);

        $invitation->load('template');
        $template = $invitation->template;

        $packages = Package::active()->ordered()->get();

        // Template Premium/Exclusive hanya bisa dipakai dengan paket yang has_all_templates = true
        $needsAllTemplates = $template && ($template->is_premium || $template->is_exclusive);
        if ($needsAllTemplates) {
            $packages = $packages->filter(fn($p) => $p->has_all_templates);
        }

        return view('payment.select-package', compact('invitation', 'packages', 'template', 'needsAllTemplates'));
    }

    /**
     * Create Midtrans Snap transaction and redirect.
     */
    public function checkout(Request $request, Invitation $invitation)
    {
        $this->authorize('update', $invitation);

        $request->validate(['package_id' => 'required|exists:packages,id']);

        $package = Package::findOrFail($request->package_id);

        // Check there is no pending transaction for this invitation
        $existing = Transaction::where('invitation_id', $invitation->id)
            ->where('status', 'pending')
            ->where('expired_at', '>', now())
            ->first();

        if ($existing) {
            return view('payment.checkout', [
                'transaction' => $existing,
                'invitation'  => $invitation,
                'snapToken'   => $existing->snap_token,
                'snapUrl'     => $this->midtrans->getSnapUrl(),
                'clientKey'   => $this->midtrans->getSnapClientKey(),
            ]);
        }

        $transaction = $this->midtrans->createTransaction(auth()->user(), $package, $invitation);

        return view('payment.checkout', [
            'transaction' => $transaction,
            'invitation'  => $invitation,
            'snapToken'   => $transaction->snap_token,
            'snapUrl'     => $this->midtrans->getSnapUrl(),
            'clientKey'   => $this->midtrans->getSnapClientKey(),
        ]);
    }

    /**
     * Midtrans webhook handler — NEVER trust frontend callbacks.
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans webhook received', ['order_id' => $payload['order_id'] ?? 'unknown']);

        try {
            $transaction = $this->midtrans->handleWebhook($payload);

            if ($transaction->isPaid()) {
                $this->activateInvitation($transaction);
                Log::info('Invitation activated', ['order_id' => $transaction->order_id]);
            }

            return response()->json(['status' => 'ok']);
        } catch (\RuntimeException $e) {
            Log::warning('Midtrans webhook validation failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        } catch (\Throwable $e) {
            Log::error('Midtrans webhook error', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error'], 500);
        }
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

    /**
     * Check & sync payment status directly from Midtrans API.
     * Called by frontend after Snap popup closes — works without webhook.
     */
    public function checkStatus(Request $request, Transaction $transaction)
    {
        // Only owner can check
        if ((int) $transaction->user_id !== (int) auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $transaction = $this->midtrans->checkAndSync($transaction);

        if ($transaction->isPaid()) {
            $this->activateInvitation($transaction);
        }

        return response()->json([
            'status'      => $transaction->status,
            'is_paid'     => $transaction->isPaid(),
            'order_id'    => $transaction->order_id,
        ]);
    }

    /** Midtrans finish callback (frontend redirect) */
    public function finish(Request $request)
    {
        // Cari transaction dari order_id yang dikirim Midtrans di query string
        $orderId = $request->query('order_id');
        if ($orderId) {
            $transaction = Transaction::where('order_id', $orderId)
                ->where('user_id', auth()->id())
                ->first();
            if ($transaction?->invitation_id) {
                return redirect()->route('editor', $transaction->invitation_id)
                    ->with('success', 'Pembayaran berhasil! Undangan Anda sudah aktif. Sekarang lengkapi detail undangan Anda 🎉');
            }
        }

        return redirect()->route('dashboard')
            ->with('success', 'Pembayaran berhasil! Undangan Anda sedang diaktifkan.');
    }

    public function error(Request $request)
    {
        return redirect()->route('dashboard')
            ->with('error', 'Pembayaran gagal. Silakan coba lagi.');
    }

    public function pending(Request $request)
    {
        return redirect()->route('dashboard')
            ->with('info', 'Pembayaran sedang diproses. Kami akan mengirim notifikasi setelah pembayaran dikonfirmasi.');
    }
}
