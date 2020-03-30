<?php

namespace app\Api\Datamart;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\OtIndicator;

class OtIndicatorController extends Controller
{


  public function insert(Request $req)
  {

    $exOtInd = OtIndicator::where('user_id', $req->pers_no)
    ->where('start_date',$req->start_date_ot_ind)->delete();

    $oti = new OtIndicator;

    $oti->user_id             = $req->pers_no;
    $oti->upd_sap             = $req->change_on_ot_ind;
    $oti->start_date          = $req->start_date_ot_ind;
    $oti->end_date            = $req->end_date_ot_ind;

    $oti->ot_hour_exception   = $req->ot_hour_exception;
    $oti->ot_salary_exception = $req->ot_salary_exception;
    $oti->allowance           = $req->allowance_amount;
    $oti->allowance           = $req->allowance_amount;
    $oti->upd_dm              = $req->last_upd_dt;
    $oti->save();

    return $oti->id;

  }

  public function returnMaxDate()
  {
      $upd_sap = OtIndicator::max('upd_sap');

      return $upd_sap;

  }




}
