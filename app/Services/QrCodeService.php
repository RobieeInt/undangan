<?php

namespace App\Services;

use App\Models\InvitationGuest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate a QR code for a guest and store it.
     * Returns the storage path.
     */
    public function generateForGuest(InvitationGuest $guest): string
    {
        // Generate a secure random token
        $token = Str::random(64);

        // The QR code contains the check-in verification URL
        $checkInUrl = route('checkin.verify', ['token' => $token]);

        // Generate QR image as SVG
        $qrSvg = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($checkInUrl);

        $path = 'qrcodes/' . $guest->invitation_id . '/' . $guest->id . '.svg';

        Storage::disk('public')->makeDirectory('qrcodes/' . $guest->invitation_id);
        Storage::disk('public')->put($path, $qrSvg);

        // Update guest record
        $guest->update([
            'qr_token' => $token,
            'qr_code'  => $path,
        ]);

        return $path;
    }

    /**
     * Verify a QR token and return the guest if valid.
     */
    public function verifyToken(string $token): ?InvitationGuest
    {
        return InvitationGuest::where('qr_token', $token)->first();
    }

    /**
     * Bulk generate QR codes for all guests of an invitation.
     */
    public function generateBulk(int $invitationId): int
    {
        $guests = InvitationGuest::where('invitation_id', $invitationId)
            ->whereNull('qr_code')
            ->get();

        $count = 0;
        foreach ($guests as $guest) {
            $this->generateForGuest($guest);
            $count++;
        }

        return $count;
    }
}
