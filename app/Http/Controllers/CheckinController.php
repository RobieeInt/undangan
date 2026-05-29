<?php

namespace App\Http\Controllers;

use App\Models\GuestCheckin;
use App\Models\Invitation;
use App\Models\InvitationGuest;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckinController extends Controller
{
    public function __construct(private QrCodeService $qrCode) {}

    /**
     * Show check-in dashboard for an invitation.
     */
    public function dashboard(Invitation $invitation)
    {
        $this->authorize('update', $invitation);

        // Block access for expired invitations
        if ($invitation->expires_at && \Carbon\Carbon::parse($invitation->expires_at)->isPast()) {
            return redirect()->route('dashboard')
                ->with('error', 'Undangan ini sudah kadaluarsa. Perpanjang paket untuk mengaktifkan kembali fitur check-in.');
        }

        return view('checkin.dashboard', compact('invitation'));
    }

    /**
     * Verify a QR token — called from camera scan.
     */
    public function verify(Request $request, string $token)
    {
        $guest = $this->qrCode->verifyToken($token);

        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'QR code tidak valid.'], 404);
        }

        $invitation = $guest->invitation;

        // Check if already checked in
        $existing = DB::table('guest_checkins')->where('guest_id', $guest->id)->first();
        if ($existing) {
            return response()->json([
                'success'      => false,
                'already_in'   => true,
                'guest_name'   => $guest->name,
                'checked_in_at'=> $existing->checked_in_at,
                'message'      => "Tamu {$guest->name} sudah check-in sebelumnya.",
            ]);
        }

        // Perform check-in
        $rsvp = DB::table('invitation_rsvps')
            ->where('guest_id', $guest->id)
            ->first();

        GuestCheckin::create([
            'invitation_id' => $invitation->id,
            'guest_id'      => $guest->id,
            'rsvp_id'       => $rsvp?->id,
            'checked_in_at' => now(),
            'checked_in_by' => auth()->user()?->name ?? 'Scanner',
            'ip_address'    => $request->ip(),
        ]);

        return response()->json([
            'success'    => true,
            'guest_name' => $guest->name,
            'seats'      => $guest->allocated_seats,
            'notes'      => $guest->notes,
            'message'    => "✓ {$guest->name} berhasil check-in!",
        ]);
    }
}
