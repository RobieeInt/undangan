<?php

namespace App\Livewire\Invitation;

use App\Models\Invitation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class QrCheckin extends Component
{
    public Invitation $invitation;
    public bool $scanning = false;
    public ?array $lastResult = null;
    public array $recentCheckins = [];
    public int $totalCheckedIn = 0;
    public int $totalGuests = 0;

    public function mount(Invitation $invitation)
    {
        $this->authorize('update', $invitation);
        $this->invitation = $invitation;
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $this->totalGuests    = DB::table('invitation_guests')
            ->where('invitation_id', $this->invitation->id)
            ->count();

        $this->totalCheckedIn = DB::table('guest_checkins')
            ->where('invitation_id', $this->invitation->id)
            ->count();

        $this->recentCheckins = DB::table('guest_checkins')
            ->join('invitation_guests', 'guest_checkins.guest_id', '=', 'invitation_guests.id')
            ->where('guest_checkins.invitation_id', $this->invitation->id)
            ->select('invitation_guests.name', 'guest_checkins.checked_in_at')
            ->orderByDesc('guest_checkins.checked_in_at')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Called from frontend after QR scan decodes a token URL.
     */
    public function processToken(string $token): void
    {
        $response = app(\App\Services\QrCodeService::class)->verifyToken($token);

        if (!$response) {
            $this->lastResult = ['success' => false, 'message' => 'QR code tidak valid.'];
            return;
        }

        $guest = $response;

        // Check if already checked in
        $existing = DB::table('guest_checkins')->where('guest_id', $guest->id)->first();
        if ($existing) {
            $this->lastResult = [
                'success'    => false,
                'already_in' => true,
                'message'    => "⚠ {$guest->name} sudah check-in.",
            ];
            return;
        }

        // Perform check-in
        DB::table('guest_checkins')->insert([
            'invitation_id' => $this->invitation->id,
            'guest_id'      => $guest->id,
            'checked_in_at' => now(),
            'checked_in_by' => auth()->user()->name,
            'ip_address'    => request()->ip(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $this->lastResult = [
            'success'  => true,
            'message'  => "✓ {$guest->name} berhasil check-in!",
            'seats'    => $guest->allocated_seats,
        ];

        $this->loadStats();
    }

    public function render()
    {
        return view('livewire.invitation.qr-checkin');
    }
}
