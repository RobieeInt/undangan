<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { margin:0; padding:0; background:#FBF5DD; font-family:'Georgia',serif; }
.container { max-width:600px; margin:0 auto; background:#fff; }
.header { background:linear-gradient(135deg,#C97C22,#D4850A); padding:40px 32px; text-align:center; }
.header h1 { color:#fff; font-size:24px; margin:0; }
.body { padding:40px 32px; }
.btn { display:inline-block; background:#306D29; color:#FBF5DD; padding:14px 32px; border-radius:10px; text-decoration:none; font-size:14px; }
.footer { text-align:center; padding:24px 32px; font-size:12px; color:#888; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>⚠ Undangan Akan Kadaluarsa</h1>
    </div>
    <div class="body">
        <p>Halo <strong>{{ $invitation->user->name }}</strong>,</p>
        <p>Undangan <strong>{{ $invitation->getCoupleName() }}</strong> Anda akan kadaluarsa pada <strong>{{ $invitation->expires_at?->translatedFormat('d F Y') }}</strong> (14 hari lagi).</p>
        <p>Perpanjang sekarang agar undangan Anda tetap aktif dan dapat diakses oleh tamu undangan.</p>
        <div style="text-align:center;margin:30px 0">
            <a href="{{ route('dashboard') }}" class="btn">Perpanjang Sekarang</a>
        </div>
    </div>
    <div class="footer">© {{ date('Y') }} Invora.id</div>
</div>
</body>
</html>
