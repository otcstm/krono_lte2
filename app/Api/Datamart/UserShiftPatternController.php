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
      

      $shift_pattern_code = $req->work_schedule;
      //check existing sp
      $exsp = ShiftPattern::where('code', $shift_pattern_code)->first();
      if($exsp){      }

      else{
        $descr = $req->work_schedule_descr;
        if(!$descr){$descr = "no descr given by SAP";} 
        $nsp = new ShiftPattern;
        $nsp->code = $shift_pattern_code;
        $nsp->description = $descr;
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

      
      $exusp = UserShiftPattern::where('user_id',$req->pers_no)->where('start_date',$startDate);
      $usp = new UserShiftPattern;
      $usp->user_id           = $req->pers_no;
      $usp->shift_pattern_id  = $sp->id;

      $usp->start_date        = $startDate;
      $usp->end_date          = $endDate;
      $usp->sap_code          = $shift_pattern_code;
      $usp->upd_sap           = $upd_sap;
      $usp->upd_dm            = $req->last_upd_dt;
      $usp->created_by        = 0;
      $usp->source            = 'SAP' ;

      $usp->save();
      $collection = ["user_id" => $usp->user_id, "shift_pattern" => $sp->code ];
      return $collection;


      ;
  }

  public function delUSPbyPersno(Request $req)
  {
      

      $persno = $req->pers_no;
  
     
      
      //$affectedRows = 
      UserShiftPattern::where('user_id',"=",$persno)->delete();
      
      
      //dd($affectedRows->toSql());

     // dd($$persno );

      return [
  
        'msg' => "USP recorcds for pers_no ".$persno." deleted",
        'ecode' => 200,
        'data' => [$persno]
      ];

  }

  public function returnMaxDate()
  {
      $upd_sap = UserShiftPattern::max('upd_sap');

      

      return $upd_sap;

  }
}
