<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Carbon\Carbon;

class DayType extends Model
{
  use SoftDeletes;

  public function showEndTime(){
    //if($this->is_work_day){
      $st = Carbon::create($this->start_time);
      $st->addHours($this->dur_hour);
      $st->addMinutes($this->dur_minute);

      $endtime=$st->toTimeString();
      // if($st->toTimeString()=='23:59:00'){
      //   $endtime = '24:00';
      // }
      return $endtime;
    //}
    //return null;
  }
  
  public function showEndTime24format($format){
    $st = Carbon::create($this->start_time);
    $st->addHours($this->dur_hour);
    $st->addMinutes($this->dur_minute);
    if(!$format){
      //$format = 'Y-m-d H:i:s';
      $format = 'Hi';
    }
    $endtime=$st;

    if($endtime->format('Hi')=='0000'){
      $replace = array(
        "H" => "24",
        "G" => "24",
        "i" => "00",
      );

      $endtime = date(str_replace(array_keys($replace),$replace,$format),       
        strtotime($st->addMinutes(-1))
      );
      return $endtime;
    }
    else{
      return $endtime->format('Hi');
    }
  }

  public function getTimeRange(){
    // if($this->is_work_day == true){
    //   $stime = new Carbon($this->start_time);
    //   $etime = new Carbon($this->start_time);
    //   $etime->addMinutes($this->total_minute);
    //   return $stime->format('Hi') . '-' . $etime->format('Hi');
    // } else {
    //   //return $this->description;
    //   return $stime->format('Hi') . '-' . $etime->format('Hi');
    // }
    
    $stime = new Carbon($this->start_time);
    $etime = new Carbon($this->start_time);
    $etime->addMinutes($this->total_minute);
    return $stime->format('Hi') . '-' . $etime->format('Hi');
  }


}
