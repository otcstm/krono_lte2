<?php

namespace App\Http\Controllers\Admin;
use App\MaintenanceOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use DB;

class MaintenanceOrderController extends Controller
{
  public function index(Request $req){

    // $molist = MaintenanceOrder::all();
    $molist = [];

      if($req->filled('searching')){
        //keluarkan result
        // dd('b1');
        $molist = $this->fetch($req);

      }

    return view('admin.maintenanceOrder', ['molists' => $molist]);

  }

  // public function mosearch(Request $req){
  //   $data = [];
  //   if($req->has('q')){
  //       $search = $req->q;
  //   $data = MaintenanceOrder::select("id")
  //   ->where('id','LIKE',"%$search%")
  //   ->get();
  // }
  //
  //   return response()->json($data);
  // }

  public function csearch(Request $req){
    $data = [];
    if($req->has('q')){
        $search = $req->q;
    $data = MaintenanceOrder::select("cost_center")
    ->where('cost_center','LIKE',"%$search%")
    ->distinct()
    ->get();
  }

    return response()->json($data);
  }

  public function tpsearch(Request $req){
    $data = [];
    if($req->has('q')){
        $search = $req->q;
    $data = MaintenanceOrder::select("type")
    ->where('type','LIKE',"%$search%")
    ->distinct()
    ->get();
  }

    return response()->json($data);
  }

  public function cocdsearch(Request $req){
    $data = [];
    if($req->has('q')){
        $search = $req->q;
    $data = MaintenanceOrder::select("company_code")
    ->where('company_code','LIKE',"%$search%")
    ->distinct()
    ->get();
  }

    return response()->json($data);
  }

  public function fetch(Request $req)
  {
    // dd('fetch');
    // explode(",", str_replace(' ','',$req->inputpersno)
    // $fid = explode(",", str_replace(' ','',$req->inputid));
    $fid = $req->inputid;
    // $ftype = explode(",", str_replace(' ','',$req->inputType));
    $ftype = $req->inputType;
    $fstatus = explode(",", str_replace(' ','',$req->inputStatus));
    // $fcc = explode(",", str_replace(' ','',$req->inputcc));
    $fcc = $req->inputcc;
    // $fcocd = explode(",", str_replace(' ','',$req->inputcocd));
    $fcocd = $req->inputcocd;
    $fapprover = explode(",", str_replace(' ','',$req->inputapprver));

    $molist = MaintenanceOrder::query();
    if(isset($req->inputid)){
      $molist = $molist->where('id','LIKE','%' .$fid. '%');
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
