<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftPlanStaff extends Model
{
  protected $dates = ['plan_month'];

  public function User(){
    return $this->belongsTo(User::class);
  }

  public function Templates(){
    return $this->hasMany(ShiftPlanStaffTemplate::class, 'shift_plan_staff_id');
  }

  public function ShiftPlan(){
    return $this->belongsTo(ShiftPlan::class, 'shift_plan_id');
  }

  public function updateSums(){
    $totdays = 0;
    $totminutes = 0;
    $tlist = $this->Templates;
    $tcount = $tlist->count();

    // recount the totals
    foreach ($tlist as $key => $value) {
      $cpattern = $value->Pattern;
      $totdays += $cpattern->days_count;
      $totminutes += $cpattern->total_minutes;

      // update the new end date using the last template
      if($value->day_seq == $tcount){
        $this->end_date = $value->end_date;
      }

      if($value->day_seq == 1){
        $this->start_date = $value->start_date;
      }
    }

    //update the totals
    $this->total_days = $totdays;
    $this->total_minutes = $totminutes;

    $this->save();

  }
}
