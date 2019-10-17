<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftPlan extends Model
{
  public function StaffList(){
    return $this->hasMany(ShiftPlanStaff::class, 'shift_plan_id');
  }

  public function History(){
    return $this->hasMany(ShiftPlanHistory::class, 'shift_plan_id');
  }

  public function Creator(){
    return $this->belongsTo(User::class, 'creator_id');
  }

}
