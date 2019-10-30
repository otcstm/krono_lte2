<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftPlanStaffTemplate extends Model
{
  public function Pattern(){
    return $this->belongsTo(ShiftPattern::class, 'shift_pattern_id');
  }
}
