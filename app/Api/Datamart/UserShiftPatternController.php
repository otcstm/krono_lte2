<?php

namespace App\Api\Datamart;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\UserShiftPattern;
use App\ShiftPattern;
use \Carbon\Carbon;
use DateTime;
use App\CustomCollection;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;

class UserShiftPatternController extends Controller
{
  public function insert(Request $req)
  {
      $usp = new UserShiftPattern;

      $shift_pattern_code = $req->work_schedule;
      //check existing sp
      $exsp = ShiftPattern::where('code', $req->$shift_pattern_code)->first();
      if($exsp){      }

      else{
        $nsp = new ShiftPattern;
        $nsp->code = $shift_pattern_code;
        $nsp->description = $req->work_schedule_descr;
        $nsp->is_weekly     = 0;
        $nsp->created_by    = 0;
        $nsp->days_count    = 0;
        $nsp->total_hours   = 0;
        $nsp->total_minutes = 0;
        $nsp->is_weekly     = 0;
        $nsp->source        = 'SAP';
        $nsp->save();
      }

      $sp = ShiftPattern::where('code', $shift_pattern_code)->first();
      $startDate = DateTime::createFromFormat('Ymd H:i:s', $req->start_date .' 00:00:00');
      $endDate = DateTime::createFromFormat('Ymd H:i:s', $req->end_date .' 00:00:00');
      $upd_sap = DateTime::createFromFormat('Ymd H:i:s', $req->change_on .' 00:00:00');




      $usp->user_id           = $req->pers_no;
      $usp->shift_pattern_id  = $sp->id;

      $usp->start_date        = $startDate;
      $usp->end_date          = $endDate;
      $usp->sap_code          = $shift_pattern_code;
      $usp->upd_sap           = $upd_sap;

      $usp->created_by        = 0;
      $usp->source            = 'SAP' ;

      $usp->save();
      $collection = ["user_id" => $usp->user_id, "shift_pattern" => $sp->code ];
      return $collection;


      ;
  }

  public function returnMaxDate()
  {
      $upd_sap = UserShiftPattern::max('upd_sap');

      return $upd_sap;

  }
}
