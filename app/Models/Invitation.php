<?php

namespace App\Models;

use App\Notifications\InvitationNotification;
use App\UserRole;
use Carbon\CarbonImmutable;
use Database\Factories\InvitationFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $code
 * @property string $email
 * @property UserRole $role
 * @property int|null $team_id
 * @property int $sender_id
 * @property CarbonImmutable $expires_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read string $name
 * @property-read string $avatar
 * @property-read User $sender
 * @property-read Team|null $team
 */
class Invitation extends Model
{
    /** @use HasFactory<InvitationFactory> */
    use HasFactory, MassPrunable;

    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'expires_at' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($invitation) {
            $invitation->code = $invitation->code ?: Str::random(64);
        });
    }

    /** @return Builder<static> */
    public function prunable(): Builder
    {
        return static::query()->wherePast('expires_at');
    }

    /** @param  Builder<static>  $query */
    #[Scope]
    protected function pending(Builder $query): void
    {
        $query->whereFuture('expires_at');
    }

    /** @return BelongsTo<User, $this> */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /** @return BelongsTo<Team, $this> */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $field === 'code' ? static::findPendingByCode($value) : parent::resolveRouteBinding($value, $field);
    }

    public static function findPendingByCode(mixed $code): ?static
    {
        return is_string($code) ? static::with('team')->pending()->firstWhere('code', $code) : null;
    }

    public function send(User $sender): static
    {
        $this->sender()->associate($sender);
        $this->expires_at = now()->addWeek();
        $this->save();

        Notification::route('mail', $this->email)
            ->notify(new InvitationNotification($this));

        return $this;
    }

    public function url(): string
    {
        return route('teams.invitations.show', $this);
    }

    public function accept(User $user): ?bool
    {
        return DB::transaction(function () use ($user) {
            if ($this->team) {
                $user->joinTeam($this->team, $this->role);
            }

            return $this->delete();
        });
    }

    /** @return Attribute<string, never> */
    protected function name(): Attribute
    {
        return Attribute::make(fn ($value, $attributes) => strtok($attributes['email'], '@'))->shouldCache();
    }

    /** @return Attribute<string, never> */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => "https://ui-avatars.com/api/{$this->name}/48/dbeafe/1e40af"
        )->shouldCache();
    }
}
