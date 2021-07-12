<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtIndicator extends Model
{
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
