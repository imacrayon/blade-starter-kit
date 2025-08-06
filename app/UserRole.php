<?php

namespace App;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MEMBER = 'member';

    public function label()
    {
        return match ($this) {
            self::ADMIN => __('Admin'),
            self::MEMBER => __('Member'),
        };
    }

    public function description()
    {
        return match ($this) {
            self::ADMIN => __('Can view, add, and edit team members.'),
            self::MEMBER => __('Can view other team members.'),
        };
    }
}
