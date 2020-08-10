<?php

namespace App\Http\Controllers\Admin;
use App\MaintenanceOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaintenanceOrderController extends Controller
{
  public function index(Request $req){

    $molist = MaintenanceOrder::all();
    // $molist = [];

      if($req->filled('searching')){
        //keluarkan result
        // dd('b1');
        $molist = $this->fetch($req);

      }

    return view('admin.maintenanceOrder', ['molists' => $molist]);

  }

  public function fetch(Request $req)
  {
    // dd('fetch');
    // explode(",", str_replace(' ','',$req->inputpersno)
    $fid = explode(",", str_replace(' ','',$req->inputid));
    $ftype = explode(",", str_replace(' ','',$req->inputType));
    $fstatus = explode(",", str_replace(' ','',$req->inputStatus));
    $fcc = explode(",", str_replace(' ','',$req->inputcc));
    $fcocd = explode(",", str_replace(' ','',$req->inputcocd));
    $fapprover = explode(",", str_replace(' ','',$req->inputapprver));

    $molist = MaintenanceOrder::query();
    if(isset($req->inputid)){
      $molist = $molist->whereIn('id',$fid);
    }
    if(isset($req->inputType)){
      $molist = $molist->whereIn('type',$ftype);
    }
    if(isset($req->inputStatus)){
      $molist = $molist->whereIn('status',$fstatus);
    }
    if(isset($req->inputcc)){
      $molist = $molist->whereIn('cost_center',$fcc);
    }
    if(isset($req->inputcocd)){
      $molist = $molist->whereIn('company_code',$fcocd);
    }
    if(isset($req->inputapprver)){
      $molist = $molist->whereIn('approver_id',$fapprover);
    }
    $molist = $molist->get();
    return $molist;
  }


}
