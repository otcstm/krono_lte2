<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OvertimeLog extends Model
{
    public function name()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail()
    {
      return $this->belongsTo(Overtime::class, 'ot_id');
    }
}
