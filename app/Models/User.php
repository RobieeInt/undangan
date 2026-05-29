<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'status', 'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────
    public function scopeActive($q)   { return $q->where('status', 'active'); }
    public function scopeAdmin($q)    { return $q->where('role', 'admin'); }

    // ─── Helpers ─────────────────────────────────────────────────────────────
    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isActive(): bool  { return $this->status === 'active'; }

    // ─── Relations ───────────────────────────────────────────────────────────
    public function invitations()     { return $this->hasMany(Invitation::class); }
    public function transactions()    { return $this->hasMany(Transaction::class); }
}
