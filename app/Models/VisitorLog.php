<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'invitation_id', 'ip_address', 'user_agent',
        'device_type', 'referrer', 'guest_slug', 'visited_at',
    ];
    protected function casts(): array { return ['visited_at' => 'datetime']; }

    public function invitation() { return $this->belongsTo(Invitation::class); }
}
