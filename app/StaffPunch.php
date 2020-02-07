<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffPunch extends Model
{
  protected $dates = ['punch_in_time', 'punch_out_time'];

  public function detail()
  {
      return $this->hasMany(OvertimePunch::class, 'punch_id');
  }
}
