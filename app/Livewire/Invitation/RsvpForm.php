<?php

namespace App\Livewire\Invitation;

use App\Models\Invitation;
use App\Models\InvitationGuest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;

class RsvpForm extends Component
{
    public Invitation $invitation;

    // 'search' = belum dikenali, 'form' = sudah match ke tamu
    public string $step = 'search';

    // Store only scalars — never Eloquent models — to avoid hydration errors
    public int    $guestId        = 0;
    public string $guestQrUrl     = '';
    public bool   $isReadonlyName = false;
    public bool   $submitted      = false;
    public int    $totalHadir     = 0;

    // Step search
    public string $searchName    = '';
    public array  $searchResults = [];
    public bool   $notFound      = false;

    #[Rule('required|string|max:100')]
    public string $name = '';

    #[Rule('nullable|string|max:20')]
    public string $phone = '';

    #[Rule('required|in:hadir,tidak_hadir,mungkin')]
    public string $attendance = 'hadir';

    #[Rule('required|integer|min:1|max:20')]
    public int $guest_count = 1;

    #[Rule('nullable|string|max:500')]
    public string $message = '';

    public function mount(Invitation $invitation, ?InvitationGuest $guest = null)
    {
        $this->invitation = $invitation;

        // Fallback: cari dari ?tamu= jika $guest tidak dikirim dari view
        if (! $guest) {
            $tamu = request()->query('tamu');
            if ($tamu) {
                $guest = InvitationGuest::where('invitation_id', $invitation->id)
                    ->where('slug', $tamu)
                    ->first();
            }
        }

        if ($guest) {
            $this->resolveGuest($guest);
        }

        $this->totalHadir = DB::table('invitation_rsvps')
            ->where('invitation_id', $invitation->id)
            ->where('attendance', 'hadir')
            ->sum('guest_count');
    }

    /* ── Name search (debounced via wire:model.live.debounce) ── */

    public function updatedSearchName(): void
    {
        $this->notFound      = false;
        $this->searchResults = [];

        $q = trim($this->searchName);
        if (mb_strlen($q) < 2) {
            return;
        }

        $results = InvitationGuest::where('invitation_id', $this->invitation->id)
            ->where('name', 'like', '%' . $q . '%')
            ->orderBy('name')
            ->limit(6)
            ->get(['id', 'name', 'allocated_seats', 'phone'])
            ->toArray();

        $this->searchResults = $results;
        $this->notFound      = empty($results) && mb_strlen($q) >= 3;
    }

    public function selectGuest(int $guestId): void
    {
        $guest = InvitationGuest::where('id', $guestId)
            ->where('invitation_id', $this->invitation->id)
            ->first();

        if (! $guest) {
            return;
        }

        $this->resolveGuest($guest);
    }

    /* ── Submit ── */

    public function submit(): void
    {
        if (! $this->invitation->is_open) {
            $this->addError('name', 'RSVP sudah ditutup.');
            return;
        }

        // Wajib dari daftar tamu
        if ($this->guestId === 0) {
            $this->addError('name', 'Nama Anda tidak ditemukan dalam daftar tamu undangan.');
            return;
        }

        $this->validate();

        // Cegah duplikat
        $exists = DB::table('invitation_rsvps')
            ->where('guest_id', $this->guestId)
            ->exists();

        if ($exists) {
            $this->submitted = true;
            return;
        }

        DB::table('invitation_rsvps')->insert([
            'invitation_id' => $this->invitation->id,
            'guest_id'      => $this->guestId,
            'name'          => $this->name,
            'phone'         => $this->phone ?: null,
            'attendance'    => $this->attendance,
            'guest_count'   => $this->guest_count,
            'message'       => $this->message ?: null,
            'ip_address'    => request()->ip(),
            'user_agent'    => substr(request()->userAgent() ?? '', 0, 500),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Refresh QR jika ada
        if (! $this->guestQrUrl) {
            $g = InvitationGuest::find($this->guestId);
            if ($g && $g->qr_code && Storage::exists($g->qr_code)) {
                $this->guestQrUrl = Storage::url($g->qr_code);
            }
        }

        $this->totalHadir = DB::table('invitation_rsvps')
            ->where('invitation_id', $this->invitation->id)
            ->where('attendance', 'hadir')
            ->sum('guest_count');

        $this->submitted = true;

        // Trigger GuestWishes component to refresh
        $this->dispatch('rsvp-submitted');
    }

    /* ── Internal ── */

    private function resolveGuest(InvitationGuest $guest): void
    {
        $this->guestId        = $guest->id;
        $this->name           = $guest->name;
        $this->phone          = $guest->phone ?? '';
        $this->guest_count    = max(1, (int) $guest->allocated_seats);
        $this->isReadonlyName = true;
        $this->searchResults  = [];
        $this->step           = 'form';

        if ($guest->qr_code && Storage::exists($guest->qr_code)) {
            $this->guestQrUrl = Storage::url($guest->qr_code);
        }

        if ($guest->hasRsvped()) {
            $this->submitted = true;
        }
    }

    public function render()
    {
        return view('livewire.invitation.rsvp-form');
    }
}
