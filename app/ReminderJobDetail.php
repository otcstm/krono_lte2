<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReminderJobDetail extends Model
{
  public function User(){
    return $this->belongsTo(User::class);
  }
}
