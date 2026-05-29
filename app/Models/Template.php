<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'name', 'slug', 'thumbnail', 'preview_url', 'description',
        'category', 'is_premium', 'is_exclusive', 'is_active', 'config',
        'usage_count', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_premium'   => 'boolean',
            'is_exclusive' => 'boolean',
            'is_active'    => 'boolean',
            'config'       => 'array',
        ];
    }

    public function scopeActive($q)     { return $q->where('is_active', true); }
    public function scopeFree($q)       { return $q->where('is_premium', false)->where('is_exclusive', false); }
    public function scopePremium($q)    { return $q->where('is_premium', true); }
    public function scopeOrdered($q)    { return $q->orderBy('sort_order'); }

    public function invitations()       { return $this->hasMany(Invitation::class); }

    public function isFree(): bool      { return !$this->is_premium && !$this->is_exclusive; }
    public function getTierLabel(): string
    {
        if ($this->is_exclusive) return 'Exclusive';
        if ($this->is_premium)   return 'Premium';
        return 'Basic';
    }
}
