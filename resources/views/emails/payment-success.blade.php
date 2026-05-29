<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { margin:0; padding:0; background:#FBF5DD; font-family:'Georgia',serif; }
.container { max-width:600px; margin:0 auto; background:#fff; }
.header { background:linear-gradient(135deg,#306D29,#0D530E); padding:40px 32px; text-align:center; }
.header h1 { color:#FBF5DD; font-size:28px; margin:0; }
.body { padding:40px 32px; }
.box { background:#FBF5DD; border-radius:12px; padding:20px 24px; margin:20px 0; }
.detail { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #E7E1B1; font-size:14px; }
.btn { display:inline-block; background:#306D29; color:#FBF5DD; padding:14px 32px; border-radius:10px; text-decoration:none; font-size:14px; margin:24px 0; }
.footer { text-align:center; padding:24px 32px; font-size:12px; color:#888; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Pembayaran Berhasil ✓</h1>
        <p style="color:#E7E1B1;margin-top:8px;font-size:14px;">Undangan Anda telah diaktifkan</p>
    </div>
    <div class="body">
        <p>Halo <strong>{{ $transaction->user->name }}</strong>,</p>
        <p>Terima kasih! Pembayaran Anda telah berhasil diproses dan undangan Anda sudah aktif.</p>

        <div class="box">
            <div class="detail"><span>Order ID</span><strong>{{ $transaction->order_id }}</strong></div>
            <div class="detail"><span>Paket</span><strong>{{ $transaction->package->name }}</strong></div>
            <div class="detail"><span>Jumlah</span><strong>{{ $transaction->formatted_amount }}</strong></div>
            @if($transaction->invitation)
            <div class="detail"><span>Undangan</span><strong>{{ $transaction->invitation->getCoupleName() }}</strong></div>
            <div class="detail"><span>Aktif hingga</span><strong>{{ $transaction->invitation->expires_at?->format('d M Y') }}</strong></div>
            @endif
        </div>

        @if($transaction->invitation)
        <div style="text-align:center">
            <a href="{{ url('/' . $transaction->invitation->slug) }}" class="btn">Lihat Undangan Anda</a>
        </div>
        @endif
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} Invora.id — Platform Undangan Online Premium</p>
    </div>
</div>
</body>
</html>
