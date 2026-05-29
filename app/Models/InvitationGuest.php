<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class InvitationGuest extends Model
{
    protected $fillable = [
        'invitation_id', 'name', 'slug', 'phone',
        'qr_code', 'qr_token', 'notes', 'allocated_seats',
    ];

    public function invitation() { return $this->belongsTo(Invitation::class); }
    public function rsvp()       { return $this->hasOne(InvitationRsvp::class, 'guest_id'); }
    public function checkin()    { return $this->hasOne(GuestCheckin::class, 'guest_id'); }

    public function hasRsvped(): bool   { return $this->rsvp()->exists(); }
    public function hasCheckedIn(): bool { return $this->checkin()->exists(); }

    public function getPersonalUrlAttribute(): string
    {
        $invitation = $this->invitation;
        return url('/' . $invitation->slug . '?tamu=' . $this->slug);
    }
}
