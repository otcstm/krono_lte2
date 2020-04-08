<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WsrChangeReq extends Model
{
  use SoftDeletes;

  public function shiftpattern(){
    return $this->belongsTo(ShiftPattern::class, 'shift_pattern_id');
  }

  public function requestor(){
    return $this->belongsTo(User::class, 'user_id');
  }

  public function approver(){
    return $this->belongsTo(User::class, 'superior_id');
  }
}

/*
 *  Statuses:
 *  Approved
 *  New
 *  Rejected
 */
