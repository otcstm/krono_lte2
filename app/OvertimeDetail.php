<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OvertimeDetail extends Model
{
    public function detail()
    {
      return $this->belongsTo(Overtime::class, 'ot_id');
    }

    // public function starttime(){
    //   return $this->start_time; 
    // }
}
