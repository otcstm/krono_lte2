<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DayTag extends Model
{
    protected $dates = ['phdate', 'date',];

    public function usertbl()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
