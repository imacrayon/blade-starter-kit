<?php

namespace App\Models;

use App\UserRole;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Membership extends Pivot
{
    public $incrementing = true;

    protected function role(): Attribute
    {
        return Attribute::get(fn ($value) => UserRole::from($value))->shouldCache();
    }
}
