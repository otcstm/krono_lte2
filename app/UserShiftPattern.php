<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserShiftPattern extends Model
{
  public function shiftpattern(){
    return $this->belongsTo(ShiftPattern::class);
  }
}
