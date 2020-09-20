<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewShiftPlanning extends Model
{
    //
    protected $table = 'v_shift_planning';

    public function StaffMember(){
        //return $this->hasOne('App\User', 'id','user_id');
        return $this->belongsTo(User::class, 'user_id');
    }

    public function GroupOwner(){
        //return $this->hasOne('App\User', 'id','manager_id');
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function ShiftPlanner(){
        //return $this->hasOne('App\User', 'id','planner_id');
        return $this->belongsTo(User::class, 'planner_id');
    }

    public function PlanApprover(){
        //return $this->hasOne('App\User', 'id','manager_id');
        return $this->belongsTo(User::class, 'manager_id');
    }
}
