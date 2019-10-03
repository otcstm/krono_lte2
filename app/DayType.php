<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Carbon\Carbon;

class DayType extends Model
{
  use SoftDeletes;

  public function showEndTime(){
    if($this->is_work_day){
      $st = Carbon::create($this->start_time);
      $st->addHours($this->dur_hour);
      $st->addMinutes($this->dur_minute);

      return $st->toTimeString();
    }

    return null;

  }


}
