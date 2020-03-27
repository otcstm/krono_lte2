<?php

namespace app\Api\Datamart;
//use App\Api\Datamart;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\InternalOrder;

use Illuminate\Http\Request;

class IOController extends Controller
{

    public function insert(Request $req)
    {


      $exioList = InternalOrder::where('id', $req->int_order_number)->delete();

      $io = new InternalOrder;
      $io->id                 = $req->int_order_number;
      $io->descr              = $req->int_order_desc;
      $io->order_type               = $req->int_order_type;
      $io->status             = $req->int_order_status;

      $io->cost_center        = $req->int_order_cc;
      $io->company_code       = $req->int_order_cocode;
      $io->pers_responsible   = $req->int_order_pers;
      $io->budget             = $req->int_order_budget;
      $io->upd_dm             = $req->last_upd_dt;

      $io->save();



      return $io->id;

    }

    public function returnMaxDate()
    {
        $upd_dm = InternalOrder::max('upd_dm');

        return $upd_dm;

    }




}
