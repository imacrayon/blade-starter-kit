<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UserAvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_avatar_accessor_returns_local_proxy_url(): void
    {
        $user = User::factory()->create();

        $this->assertSame(route('avatars.show', $user), $user->avatar);
        $this->assertStringNotContainsString($user->email, $user->avatar);
    }

    public function test_avatar_proxy_returns_an_image_for_a_valid_user(): void
    {
        Http::fake([
            'unavatar.io/*' => Http::response('fake-image-bytes', 200, [
                'Content-Type' => 'image/png',
            ]),
        ]);

        $user = User::factory()->create();

        $response = $this->get(route('avatars.show', $user));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/png');
        $this->assertSame('fake-image-bytes', $response->content());
    }

    public function test_avatar_proxy_caches_the_fetched_image(): void
    {
        Http::fake([
            'unavatar.io/*' => Http::response('fake-image-bytes', 200, [
                'Content-Type' => 'image/png',
            ]),
        ]);

        $user = User::factory()->create();

        $this->get(route('avatars.show', $user))->assertOk();
        $this->get(route('avatars.show', $user))->assertOk();

        Http::assertSentCount(1);
    }

    public function test_avatar_proxy_returns_404_for_non_existent_user(): void
    {
        $this->get(route('avatars.show', 999))->assertNotFound();
    }
}
