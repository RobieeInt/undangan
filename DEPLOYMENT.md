# Deployment Guide — cPanel Shared Hosting

## Persiapan Server

### Requirements
- PHP 8.3+
- MySQL 8.0+
- mod_rewrite enabled
- Ekstensi PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDX, Tokenizer, XML, GD, Zip

---

## Langkah Deploy ke cPanel

### 1. Upload Files
Upload semua file ke folder `public_html` atau subdomain di cPanel File Manager, **kecuali** folder `public/`.

Struktur yang benar:
```
~/domains/undangan.com/
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
└── artisan

~/domains/undangan.com/public_html/   ← isi dari folder public/
├── .htaccess
├── index.php
├── build/
└── storage → (symlink ke ../storage/app/public)
```

### 2. Konfigurasi index.php
Edit `public_html/index.php`, ubah path ke aplikasi:
```php
require __DIR__.'/../bootstrap/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

### 3. Database
1. Buat database MySQL di cPanel → MySQL Databases
2. Buat user dan assign ke database
3. Update `.env`:
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=nama_user
DB_PASSWORD=password
```

### 4. .env Configuration
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://undangan.com

# Midtrans
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
MIDTRANS_IS_PRODUCTION=false  # ubah ke true saat live

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mail.undangan.com
MAIL_PORT=465
MAIL_USERNAME=noreply@undangan.com
MAIL_PASSWORD=password
MAIL_ENCRYPTION=ssl

QUEUE_CONNECTION=database
SESSION_DRIVER=file
CACHE_STORE=file
```

### 5. Jalankan Artisan Commands (via SSH atau PHP terminal)
```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Storage Symlink (jika tidak bisa via artisan)
Buat symlink manual:
```bash
ln -s /home/user/undangan/storage/app/public /home/user/public_html/storage
```

### 7. Cronjob (cPanel → Cron Jobs)
Tambahkan cron job:
```
* * * * * /usr/local/bin/php /home/user/undangan/artisan schedule:run >> /dev/null 2>&1
```

### 8. Queue Worker
Untuk queue email (opsional, gunakan jika server mendukung):
```
* * * * * /usr/local/bin/php /home/user/undangan/artisan queue:work --sleep=3 --tries=3 --max-time=60 >> /dev/null 2>&1
```

---

## Midtrans Setup

1. Register di https://midtrans.com
2. Gunakan **Sandbox** untuk testing, **Production** untuk live
3. Set Notification URL di dashboard Midtrans:
   ```
   https://undangan.com/midtrans/webhook
   ```
4. Whitelist IP server di Midtrans jika diperlukan

---

## Admin Panel
URL: `https://undangan.com/admin`
Default credentials (ubah segera setelah deploy):
- Email: `admin@undangan.com`
- Password: set via `ADMIN_PASSWORD` di `.env`

```bash
php artisan db:seed  # akan membuat admin dengan kredensial dari .env
```

---

## Performance Tips (cPanel)

1. **Enable OPcache** via cPanel PHP Settings
2. **Enable Gzip** — sudah dikonfigurasi di `.htaccess`
3. **Cache headers** — sudah dikonfigurasi di `.htaccess`
4. **Database indexes** — sudah ditambahkan di semua migration
5. Gunakan **file cache** (bukan Redis/Memcache yang mungkin tidak tersedia)

---

## Troubleshooting

**500 Error:** Check `storage/logs/laravel.log`

**403 Forbidden:** Pastikan permission folder:
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 644 .env
```

**Queue tidak jalan:** Gunakan `QUEUE_CONNECTION=sync` di `.env` (tidak async tapi lebih simple)
