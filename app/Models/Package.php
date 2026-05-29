<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'duration_days',
        'max_guests', 'max_gallery', 'has_watermark', 'has_analytics',
        'has_rsvp_export', 'has_custom_domain', 'has_all_templates',
        'has_qr_checkin', 'max_music', 'features', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price'              => 'integer',
            'duration_days'      => 'integer',
            'max_guests'         => 'integer',
            'max_gallery'        => 'integer',
            'max_music'          => 'integer',
            'sort_order'         => 'integer',
            'has_watermark'      => 'boolean',
            'has_analytics'      => 'boolean',
            'has_rsvp_export'    => 'boolean',
            'has_custom_domain'  => 'boolean',
            'has_all_templates'  => 'boolean',
            'has_qr_checkin'     => 'boolean',
            'is_active'          => 'boolean',
            'features'           => 'array',
        ];
    }

    public function scopeActive($q)  { return $q->where('is_active', true); }
    public function scopeOrdered($q) { return $q->orderBy('sort_order'); }

    public function invitations()    { return $this->hasMany(Invitation::class); }
    public function transactions()   { return $this->hasMany(Transaction::class); }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getDurationLabelAttribute(): string
    {
        if ($this->duration_days >= 365) return ($this->duration_days / 365) . ' Tahun';
        if ($this->duration_days >= 30)  return round($this->duration_days / 30) . ' Bulan';
        return $this->duration_days . ' Hari';
    }
}
