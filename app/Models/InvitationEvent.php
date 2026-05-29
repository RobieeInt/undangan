<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class InvitationEvent extends Model
{
    protected $fillable = [
        'invitation_id', 'name', 'date', 'time_start', 'time_end',
        'venue', 'venue_address', 'venue_maps_url', 'description', 'sort_order',
    ];
    protected function casts(): array { return ['date' => 'date']; }
    public function invitation() { return $this->belongsTo(Invitation::class); }

    public function getFormattedDateAttribute(): string
    {
        return $this->date->translatedFormat('l, d F Y');
    }
}
