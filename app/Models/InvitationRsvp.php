<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class InvitationRsvp extends Model
{
    protected $fillable = [
        'invitation_id', 'guest_id', 'name', 'phone',
        'attendance', 'guest_count', 'message', 'ip_address', 'user_agent',
    ];

    public function invitation() { return $this->belongsTo(Invitation::class); }
    public function guest()      { return $this->belongsTo(InvitationGuest::class, 'guest_id'); }
    public function checkin()    { return $this->hasOne(GuestCheckin::class, 'rsvp_id'); }

    public function getAttendanceLabelAttribute(): string
    {
        return match($this->attendance) {
            'hadir'        => 'Hadir',
            'tidak_hadir'  => 'Tidak Hadir',
            'mungkin'      => 'Mungkin Hadir',
            default        => '-',
        };
    }
}
