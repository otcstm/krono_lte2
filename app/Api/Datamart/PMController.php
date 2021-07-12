<?php

namespace app\Api\Datamart;
//use App\Api\Datamart;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\MaintenanceOrder;

use Illuminate\Http\Request;

class PMController extends Controller
{

    public function insert(Request $req)
    {


      $exPMList = MaintenanceOrder::where('id', $req->order_no)->delete();

      $pm = new MaintenanceOrder;
      $pm->id                 = $req->order_no;
      $pm->descr              = $req->order_desc;
      $pm->type               = $req->order_type;
      $pm->status             = $req->order_stat;

      $pm->cost_center        = $req->order_cost_center;
      $pm->company_code       = $req->order_company_code;
      $pm->approver_id        = $req->otcs_approver;
      $pm->budget             = $req->order_budget;
      $pm->upd_dm             = $req->last_upd_dt;


      $pm->save();


      return $pm->id;

    }

    public function returnMaxDate()
    {
        $upd_dm = MaintenanceOrder::max('upd_dm');

        return $upd_dm;

    }




}
