<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { margin:0; padding:0; background:#f4f4f4; font-family:Arial,sans-serif; }
.container { max-width:600px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; }
.header { background:linear-gradient(135deg,#306D29,#0D530E); padding:36px 32px; text-align:center; }
.header h1 { color:#FBF5DD; font-size:24px; margin:0 0 6px; font-family:Georgia,serif; }
.header p { color:rgba(251,245,221,.7); font-size:13px; margin:0; }
.body { padding:36px 32px; color:#374151; font-size:15px; line-height:1.6; }
.btn-wrap { text-align:center; margin:28px 0; }
.btn { display:inline-block; background:#306D29; color:#FBF5DD !important; padding:14px 36px; border-radius:10px; text-decoration:none; font-size:15px; font-weight:bold; }
.note { background:#FBF5DD; border-radius:10px; padding:16px 20px; font-size:13px; color:#6b7280; margin:24px 0 0; }
.url-fallback { word-break:break-all; font-size:12px; color:#9ca3af; margin-top:16px; }
.footer { text-align:center; padding:20px 32px; font-size:12px; color:#9ca3af; background:#f9fafb; border-top:1px solid #f3f4f6; }
</style>
</head>
<body>
<div style="padding:24px 16px;">
<div class="container">
    <div class="header">
        <h1>🔐 Reset Password</h1>
        <p>{{ config('app.name') }}</p>
    </div>
    <div class="body">
        <p>Halo <strong>{{ $user->name }}</strong>,</p>
        <p>Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah untuk membuat password baru:</p>

        <div class="btn-wrap">
            <a href="{{ $resetUrl }}" class="btn">Reset Password Saya</a>
        </div>

        <div class="note">
            ⏱ Link ini hanya berlaku selama <strong>60 menit</strong>.<br>
            Jika Anda tidak merasa meminta reset password, abaikan email ini — akun Anda tetap aman.
        </div>

        <p class="url-fallback">
            Jika tombol tidak berfungsi, salin dan buka link berikut di browser:<br>
            <a href="{{ $resetUrl }}" style="color:#306D29;">{{ $resetUrl }}</a>
        </p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} {{ config('app.name') }} · Email ini dikirim otomatis, jangan dibalas.
    </div>
</div>
</div>
</body>
</html>
