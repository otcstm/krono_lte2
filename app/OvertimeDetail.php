<?php

namespace App;
use App\Shared\URHelper;

use Illuminate\Database\Eloquent\Model;

class OvertimeDetail extends Model
{
    public function detail()
    {
      return $this->belongsTo(Overtime::class, 'ot_id');
    }
    public function mainOT()
    {
      return $this->belongsTo(Overtime::class, 'ot_id');
    }
    // public function Location(){//based on application date
    //   // $uid = $this->punch_in_time->format('Y-m-d H:i:s');
    //    // <td>{{ $otr->detail->Location()->in_latitude }} </td>
    //   return URHelper::getLocation($this->user_id,$this->clock_in);
    // }
    // public function starttime(){
    //   return $this->start_time;
    // }
}
