<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OvertimeDetail extends Model
{
    public function createdby()
    {
      return $this->belongsTo(Overtime::class, 'ot_id');
    }
}
