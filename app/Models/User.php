<?php

namespace App\Models;

use App\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Scout\Searchable;

#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasTeams, MustVerifyEmail, Notifiable, Searchable, TwoFactorAuthenticatable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => trim($this->first_name.' '.$this->last_name),
        );
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn () => route('avatars.show', $this)
        )->shouldCache();
    }

    public function toSearchableArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
        ];
    }
}
