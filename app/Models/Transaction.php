<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'invitation_id', 'package_id', 'order_id',
        'midtrans_transaction_id', 'gross_amount', 'status',
        'payment_type', 'payment_method', 'snap_token', 'snap_redirect_url',
        'paid_at', 'expired_at', 'midtrans_response',
    ];

    protected function casts(): array
    {
        return [
            'paid_at'            => 'datetime',
            'expired_at'         => 'datetime',
            'midtrans_response'  => 'array',
        ];
    }

    public function scopePaid($q)    { return $q->where('status', 'paid'); }
    public function scopePending($q) { return $q->where('status', 'pending'); }

    public function user()       { return $this->belongsTo(User::class); }
    public function invitation() { return $this->belongsTo(Invitation::class); }
    public function package()    { return $this->belongsTo(Package::class); }

    public function isPaid(): bool    { return $this->status === 'paid'; }
    public function isPending(): bool { return $this->status === 'pending'; }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->gross_amount, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'paid'    => 'Lunas',
            'pending' => 'Menunggu Pembayaran',
            'failed'  => 'Gagal',
            'expired' => 'Kadaluarsa',
            'refund'  => 'Refund',
            default   => '-',
        };
    }
}
