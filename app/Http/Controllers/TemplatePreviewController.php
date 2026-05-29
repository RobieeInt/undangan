<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\InvitationGallery;
use App\Models\InvitationGift;
use App\Models\Template;

class TemplatePreviewController extends Controller
{
    public function __invoke(string $slug)
    {
        $template = Template::where('slug', $slug)->firstOrFail();

        $viewName = 'templates.' . $slug . '.index';
        if (!view()->exists($viewName)) {
            abort(404, 'Template preview not found.');
        }

        // ── Invitation dummy ──────────────────────────────────────────────────
        $invitation = new Invitation([
            'groom_name'           => 'Reza',
            'bride_name'           => 'Ayu',
            'groom_full_name'      => 'Muhammad Reza Pratama',
            'bride_full_name'      => 'Ayu Lestari Putri',
            'groom_father'         => 'Bapak Hendra Susanto',
            'groom_mother'         => 'Ibu Sari Wulandari',
            'bride_father'         => 'Bapak Wahyu Nugroho',
            'bride_mother'         => 'Ibu Dewi Rahayu',
            // Foto dummy dari picsum.photos (seed konsisten = gambar sama setiap load)
            'cover_photo'          => 'https://picsum.photos/seed/undangan-cover/1200/800',
            'groom_photo'          => 'https://picsum.photos/seed/undangan-groom/400/400',
            'bride_photo'          => 'https://picsum.photos/seed/undangan-bride/400/400',
            'opening_quote'        => '"Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu merasa tenteram kepadanya."',
            'opening_quote_source' => 'QS. Ar-Rum: 21',
            'story'                => 'Kami pertama kali bertemu di bangku kuliah pada tahun 2018. Pertemuan sederhana di perpustakaan itu ternyata menjadi awal dari perjalanan panjang yang penuh cinta dan kenangan indah. Kini, dengan restu orang tua dan izin Allah SWT, kami siap melanjutkan perjalanan ini ke jenjang yang lebih suci.',
            'music_url'            => null,
            'music_autoplay'       => false,
            'slug'                 => 'preview-' . $slug,
            'is_open'              => true,
            'is_active'            => true,
            'is_published'         => true,
            'rsvp_deadline'        => now()->addMonths(2),
            'template_id'          => $template->id,
        ]);
        $invitation->id = 0;

        // ── Events dummy ──────────────────────────────────────────────────────
        $weddingDate = now()->addMonths(3)->format('Y-m-d');
        $events = collect([
            (object)[
                'id'             => 1,
                'name'           => 'Akad Nikah',
                'date'           => $weddingDate,
                'time_start'     => '08:00',
                'time_end'       => '10:00',
                'venue'          => 'Masjid Al-Hikmah',
                'venue_address'  => 'Jl. Raya Mawar No. 12, Kebayoran Baru, Jakarta Selatan',
                'venue_maps_url' => 'https://maps.google.com',
                'sort_order'     => 1,
            ],
            (object)[
                'id'             => 2,
                'name'           => 'Resepsi Pernikahan',
                'date'           => $weddingDate,
                'time_start'     => '11:00',
                'time_end'       => '15:00',
                'venue'          => 'Gedung Serbaguna Harapan Indah',
                'venue_address'  => 'Jl. Kenanga Raya No. 5, Cilandak, Jakarta Selatan',
                'venue_maps_url' => 'https://maps.google.com',
                'sort_order'     => 2,
            ],
        ]);

        // ── Gallery dummy (8 foto, seed konsisten) ────────────────────────────
        $gallerySeeds = [
            ['seed' => 'wedding-g1', 'caption' => 'Pertama Kali Bertemu'],
            ['seed' => 'wedding-g2', 'caption' => 'Prewedding di Pantai'],
            ['seed' => 'wedding-g3', 'caption' => 'Momen Bersama Keluarga'],
            ['seed' => 'wedding-g4', 'caption' => 'Pre-wedding Session'],
            ['seed' => 'wedding-g5', 'caption' => 'Sunset Bersama'],
            ['seed' => 'wedding-g6', 'caption' => 'Kenangan Indah'],
            ['seed' => 'wedding-g7', 'caption' => 'Perjalanan Cinta'],
            ['seed' => 'wedding-g8', 'caption' => 'Menuju Hari Bahagia'],
        ];
        $galleries = collect(array_map(
            fn($item, $i) => new InvitationGallery([
                'image'      => "https://picsum.photos/seed/{$item['seed']}/800/600",
                'caption'    => $item['caption'],
                'sort_order' => $i + 1,
            ]),
            $gallerySeeds,
            array_keys($gallerySeeds)
        ));

        // ── Gifts dummy ───────────────────────────────────────────────────────
        $gifts = collect([
            new InvitationGift([
                'type'           => 'bank',
                'bank_name'      => 'BCA',
                'account_number' => '1234567890',
                'account_name'   => 'Muhammad Reza Pratama',
                'qris_image'     => null,
                'label'          => 'Transfer Bank',
                'sort_order'     => 1,
            ]),
            new InvitationGift([
                'type'           => 'bank',
                'bank_name'      => 'Mandiri',
                'account_number' => '0987654321',
                'account_name'   => 'Ayu Lestari Putri',
                'qris_image'     => null,
                'label'          => 'Transfer Bank',
                'sort_order'     => 2,
            ]),
        ]);

        // ── Ucapan / RSVP dummy (10 tamu) ────────────────────────────────────
        $recentWishes = collect([
            (object)['name' => 'Budi Santoso',      'message' => 'Selamat menempuh hidup baru! Semoga menjadi keluarga yang sakinah, mawaddah, warahmah 🙏', 'attendance' => 'hadir',       'created_at' => now()->subMinutes(30)],
            (object)['name' => 'Siti Rahayu',       'message' => 'Barakallahu lakuma wa baraka alaikuma. Semoga selalu dalam lindungan Allah dan langgeng hingga akhir hayat 💕', 'attendance' => 'hadir', 'created_at' => now()->subHours(2)],
            (object)['name' => 'Ahmad Fauzi',       'message' => 'Congrats bro! Semoga kalian selalu bahagia, kompak, dan dilancarkan rezekinya. Maaf tidak bisa hadir 🙏', 'attendance' => 'tidak_hadir', 'created_at' => now()->subHours(4)],
            (object)['name' => 'Rina Permatasari',  'message' => 'Wah selamat ya! Semoga jadi keluarga yang harmonis dan selalu dalam keadaan sehat. Kami sekeluarga ikut mendoakan 🌸', 'attendance' => 'hadir', 'created_at' => now()->subHours(6)],
            (object)['name' => 'Dian Kurniawan',    'message' => 'MasyaAllah, akhirnya! Selamat menjalani babak baru kehidupan. Semoga Allah selalu memberkahi kalian berdua ✨', 'attendance' => 'hadir', 'created_at' => now()->subHours(8)],
            (object)['name' => 'Mega Safitri',      'message' => 'Selamat bahagia untuk kalian berdua! Semoga rumah tangga kalian penuh dengan kasih sayang dan keberkahan 💒', 'attendance' => 'hadir', 'created_at' => now()->subDay()],
            (object)['name' => 'Hendro Prasetyo',   'message' => 'Wishing you both a lifetime of happiness! Semoga langgeng dan selalu setia satu sama lain ya 🎊', 'attendance' => 'hadir', 'created_at' => now()->subDays(2)],
            (object)['name' => 'Yunita Maharani',   'message' => 'Selamat untuk pasangan yang paling serasi! Semoga pernikahan ini membawa keberkahan untuk keluarga besar kalian 🌺', 'attendance' => 'tidak_hadir', 'created_at' => now()->subDays(2)],
            (object)['name' => 'Fajar Hidayat',     'message' => 'Alhamdulillah, akhirnya sampai di momen ini. Semoga Allah meridhoi langkah kalian dan dikaruniai keturunan yang sholeh 🤲', 'attendance' => 'hadir', 'created_at' => now()->subDays(3)],
            (object)['name' => 'Laila Nurhayati',   'message' => 'Bahagia banget dengernya! Selamat ya semoga jadi keluarga yang bahagia, sehat, dan selalu bersyukur 💝', 'attendance' => 'hadir', 'created_at' => now()->subDays(3)],
        ]);

        $guest          = null;
        $guestQrUrl     = null;
        $show_watermark = false; // matikan watermark di preview biar keliatan bersih

        return view($viewName, compact(
            'invitation', 'guest', 'events', 'galleries', 'gifts',
            'recentWishes', 'show_watermark', 'guestQrUrl'
        ));
    }
}
