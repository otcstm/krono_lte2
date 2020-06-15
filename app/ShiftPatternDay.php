<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftPatternDay extends Model
{
  public function Day(){
    return $this->belongsTo(DayType::class, 'day_type_id');
  }

  public function ShiftPattern(){
    return $this->belongsTo(ShiftPatternCompany::class, 'shift_pattern_id');
  }

}
