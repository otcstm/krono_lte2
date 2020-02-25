<?php

namespace App\Api\Datamart;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\UserShiftPattern;
use \Carbon\Carbon;
use DateTime;

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

      $sp = ShiftPattern::where('code', $req->$shift_pattern_code)->first();
      $startDate = DateTime::createFromFormat('Ymd', $req->start_date);


      $usp->user_id           = $req->pernr;
      $usp->shift_pattern_id  = $req->$sp;

      $usp->start_date        = $req->start_date;
      $usp->end_date          = $req->end_date;
      $usp->sap_code          = $shift_pattern_code;
      $usp->created_by        = 0;
      $usp->source            = 'SAP' ;

      $usp->save();
      return {'persno':$usp->persno,
      'shift_pattern':$sp->description};
  }

  public function returnMaxDate()
  {
      $upd_sap = UserShiftPattern::max('upd_sap');

      return $upd_sap;

  }
}
