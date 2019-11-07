<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftPlanStaffTemplate extends Model
{
  public function Pattern(){
    return $this->belongsTo(ShiftPattern::class, 'shift_pattern_id');
  }

  public function StaffPlan(){
    return $this->belongsTo(ShiftPlanStaff::class, 'shift_plan_staff_id');
  }

}
