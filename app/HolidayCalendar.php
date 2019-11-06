<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HolidayCalendar extends Model
{

    //
    public function stateid()
    {
        return $this->belongsTo(State::class,'state_id')->withDefault(['state_descr' => 'NULL']);
    }
}
