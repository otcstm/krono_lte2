<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftPlanStaffDay extends Model
{
  public function Day(){
    return $this->belongsTo(DayType::class, 'day_type_id');
  }

  public function StaffTemplate(){
    return $this->belongsTo(ShiftPlanStaffTemplate::class, 'shift_plan_staff_template_id');
  }

  public function ShiftPlan(){
    return $this->belongsTo(ShiftPlan::class, 'shift_plan_id');
  }
}
