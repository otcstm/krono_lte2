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

  public function getTimeRange(){
    if($this->is_work_day == true){
      $stime = new Carbon($this->start_time);
      $etime = new Carbon($this->start_time);
      $etime->addMinutes($this->total_minute);
      return $stime->format('Hi') . '-' . $etime->format('Hi');
    } else {
      return $this->description;
    }
  }


}
