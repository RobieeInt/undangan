<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    /**
     * Create a Midtrans Snap transaction and persist the Transaction record.
     */
    public function createTransaction(User $user, Package $package, Invitation $invitation): Transaction
    {
        $orderId = 'INV-' . strtoupper(Str::random(8)) . '-' . time();

        // Midtrans requires a valid RFC-compliant email (e.g. rejects "user@local")
        $email = $user->email ?? '';
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $localPart = Str::slug($user->name ?: 'user') . $user->id;
            $email     = $localPart . '@mail.undangan.app';
        }

        // Build Midtrans params
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $package->price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $email,
                'phone'      => $user->phone ?? '',
            ],
            'item_details' => [
                [
                    'id'       => 'PKG-' . $package->id,
                    'price'    => (int) $package->price,
                    'quantity' => 1,
                    'name'     => 'Paket ' . $package->name . ' — ' . $invitation->getCoupleName(),
                ],
            ],
            'callbacks' => [
                'finish'  => route('payment.finish'),
                'error'   => route('payment.error'),
                'pending' => route('payment.pending'),
            ],
        ];

        // Get Snap token + redirect URL from Midtrans
        $snapResult  = Snap::createTransaction($params);
        $snapToken   = $snapResult->token;
        $snapPageUrl = $snapResult->redirect_url;

        // Persist transaction record
        $expiredAt = now()->addHours(24);

        $transaction = Transaction::create([
            'user_id'          => $user->id,
            'invitation_id'    => $invitation->id,
            'package_id'       => $package->id,
            'order_id'         => $orderId,
            'gross_amount'     => $package->price,
            'status'           => 'pending',
            'snap_token'       => $snapToken,
            'snap_redirect_url'=> $snapPageUrl,
            'expired_at'       => $expiredAt,
        ]);

        return $transaction;
    }

    /**
     * Handle and verify a Midtrans webhook notification.
     * Returns the verified Transaction or throws on failure.
     */
    public function handleWebhook(array $payload): Transaction
    {
        // 1. Verify signature
        $this->verifySignature($payload);

        // 2. Find the transaction
        $transaction = Transaction::where('order_id', $payload['order_id'])->firstOrFail();

        // 3. Verify gross amount
        if ((int) $payload['gross_amount'] !== (int) $transaction->gross_amount) {
            throw new \RuntimeException('Gross amount mismatch. Possible fraud attempt.');
        }

        // 4. Map Midtrans status
        $midtransStatus    = $payload['transaction_status'] ?? '';
        $fraudStatus       = $payload['fraud_status'] ?? '';
        $paymentType       = $payload['payment_type'] ?? '';

        $status = $this->mapStatus($midtransStatus, $fraudStatus);

        // 5. Update transaction
        $transaction->update([
            'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
            'status'                  => $status,
            'payment_type'            => $paymentType,
            'payment_method'          => $payload['payment_method'] ?? $paymentType,
            'midtrans_response'       => $payload,
            'paid_at'                 => $status === 'paid' ? now() : null,
        ]);

        return $transaction;
    }

    private function verifySignature(array $payload): void
    {
        $orderId     = $payload['order_id'] ?? '';
        $statusCode  = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $serverKey   = config('midtrans.server_key');

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        $received = $payload['signature_key'] ?? '';

        if (!hash_equals($expected, $received)) {
            throw new \RuntimeException('Invalid Midtrans signature key.');
        }
    }

    private function mapStatus(string $midtransStatus, string $fraudStatus): string
    {
        return match(true) {
            $midtransStatus === 'capture' && $fraudStatus === 'accept' => 'paid',
            $midtransStatus === 'capture' && $fraudStatus === 'challenge' => 'pending',
            $midtransStatus === 'settlement' => 'paid',
            $midtransStatus === 'pending'    => 'pending',
            $midtransStatus === 'deny'       => 'failed',
            $midtransStatus === 'expire'     => 'expired',
            $midtransStatus === 'cancel'     => 'failed',
            $midtransStatus === 'refund'     => 'refund',
            default                          => 'pending',
        };
    }

    /**
     * Check transaction status directly from Midtrans API.
     * Used as fallback when webhook hasn't arrived yet (e.g. localhost dev).
     */
    public function checkAndSync(Transaction $transaction): Transaction
    {
        try {
            $status = \Midtrans\Transaction::status($transaction->order_id);

            $midtransStatus = $status->transaction_status ?? '';
            $fraudStatus    = $status->fraud_status ?? '';
            $paymentType    = $status->payment_type ?? '';

            $mappedStatus = $this->mapStatus($midtransStatus, $fraudStatus);

            $transaction->update([
                'midtrans_transaction_id' => $status->transaction_id ?? null,
                'status'                  => $mappedStatus,
                'payment_type'            => $paymentType,
                'midtrans_response'       => (array) $status,
                'paid_at'                 => $mappedStatus === 'paid' ? now() : $transaction->paid_at,
            ]);

            $transaction->refresh();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Midtrans status check failed', [
                'order_id' => $transaction->order_id,
                'error'    => $e->getMessage(),
            ]);
        }

        return $transaction;
    }

    public function getSnapClientKey(): string
    {
        return config('midtrans.client_key');
    }

    public function getSnapUrl(): string
    {
        return config('midtrans.snap_url');
    }
}
