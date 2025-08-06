<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImpersonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_impersonate_another_user(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create();
        $response = $this
            ->be($admin)
            ->post(route('admin.impersonation.store'), [
                'user_id' => $target->id,
            ]);
        $response->assertRedirect('/app');
        $this->assertAuthenticatedAs($target);
        $this->assertEquals($admin->id, session('impersonator_id'));
        $this->assertEquals($target->name, session('impersonating'));
    }

    public function test_impersonator_can_stop_impersonating(): void
    {
        $admin = User::factory()->admin()->create();
        $target = User::factory()->create();
        // Start impersonation
        $this
            ->actingAs($admin)
            ->post(route('admin.impersonation.store'), [
                'user_id' => $target->id,
            ]);
        $this->assertAuthenticatedAs($target);
        // Stop impersonation
        session(['impersonator_id' => $admin->id, 'impersonating' => $target->name]);
        $response = $this
            ->be($target)
            ->delete(route('impersonation.destroy'));
        $response->assertRedirect(route('admin.users.index'));
        $this->assertAuthenticatedAs($admin);
        $this->assertNull(session('impersonator_id'));
        $this->assertNull(session('impersonating'));
    }

    public function test_non_admin_cannot_impersonate(): void
    {
        $user = User::factory()->create();
        $target = User::factory()->create();
        $response = $this
            ->be($user)
            ->post(route('admin.impersonation.store'), [
                'user_id' => $target->id,
            ]);
        $response->assertForbidden();
    }

    public function test_cannot_stop_impersonating_if_not_impersonating(): void
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);
        $response = $this->delete(route('impersonation.destroy'));
        $response->assertNotFound();
    }
}
