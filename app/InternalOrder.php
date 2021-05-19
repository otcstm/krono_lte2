<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InternalOrder extends Model
{
    public $incrementing = false;
    public function name()
    {
        return $this->belongsTo(User::class, 'pers_responsible');
    }
}
