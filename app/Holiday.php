<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
  public function StatesThatCelebrateThis(){
    return $this->hasMany(HolidayCalendar::class, 'holiday_id');
  }
}
