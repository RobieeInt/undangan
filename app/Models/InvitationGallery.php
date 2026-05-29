<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class InvitationGallery extends Model
{
    protected $fillable = ['invitation_id', 'image', 'caption', 'sort_order'];
    public function invitation() { return $this->belongsTo(Invitation::class); }

    // Handles both storage path and full URL (used in previews)
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http')) return $this->image;
        return Storage::url($this->image);
    }
}
