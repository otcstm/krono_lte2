<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WsrChangeReq;
use App\UserShiftPattern;
use App\ShiftPattern;
use App\DayType;
use \Carbon\Carbon;

class WorkSchedRuleController extends Controller
{
  public function wsrMainPage(Request $req){



    return view('staff.workschedule');
  }

  public function doEditWsr(Request $req){

  }

  public function myCalendar(Request $req){

  }

  public function teamCalendar(Request $req){

  }

  public function listChangeWsr(Request $req){

  }

  public function doApproveWsr(Request $req){

  }

  public function doRejectWsr(Request $req){

  }

  public function ApiGetWsrDays(Request $req){
    $datsp = ShiftPattern::find($req->id);
    $dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
    if($datsp){
      $retv = [];
      foreach($datsp->ListDays as $oneday){
        if($oneday->Day->is_work_day == true){
          $stime = new Carbon($oneday->Day->start_time);
          $etime = new Carbon($oneday->Day->start_time);
          $etime->addMinutes($oneday->Day->total_minute);
          array_push($retv, [
            'day' => $dowMap[$oneday->day_seq],
            'time' => $stime->toTimeString() . ' - ' . $etime->toTimeString()
          ]);
        } else {
          array_push($retv, [
            'day' => $dowMap[$oneday->day_seq],
            'time' => $oneday->Day->description
          ]);
        }

      }
      return $retv;
    } else {
      return [];
    }
  }
}
