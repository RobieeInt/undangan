<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class InvitationGift extends Model
{
    protected $fillable = [
        'invitation_id', 'type', 'bank_name', 'account_number',
        'account_name', 'qris_image', 'label', 'sort_order',
    ];
    public function invitation() { return $this->belongsTo(Invitation::class); }

    // Handles both storage path and full URL (used in previews)
    public function getQrisImageUrlAttribute(): ?string
    {
        if (!$this->qris_image) return null;
        if (str_starts_with($this->qris_image, 'http')) return $this->qris_image;
        return Storage::url($this->qris_image);
    }
}
