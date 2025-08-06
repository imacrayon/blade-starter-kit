<?php

namespace App\Models;

use App\UserRole;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasTeams, MustVerifyEmail, Notifiable, Searchable;

    protected static $unguarded = true;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => "https://unavatar.io/{$attributes['email']}?".http_build_query([
                'fallback' => "https://ui-avatars.com/api/{$this->name}/32/dbeafe/2563eb",
            ])
        )->shouldCache();
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
