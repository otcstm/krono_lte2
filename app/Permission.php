<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $incrementing = false;

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
