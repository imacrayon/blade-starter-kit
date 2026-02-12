<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get(route('app'))->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $this->be($user = User::factory()->create());

    $this->get(route('app'))->assertStatus(200);
});