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
  public function viewOT2(Request $req)//OT Summary
  {
    $company = Company::all();
    $state = State::all();
    $region = SetupCode::where('item1', 'region')->get();

    if($req->filled('searching')){
      $otr = $this->fetch($req);
      return view('report.otrList',['otrep' => $otr,]);
    }
    return view('report.otr',['companies'=>$company,'states'=>$state,'regions'=>$region ]);
  }

  public function viewOTd(Request $req)//OT Detail
  {
    $company = Company::all();
    $state = State::all();
    $region = SetupCode::where('item1', 'region')->get();
    if($req->filled('searching')){
      $otr = $this->fetch($req);
      return view('report.otrdetailsList',['otrep' => $otr, ]);
    }
    return view('report.otrdetails',[ 'companies'=>$company, 'states'=>$state,'regions'=>$region]);
  }

  public function fetch(Request $req)
  {
    $jenisrep = $req->searching;
    $fdate = $req->fdate;
    $tdate = $req->tdate;
    $approver_id = $req->fapprover_id;
    $refno = $req->frefno;
    $verifier_id = $req->fverifier_id;
    // $otr = [];
    // $otdetail = [];
    $persno = explode(",", $req->fpersno);//convert str to arry
    $company = $req->fcompany;
    $state = $req->fstate;
    $region = $req->fregion;

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
    $otr = $otr->where('status','not like',"%D%")->get();

    if($jenisrep == 'detail'){
      $list_of_id = $otr->pluck('id');
      $otdetail = OvertimeDetail::whereIn('ot_id', $list_of_id)->where('checked','Y')->get();
      return $otdetail;
    }
    elseif ($jenisrep == 'main'){
      return $otr;
    }
  }

  public function viewLC(Request $req)
  {
    if($req->filled('searching')){
      $otr = $this->fetchLC($req);
      $otid = $otr->pluck('ot_id');
      $justification = OvertimeDetail::whereIn('ot_id', $otid)->where('checked','=','Y')->get();
      return view('report.otLogList',['otrep' => $otr,'otdetail' => $justification ]);
    }
    return view('report.otLog',[]);
  }

  public function fetchLC(Request $req)
  {
    $fdate = $req->fdate;
    $tdate = $req->tdate;
    $refno = $req->frefno;
    $persno = explode(",", $req->fpersno);

    $otr = Overtime::query();
    if(isset($req->fdate)){
      $otr = $otr->whereBetween('date', array($fdate, $tdate));
    }
    if(isset($req->fpersno)){
      $otr = $otr->whereIn('user_id',$persno);
    }
    if(isset($req->frefno)){
      $otr = $otr->where('refno', 'LIKE', '%' .$refno. '%');
    }

    $otr = $otr->get();
    $list_of_id = $otr->pluck('id');
    $otlog = OvertimeLog::whereIn('ot_id', $list_of_id)->where('action','not like',"%Created draft%")->get();
    return $otlog;

  }

  public function viewStEd(Request $req)//List of Start/End OT Time (Punch)
  {
    $company = Company::all();
    $state = State::all();
    $region = SetupCode::where('item1', 'region')->get();

    if($req->filled('searching')){
      // dd($req);
      $otPunch = $this->fetchStEd($req);
      // dd($otPunch);
      return view('report.otSdEdList',['otrep' => $otPunch,]);
    }
    return view('report.otSdEd',['companies'=>$company, 'states'=>$state,'regions'=>$region ]);
  }

  public function fetchStEd(Request $req)
  {
    $fdate = $req->fdate;
    $tdate = $req->tdate;
    $persno = explode(",", $req->fpersno);
    $company =  $req->fcompany;
    $state =  $req->fstate;
    $region = $req->fregion;

    // dd($req);
    $otr = StaffPunch::query();
    if(isset($req->fdate)){
      // dd($fdate,$tdate);
      $otr = $otr->whereBetween('punch_in_time', array($fdate, $tdate));
    }
    if(isset($req->fpersno)){
      $otr = $otr->whereIn('user_id',$persno);
    }

    $otrStEd = $otr->get();

    foreach ($otrStEd as $key => $StEd){
      // cari profile user ni
      $rekodpengguna = $StEd->URecord;
      $rekodregion = $StEd->URecord->Reg;

      if($req->filled('fcompany')){
        // dd('ada input comp')
        if(in_array($rekodpengguna->company_id, $company)){
        } else {
          // dd($otrStEd);
        // if($rekodpengguna->company_id != $req->comp_no){
          unset($otrStEd[$key]);
          continue;
        }
      }
      if($req->filled('fstate')){
        if(in_array($rekodpengguna->state_id, $state)){
        } else {
          unset($otrStEd[$key]);
          continue;
        }
      }
      if($req->filled('fregion')){
        if(in_array($rekodregion->region, $region)){
        } else {
          unset($otrStEd[$key]);
          continue;
        }
      }

      $ot = \DB::table('overtimes')
      ->join('overtime_details','overtime_details.ot_id','=','overtimes.id')
      ->where('overtimes.user_id',$StEd->user_id)
      ->where('overtime_details.start_time',$StEd->punch_in_time)
      ->where('overtime_details.end_time',$StEd->punch_out_time)
      ->where('overtime_details.checked','Y')->first();

      if($ot){
          $StEd->ot_applied='Yes';
        }else{
          $StEd->ot_applied='No';
      }
      // dd($StEd);
    }
    return $otrStEd;
  }
}
