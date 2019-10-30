<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftPlanStaff extends Model
{
  public function User(){
    return $this->belongsTo(User::class);
  }

  public function Templates(){
    return $this->hasMany(ShiftPlanStaffTemplate::class, 'shift_plan_staff_id');
  }
}
