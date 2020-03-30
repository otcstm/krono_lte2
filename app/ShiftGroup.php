<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftGroup extends Model
{
  public function Members(){
    return $this->hasMany(ShiftGroupMember::class, 'shift_group_id');
  }

  public function Planner(){
    return $this->belongsTo(User::class, 'planner_id');
  }

  public function Manager(){
    return $this->belongsTo(User::class, 'manager_id');
  }

  public function ShiftPlans(){
    return $this->hasMany(ShiftPlan::class, 'department');
  }

  public function ShiftPatterns()
  {
      return $this->belongsToMany(ShiftPattern::class);
  }

}
