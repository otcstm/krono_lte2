<?php

namespace App\Http\Controllers\Admin;
use Session;
use App\Overtime;
use App\State;
use App\SetupCode;
use App\Company;
use App\OvertimeDetail;
use App\OvertimeLog;
use App\StaffPunch;

// use App\UserRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OtReportController extends Controller
{
  public function viewOT(Request $req)//OT Summary
  {
    $company = Company::all();
    $state = State::all();
    $region = SetupCode::where('item1', 'region')->get();
    $vlist = false;
    $ar_persno =[];


    if($req->filled('searching')){
      $vlist = true;
      $otr = $this->fetch($req);
      // dd($req);
      // dd($otr);

    }else{
      $otr = [];
      if($req->filled('fpersno')){
        $ar_persno= $req->fpersno;
      }
    }
    // dd($otr);
    // dd($req);
    return view('report.otr',['vlist'=>$vlist,'ar_persno'=> $ar_persno,'otrep' => $otr,'companies'=>$company,'states'=>$state,'regions'=>$region ]);
  }

  public function viewOTd(Request $req)//OT Detail
  {
    $company = Company::all();
    $state = State::all();
    $region = SetupCode::where('item1', 'region')->get();
    $vlist = false;
    $param = [];

    if($req->filled('searching')){
      $vlist = true;
      $otr = $this->fetch($req);
      if(isset($req->fdate)){
        array_push($param, ['fdate'=>$req->fdate,'tdate'=>$req->tdate, ]);
      }
      if(isset($req->fpersno)){
        array_push($param, ['fpersno'=>$req->fpersno, ]);
      }
      if(isset($req->frefno)){
        array_push($param, ['frefno'=>$req->frefno, ]);
      }
      if(isset($req->fapprover_id)){
        array_push($param, ['fapprover_id'=>$req->fapprover_id, ]);
      }
      if(isset($req->fverifier_id)){
        array_push($param, ['fverifier_id'=>$req->fverifier_id, ]);
      }
    }else{
      $otr = [];

    }

    return view('report.otrdetails',['vlist'=>$vlist,'otrep' => $otr, 'param'=> $param, 'companies'=>$company, 'states'=>$state,'regions'=>$region  ]);
  }

  public function fetch(Request $req)
  {
    $jenisrep = $req->searching;
    $fdate = $req->fdate;
    $tdate = $req->tdate;
    // $persno = $req->fpersno;
    $approver_id = $req->fapprover_id;
    $refno = $req->frefno;
        // $status = $req->fstatus;
    $verifier_id = $req->fverifier_id;
    $otr = [];
    $otdetail = [];
    $persno = explode(",", $req->fpersno);
    $company = explode(",", $req->fcompany);
    $state = explode(",", $req->fstate);
    $region = explode(",", $req->fregion);

    // dd($req);
    // $otreport = OvertimeDetail::query();
    $otr = Overtime::query();
    if(isset($req->fdate)){
      $otr = $otr->whereBetween('date', array($fdate, $tdate));
    }
    if(isset($req->fpersno)){
      $otr = $otr->whereIn('user_id',$persno);
    }
    if(isset($req->fcompany)){
      $otr = $otr->whereIn('company_id',$company);
    }
    if(isset($req->fstate)){
      $otr = $otr->whereIn('state_id',$state);
    }
    if(isset($req->fregion)){
      $otr = $otr->whereIn('region',$region);
    }
    if(isset($req->fapprover_id)){
      $otr = $otr->where('approver_id', 'LIKE', '%' .$approver_id. '%');
    }
    if(isset($req->frefno)){
      $otr = $otr->where('refno', 'LIKE', '%' .$refno. '%');
    }
    if(isset($req->fverifier_id)){
      $otr = $otr->where('verifier_id', 'LIKE', '%' .$verifier_id. '%');
    }
    if(isset($req->fapprover_id)){
      $otr = $otr->where('approver_id', 'LIKE', '%' .$approver_id. '%');
    }
    if(isset($req->fstatus)){
      $otr = $otr->where('status', 'LIKE', '%' .$status. '%');
    }
    $otr = $otr->get();

    if($jenisrep == 'detail'){
      $list_of_id = $otr->pluck('id');
      $otdetail = OvertimeDetail::whereIn('ot_id', $list_of_id)->where('checked','Y')->get();
      return $otdetail;
    }
    elseif ($jenisrep == 'main') {
      return $otr;

  }
}



  public function viewLC(Request $req)
  {
    $vlist = false;

    if($req->filled('searching')){
      $vlist = true;
      $otr = $this->fetchLC($req);

      $otid = $otr->pluck('ot_id');
      $justification = OvertimeDetail::whereIn('ot_id', $otid)
      ->where('checked','=','Y')
      ->get();

    }else{
      $otr = [];
      $justification = [];
    }

// dd($otid);
    return view('report.otLog',['vlist'=>$vlist,'otrep' => $otr,'otdetail' => $justification ]);
  }

  public function fetchLC(Request $req)
  {
    $fdate = $req->fdate;
    $tdate = $req->tdate;
    $persno = $req->fpersno;
    $refno = $req->frefno;
    $otr = [];
    $otdetail = [];

    $otr = Overtime::query();
    if(isset($req->fdate)){
      $otr = $otr->whereBetween('date', array($fdate, $tdate));
    }
    if(isset($req->fpersno)){
      $otr = $otr->where('user_id', 'LIKE', '%' .$persno. '%');
    }
    if(isset($req->frefno)){
      $otr = $otr->where('refno', 'LIKE', '%' .$refno. '%');
    }

    $otr = $otr->get();

      $list_of_id = $otr->pluck('id');
      $otlog = OvertimeLog::whereIn('ot_id', $list_of_id)
      ->where('action','not like',"%Created draft%")
      ->get();
      // $otdetail = OvertimeLog::whereIn('ot_id', $list_of_id)->where('checked','Y')->get();
      // dd($otdetail);
      return $otlog;

  }

public function viewStEd(Request $req)//List of Start/End OT Time (Punch)
{
  $vlist = false;

  if($req->filled('searching')){
    $vlist = true;
    $otPunch = $this->fetchStEd($req);
  }else{
    $otPunch = [];
  }
  return view('report.otSdEd',['vlist'=>$vlist,'otrep' => $otPunch,]);
}

public function fetchStEd(Request $req)
{
  $fdate = $req->fdate;
  $tdate = $req->tdate;
  $persno = $req->fpersno;

  $otr = StaffPunch::query();
  if(isset($req->fdate)){
    // dd($fdate,$tdate);
    $otr = $otr->whereBetween('punch_in_time', array($fdate, $tdate));
  }
  if(isset($req->fpersno)){
    $otr = $otr->where('user_id', 'LIKE', '%' .$persno. '%');
  }

  $otrStEd = $otr->get();

  return $otrStEd;

}



}
