<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GuestCheckin extends Model
{
    protected $fillable = [
        'invitation_id', 'guest_id', 'rsvp_id',
        'checked_in_at', 'checked_in_by', 'ip_address',
    ];
    protected function casts(): array { return ['checked_in_at' => 'datetime']; }

    public function invitation() { return $this->belongsTo(Invitation::class); }
    public function guest()      { return $this->belongsTo(InvitationGuest::class, 'guest_id'); }
    public function rsvp()       { return $this->belongsTo(InvitationRsvp::class, 'rsvp_id'); }
}
