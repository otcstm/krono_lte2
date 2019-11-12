<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftPlan extends Model
{

  protected $dates = ['plan_month'];

  public function StaffList(){
    return $this->hasMany(ShiftPlanStaff::class, 'shift_plan_id');
  }

  public function History(){
    return $this->hasMany(ShiftPlanHistory::class, 'shift_plan_id');
  }

  public function Creator(){
    return $this->belongsTo(User::class, 'creator_id');
  }

  public function Group(){
    return $this->belongsTo(ShiftGroup::class, 'department');
  }

}
