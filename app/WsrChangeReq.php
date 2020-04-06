<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WsrChangeReq extends Model
{
  use SoftDeletes;

  public function shiftpattern(){
    return $this->belongsTo(ShiftPattern::class);
  }

  public function requestor(){
    return $this->belongsTo(User::class);
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
