<?php

namespace App\Models;

use App\Mail\InvitationMail;
use App\UserRole;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Invitation extends Model
{
    /** @use HasFactory<\Database\Factories\InvitationFactory> */
    use HasFactory;

    protected static $unguarded = true;

    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
        ];
    }

    protected static function booted()
    {
        static::creating(function ($invitation) {
            $invitation->code = $invitation->code ?: Str::uuid();
        });
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id')->withDefault();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function send()
    {
        Mail::to($this->email)->send(new InvitationMail($this));

        $this->touch();

        return $this;
    }

    public function url()
    {
        return route('register', ['code' => $this->code]);
    }

    public function accept(User $user)
    {
        return DB::transaction(function () use ($user) {
            if ($this->team_id) {
                $user->joinTeam($this->team);
            }

            return $this->delete();
        });
    }

    protected function name(): Attribute
    {
        return Attribute::make(fn ($value, $attributes) => strtok($attributes['email'], '@'))->shouldCache();
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => "https://unavatar.io/{$attributes['email']}?".http_build_query([
                'fallback' => "https://ui-avatars.com/api/{$this->name}/48/dbeafe/1e40af",
            ])
        )->shouldCache();
    }
}
