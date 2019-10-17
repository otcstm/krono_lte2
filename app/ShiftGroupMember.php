<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftGroupMember extends Model
{
  public function User(){
    return $this->belongsTo(User::class, 'user_id');
  }
}
