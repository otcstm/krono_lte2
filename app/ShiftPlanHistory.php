<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftPlanHistory extends Model
{
  public function ActionBy(){
    return $this->belongsTo(User::class, 'user_id');
  }
}
