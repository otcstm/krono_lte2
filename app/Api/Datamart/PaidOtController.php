<?php

namespace app\Api\Datamart;

use App\Http\Controllers\Controller;

use App\PaidOt;
use Illuminate\Http\Request;

use DateTime;
use \Carbon\Carbon;

class PaidOtController extends Controller
{
  public function insert(Request $req)
  {


    $period_dt = DateTime::createFromFormat('Ym', $req->for_period);

    $exPaidOTList = PaidOt::where('user_id', $req->pers_no)
      ->where('period', $req->for_period)
      ->where('wagetype', $req->wage_type)
      ->delete();

    $po = new PaidOt;
    $po->user_id            = $req->pers_no;
    $po->period             = $req->for_period;

    $po->period_dt          = $period_dt;

    $po->pay_date           = $req->payment_date;

    $po->wagetype           = $req->wage_type;
    $po->wage_descr         = $req->wage_descr;
    $po->amount             = $req->amount;
    $po->upd_dm             = $req->last_upd_dt;
    $po->save();


    return $po->id;
  }
}
