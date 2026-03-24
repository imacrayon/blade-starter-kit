<?php

namespace Database\Factories;

use App\Models\Invitation;
use App\Models\Team;
use App\Models\User;
use App\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invitation>
 */
class InvitationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'role' => UserRole::MEMBER,
            'team_id' => Team::factory(),
            'sender_id' => User::factory(),
        ];
    }
}
