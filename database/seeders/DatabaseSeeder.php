<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ───────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@invora.id')],
            [
                'name'              => 'Administrator',
                'password'          => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
                'role'              => 'admin',
                'status'            => 'active',
                'email_verified_at' => now(),
            ]
        );

        // ── Packages ─────────────────────────────────────────────────────────
        $packages = [
            [
                'name'              => 'Basic',
                'slug'              => 'basic',
                'description'       => 'Paket dasar untuk undangan pernikahan sederhana',
                'price'             => 99000,
                'duration_days'     => 90,
                'max_guests'        => 50,
                'max_gallery'       => 6,
                'has_watermark'     => true,
                'has_analytics'     => false,
                'has_rsvp_export'   => false,
                'has_custom_domain' => false,
                'has_all_templates' => false,
                'has_qr_checkin'    => false,
                'max_music'         => 1,
                'features'          => json_encode(['Akses 3 bulan', 'Template dasar', 'RSVP online', '50 tamu', '6 foto galeri']),
                'is_active'         => true,
                'sort_order'        => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Premium',
                'slug'              => 'premium',
                'description'       => 'Paket premium dengan semua fitur lengkap',
                'price'             => 199000,
                'duration_days'     => 365,
                'max_guests'        => 500,
                'max_gallery'       => 30,
                'has_watermark'     => false,
                'has_analytics'     => true,
                'has_rsvp_export'   => true,
                'has_custom_domain' => false,
                'has_all_templates' => true,
                'has_qr_checkin'    => true,
                'max_music'         => 1,
                'features'          => json_encode(['Aktif 1 tahun', 'Semua template', 'Tanpa watermark', '500 tamu', '30 foto', 'Analytics', 'QR Check-in', 'Export RSVP']),
                'is_active'         => true,
                'sort_order'        => 2,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Exclusive',
                'slug'              => 'exclusive',
                'description'       => 'Paket eksklusif dengan custom domain dan fitur terlengkap',
                'price'             => 349000,
                'duration_days'     => 365,
                'max_guests'        => 2000,
                'max_gallery'       => 100,
                'has_watermark'     => false,
                'has_analytics'     => true,
                'has_rsvp_export'   => true,
                'has_custom_domain' => true,
                'has_all_templates' => true,
                'has_qr_checkin'    => true,
                'max_music'         => 5,
                'features'          => json_encode(['Aktif 1 tahun', 'Custom domain', 'Semua template premium', 'Tanpa watermark', '2000 tamu', '100 foto', 'Analytics lengkap', 'Priority support']),
                'is_active'         => true,
                'sort_order'        => 3,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ];

        foreach ($packages as $pkg) {
            DB::table('packages')->updateOrInsert(['slug' => $pkg['slug']], $pkg);
        }

        // ── Templates ────────────────────────────────────────────────────────
        $templates = [
            [
                'name'         => 'Floral Luxury',
                'slug'         => 'floral-luxury',
                'description'  => 'Template elegan dengan nuansa floral dan warna hijau emerald',
                'category'     => 'wedding',
                'is_premium'   => false,
                'is_exclusive' => false,
                'is_active'    => true,
                'config'       => json_encode(['theme' => 'light', 'primary' => '#306D29']),
                'sort_order'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'Dark Elegant',
                'slug'         => 'dark-elegant',
                'description'  => 'Template mewah dengan latar gelap dan aksen emas',
                'category'     => 'wedding',
                'is_premium'   => true,
                'is_exclusive' => false,
                'is_active'    => true,
                'config'       => json_encode(['theme' => 'dark', 'primary' => '#C9A84C']),
                'sort_order'   => 2,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'Emerald Islamic',
                'slug'         => 'emerald-islamic',
                'description'  => 'Template Islami dengan nuansa hijau emerald yang elegan',
                'category'     => 'wedding',
                'is_premium'   => false,
                'is_exclusive' => false,
                'is_active'    => true,
                'config'       => json_encode(['theme' => 'light', 'primary' => '#0D530E']),
                'sort_order'   => 3,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'Minimalist Modern',
                'slug'         => 'minimalist-modern',
                'description'  => 'Template bersih dan modern dengan tipografi premium',
                'category'     => 'wedding',
                'is_premium'   => false,
                'is_exclusive' => false,
                'is_active'    => true,
                'config'       => json_encode(['theme' => 'light', 'primary' => '#111827']),
                'sort_order'   => 4,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'Blue Butterfly',
                'slug'         => 'blue-butterfly',
                'description'  => 'Template romantis dengan nuansa biru lembut dan motif kupu-kupu',
                'category'     => 'wedding',
                'is_premium'   => false,
                'is_exclusive' => false,
                'is_active'    => true,
                'config'       => json_encode(['theme' => 'light', 'primary' => '#3B82F6']),
                'sort_order'   => 5,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'Jawa Klasik',
                'slug'         => 'jawa-klasik',
                'description'  => 'Template bergaya Jawa klasik dengan motif batik dan nuansa tradisional',
                'category'     => 'wedding',
                'is_premium'   => false,
                'is_exclusive' => false,
                'is_active'    => true,
                'config'       => json_encode(['theme' => 'light', 'primary' => '#7C3F00']),
                'sort_order'   => 6,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'Jawa Exclusive',
                'slug'         => 'jawa-exclusive',
                'description'  => 'Template Jawa eksklusif dengan ornamen batik premium dan tipografi keraton',
                'category'     => 'wedding',
                'is_premium'   => true,
                'is_exclusive' => true,
                'is_active'    => true,
                'config'       => json_encode(['theme' => 'jawa', 'primary' => '#7C3F00', 'accent' => '#D4AF37']),
                'sort_order'   => 7,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'Andalusia Exclusive',
                'slug'         => 'andalusia-exclusive',
                'description'  => 'Template eksklusif bergaya arsitektur Andalusia Islam — terinspirasi dari Alhambra Granada dengan ornamen arabesque, motif geometrik Islam, dan estetika Moorish yang mewah',
                'category'     => 'wedding',
                'is_premium'   => true,
                'is_exclusive' => true,
                'is_active'    => true,
                'config'       => json_encode([
                    'theme'   => 'andalusia',
                    'primary' => '#0D530E',
                    'accent'  => '#D4AF37',
                ]),
                'sort_order'   => 8,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'name'         => 'Batavia Royale',
                'slug'         => 'batavia-royale',
                'description'  => 'Template eksklusif bergaya Colonial Batavia Ballroom — Navy, Gold, dan Champagne dengan animasi Royal Curtain Reveal dan ornamen Art Deco',
                'category'     => 'wedding',
                'is_premium'   => true,
                'is_exclusive' => true,
                'is_active'    => true,
                'config'       => json_encode([
                    'theme'   => 'batavia',
                    'primary' => '#0F1E3A',
                    'accent'  => '#D4AF37',
                ]),
                'sort_order'   => 9,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        foreach ($templates as $tpl) {
            DB::table('templates')->updateOrInsert(['slug' => $tpl['slug']], $tpl);
        }

        $this->command->info('Database seeded successfully!');
    }
}
