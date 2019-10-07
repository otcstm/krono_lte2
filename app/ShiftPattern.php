<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftPattern extends Model
{
  public function ListDays(){
    return $this->hasMany(ShiftPatternDay::class, 'shift_pattern_id');
  }

  public function updateTotals(){
    $this->days_count = $this->ListDays->count();

    $totm = 0;
    foreach($this->ListDays as $aday){
      // dd($aday->Day);
      $totm += $aday->Day->total_minute;
    }

    $this->total_minutes = $totm;

    $minute_bal = $totm % 60;
    $minh = $totm - $minute_bal;
    $hourh = $minh / 60;
    $hourd = $minute_bal / 60;
    $this->total_hours = $hourh + $hourd;
    $this->save();

  }

}
