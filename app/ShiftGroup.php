<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftGroup extends Model
{
  public function Members(){
    return $this->hasMany(ShiftGroupMember::class, 'shift_group_id');
  }
}
