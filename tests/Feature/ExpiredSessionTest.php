<?php

namespace Tests\Feature;

use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ExpiredSessionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::post('expired-session', fn () => throw new TokenMismatchException('CSRF token mismatch.'))->middleware('web');
    }

    public function test_expired_session_redirects_back_with_a_status_message(): void
    {
        $this->from('/')
            ->post('expired-session')
            ->assertRedirect('/')
            ->assertSessionHas('status', 'Your session has expired, try again.');
    }

    public function test_expired_session_returns_json_to_json_requests(): void
    {
        $this->postJson('expired-session')
            ->assertStatus(419)
            ->assertJson(['message' => 'CSRF token mismatch.']);
    }
}
