<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Invitation extends Model
{
    protected $fillable = [
        'user_id', 'template_id', 'package_id', 'transaction_id',
        'slug', 'custom_domain',
        'groom_name', 'bride_name', 'groom_full_name', 'bride_full_name',
        'groom_father', 'groom_mother', 'bride_father', 'bride_mother',
        'groom_photo', 'bride_photo', 'cover_photo',
        'opening_quote', 'opening_quote_source', 'story',
        'music_url', 'music_name', 'music_autoplay',
        'is_published', 'is_active', 'is_open',
        'activated_at', 'expires_at', 'rsvp_deadline',
        'view_count', 'meta', 'theme',
    ];

    protected function casts(): array
    {
        return [
            'is_published'   => 'boolean',
            'is_active'      => 'boolean',
            'is_open'        => 'boolean',
            'music_autoplay' => 'boolean',
            'activated_at'   => 'datetime',
            'expires_at'     => 'datetime',
            'rsvp_deadline'  => 'date',
            'meta'           => 'array',
            'theme'          => 'array',
        ];
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────
    public function scopeActive($q)    { return $q->where('is_active', true); }
    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeExpired($q)   { return $q->where('expires_at', '<', now()); }
    public function scopeExpiring($q, int $days = 14)
    {
        return $q->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────
    public function isExpired(): bool  { return $this->expires_at && $this->expires_at->isPast(); }
    public function isValid(): bool    { return $this->is_active && !$this->isExpired(); }

    public function getPublicUrlAttribute(): string
    {
        return url('/' . $this->slug);
    }

    public function getCoupleName(): string
    {
        return $this->groom_name . ' & ' . $this->bride_name;
    }

    public function getDaysLeftAttribute(): int
    {
        if (!$this->expires_at) return 0;
        return max(0, (int) now()->diffInDays($this->expires_at, false));
    }

    // ─── Photo URL accessors (handles both storage paths and full URLs) ───────
    public function getCoverPhotoUrlAttribute(): ?string  { return $this->mediaUrl($this->cover_photo); }
    public function getGroomPhotoUrlAttribute(): ?string  { return $this->mediaUrl($this->groom_photo); }
    public function getBridePhotoUrlAttribute(): ?string  { return $this->mediaUrl($this->bride_photo); }

    private function mediaUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        return Storage::url($path);
    }

    // ─── Relations ───────────────────────────────────────────────────────────
    public function user()        { return $this->belongsTo(User::class); }
    public function template()    { return $this->belongsTo(Template::class); }
    public function package()     { return $this->belongsTo(Package::class); }
    public function transaction() { return $this->belongsTo(Transaction::class); }
    public function galleries()   { return $this->hasMany(InvitationGallery::class)->orderBy('sort_order'); }
    public function events()      { return $this->hasMany(InvitationEvent::class)->orderBy('sort_order'); }
    public function guests()      { return $this->hasMany(InvitationGuest::class); }
    public function rsvps()       { return $this->hasMany(InvitationRsvp::class)->latest(); }
    public function gifts()       { return $this->hasMany(InvitationGift::class)->orderBy('sort_order'); }
    public function visitorLogs() { return $this->hasMany(VisitorLog::class); }
}
