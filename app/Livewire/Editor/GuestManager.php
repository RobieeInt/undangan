<?php

namespace App\Livewire\Editor;

use App\Models\Invitation;
use App\Models\InvitationGuest;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Support\XlsxHelper;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class GuestManager extends Component
{
    use WithPagination, WithFileUploads;

    public Invitation $invitation;
    public string $search       = '';
    public string $newName      = '';
    public string $newPhone     = '';
    public string $newNotes     = '';
    public int    $newSeats     = 1;
    public bool   $showForm     = false;

    // Import via teks
    public string $importText   = '';
    public bool   $showImport   = false;

    // Import via Excel
    public bool   $showExcelImport = false;
    #[Validate(['importFile' => 'nullable|file|mimes:xlsx,xls,csv,txt|max:5120'])]
    public $importFile = null;

    public function mount(Invitation $invitation)
    {
        $this->authorize('update', $invitation);
        $this->invitation = $invitation;
    }

    public function addGuest(): void
    {
        $this->validate([
            'newName' => 'required|string|max:100',
            'newPhone' => 'nullable|string|max:20',
            'newSeats' => 'required|integer|min:1|max:20',
        ]);

        $package   = $this->invitation->package;
        $maxGuests = $package?->max_guests ?? 100;

        $currentCount = DB::table('invitation_guests')
            ->where('invitation_id', $this->invitation->id)
            ->count();

        if ($currentCount >= $maxGuests) {
            $this->addError('newName', "Batas maksimal {$maxGuests} tamu untuk paket ini.");
            return;
        }

        // Generate unique slug for this guest
        $slug = Str::slug($this->newName) . '-' . Str::random(6);
        while (DB::table('invitation_guests')->where('slug', $slug)->exists()) {
            $slug = Str::slug($this->newName) . '-' . Str::random(6);
        }

        $guest = InvitationGuest::create([
            'invitation_id'   => $this->invitation->id,
            'name'            => $this->newName,
            'slug'            => $slug,
            'phone'           => $this->newPhone ?: null,
            'notes'           => $this->newNotes ?: null,
            'allocated_seats' => $this->newSeats,
        ]);

        // Generate QR code
        app(QrCodeService::class)->generateForGuest($guest);

        $this->reset(['newName', 'newPhone', 'newNotes', 'newSeats', 'showForm']);
        $this->dispatch('guest-added');
    }

    public function deleteGuest(int $id): void
    {
        $guest = InvitationGuest::where('id', $id)
            ->where('invitation_id', $this->invitation->id)
            ->first();

        if (!$guest) return;

        // Clean up QR file
        if ($guest->qr_code) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($guest->qr_code);
        }

        $guest->delete();
    }

    public function bulkImport(): void
    {
        if (empty(trim($this->importText))) return;

        $lines  = explode("\n", trim($this->importText));
        $count  = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = explode(',', $line, 2);
            $name  = trim($parts[0]);
            $phone = isset($parts[1]) ? trim($parts[1]) : null;

            if (empty($name)) continue;

            $slug = Str::slug($name) . '-' . Str::random(6);

            $guest = InvitationGuest::create([
                'invitation_id'   => $this->invitation->id,
                'name'            => $name,
                'slug'            => $slug,
                'phone'           => $phone,
                'allocated_seats' => 1,
            ]);

            app(QrCodeService::class)->generateForGuest($guest);
            $count++;
        }

        $this->importText = '';
        $this->showImport = false;
        session()->flash('message', "{$count} tamu berhasil diimpor.");
    }

    /* ─────────────────────────────────────────────
       Toggle import panels (mutually exclusive)
    ───────────────────────────────────────────── */

    public function toggleTextImport(): void
    {
        $this->showImport      = ! $this->showImport;
        $this->showExcelImport = false;
    }

    public function toggleExcelImport(): void
    {
        $this->showExcelImport = ! $this->showExcelImport;
        $this->showImport      = false;
    }

    /* ─────────────────────────────────────────────
       Excel/CSV import
    ───────────────────────────────────────────── */

    public function downloadTemplate()
    {
        $headers = ['Nama', 'Nomor HP', 'Jumlah Kursi', 'Catatan'];
        $rows    = [
            ['Bapak Ahmad Rizki', '08123456789', 2, 'Keluarga pengantin pria'],
            ['Ibu Sari Dewi',     '08987654321', 1, ''],
            ['Rudi Santoso',      '',            1, ''],
        ];

        $bytes = XlsxHelper::generate($headers, $rows);

        return response()->streamDownload(
            fn () => print($bytes),
            'template-import-tamu.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        );
    }

    public function importFromFile(): void
    {
        $this->validate(['importFile' => 'required|file|mimes:xlsx,xls,csv,txt|max:5120']);

        $path      = $this->importFile->getRealPath();
        $ext       = strtolower($this->importFile->getClientOriginalExtension());
        $count     = 0;
        $skipped   = 0;

        $package   = $this->invitation->package;
        $maxGuests = $package?->max_guests ?? 100;

        // ── Parse rows ─────────────────────────────────────
        if (in_array($ext, ['xlsx', 'xls'])) {
            $allRows = XlsxHelper::parse($path);
        } else {
            // CSV / TXT
            $allRows = [];
            $handle  = fopen($path, 'r');
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $allRows[] = $row;
            }
            fclose($handle);
        }

        // ── Process rows (skip header) ─────────────────────
        foreach ($allRows as $idx => $row) {
            // Baris pertama = header jika kolom 0 adalah "nama" (case-insensitive)
            if ($idx === 0 && mb_strtolower(trim($row[0] ?? '')) === 'nama') {
                continue;
            }

            $name  = trim($row[0] ?? '');
            $phone = trim($row[1] ?? '');
            $seats = max(1, (int) ($row[2] ?? 1));
            $notes = trim($row[3] ?? '');

            if (empty($name)) {
                $skipped++;
                continue;
            }

            $currentCount = DB::table('invitation_guests')
                ->where('invitation_id', $this->invitation->id)
                ->count();

            if ($currentCount >= $maxGuests) {
                $skipped++;
                continue;
            }

            $slug = Str::slug($name) . '-' . Str::random(6);
            while (DB::table('invitation_guests')->where('slug', $slug)->exists()) {
                $slug = Str::slug($name) . '-' . Str::random(6);
            }

            $guest = InvitationGuest::create([
                'invitation_id'   => $this->invitation->id,
                'name'            => $name,
                'slug'            => $slug,
                'phone'           => $phone ?: null,
                'notes'           => $notes ?: null,
                'allocated_seats' => $seats,
            ]);

            app(QrCodeService::class)->generateForGuest($guest);
            $count++;
        }

        $this->importFile      = null;
        $this->showExcelImport = false;

        $msg = "{$count} tamu berhasil diimpor.";
        if ($skipped > 0) $msg .= " {$skipped} baris dilewati.";
        session()->flash('message', $msg);
    }

    /* ─────────────────────────────────────────────
       WhatsApp helper
    ───────────────────────────────────────────── */

    public function whatsappUrl(string $phone, string $guestName, string $guestSlug): string
    {
        // Normalisasi nomor → format internasional 62xxx
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (! str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        $personalUrl = url('/' . $this->invitation->slug . '?tamu=' . $guestSlug);

        // Ambil event pertama kalau ada
        $event = DB::table('invitation_events')
            ->where('invitation_id', $this->invitation->id)
            ->orderBy('sort_order')
            ->first();

        $eventLine = '';
        if ($event) {
            $tgl = $event->date
                ? \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y')
                : '';
            $jam = $event->time_start
                ? \Carbon\Carbon::parse($event->time_start)->format('H:i')
                : '';
            $venue = $event->venue ?? '';
            $eventLine = "\n📅 " . trim("{$event->name} · {$tgl}" . ($jam ? ", {$jam} WIB" : ''));
            if ($venue) $eventLine .= "\n📍 {$venue}";
        }

        $groom = $this->invitation->groom_name;
        $bride = $this->invitation->bride_name;

        $msg = "Halo, {$guestName} 👋\n\n"
            . "Kami mengundang Bapak/Ibu/Saudara/i *{$guestName}* untuk hadir di momen istimewa kami:\n\n"
            . "💍 *{$groom} & {$bride}*"
            . $eventLine . "\n\n"
            . "Konfirmasi kehadiran & lihat detail undangan melalui link pribadi Anda:\n"
            . $personalUrl . "\n\n"
            . "Kehadiran Anda adalah kebahagiaan kami 🙏\n\n"
            . "_Hormat kami,_\n"
            . "_Keluarga {$groom} & {$bride}_";

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($msg);
    }

    public function exportCsv()
    {
        $package = $this->invitation->package;
        if (!$package?->has_rsvp_export) {
            session()->flash('error', 'Fitur export tersedia di paket Premium ke atas.');
            return;
        }

        $guests = DB::table('invitation_guests')
            ->leftJoin('invitation_rsvps', 'invitation_guests.id', '=', 'invitation_rsvps.guest_id')
            ->leftJoin('guest_checkins', 'invitation_guests.id', '=', 'guest_checkins.guest_id')
            ->where('invitation_guests.invitation_id', $this->invitation->id)
            ->select(
                'invitation_guests.name',
                'invitation_guests.phone',
                'invitation_guests.allocated_seats',
                'invitation_rsvps.attendance',
                'invitation_rsvps.guest_count',
                'invitation_rsvps.message',
                'guest_checkins.checked_in_at'
            )
            ->get();

        $csv  = "Nama,Telepon,Kursi Dialokasikan,Konfirmasi Kehadiran,Jumlah Hadir,Pesan,Check-in\n";
        foreach ($guests as $g) {
            $csv .= "\"{$g->name}\",\"{$g->phone}\",{$g->allocated_seats},{$g->attendance},{$g->guest_count},\"{$g->message}\",\"{$g->checked_in_at}\"\n";
        }

        return response()->streamDownload(fn() => print($csv),
            'tamu-' . $this->invitation->slug . '.csv',
            ['Content-Type' => 'text/csv']
        );
    }

    public function render()
    {
        $guests = DB::table('invitation_guests')
            ->leftJoin('invitation_rsvps', 'invitation_guests.id', '=', 'invitation_rsvps.guest_id')
            ->leftJoin('guest_checkins', 'invitation_guests.id', '=', 'guest_checkins.guest_id')
            ->where('invitation_guests.invitation_id', $this->invitation->id)
            ->when($this->search, fn($q) => $q->where('invitation_guests.name', 'like', "%{$this->search}%"))
            ->select(
                'invitation_guests.*',
                'invitation_rsvps.attendance',
                'guest_checkins.checked_in_at'
            )
            ->orderBy('invitation_guests.name')
            ->paginate(20);

        $stats = [
            'total'       => DB::table('invitation_guests')->where('invitation_id', $this->invitation->id)->count(),
            'rsvped'      => DB::table('invitation_rsvps')->where('invitation_id', $this->invitation->id)->count(),
            'checked_in'  => DB::table('guest_checkins')->where('invitation_id', $this->invitation->id)->count(),
        ];

        return view('livewire.editor.guest-manager', compact('guests', 'stats'));
    }
}
