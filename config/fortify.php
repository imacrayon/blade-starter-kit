<?php

use Laravel\Fortify\Features;

return [

    'lowercase_usernames' => true,

    'home' => '/app',

    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
        'passkeys' => 'passkeys',
    ],

    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
            // 'window' => 0
        ]),
        Features::passkeys([
            'confirmPassword' => true,
        ]),
    ],

];
