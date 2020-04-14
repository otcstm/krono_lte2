<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use \DB;
use App\Overtime;
use App\State;
use App\SetupCode;
use App\Company;
use App\OvertimeDetail;
use App\OvertimeLog;
use App\StaffPunch;
use \Carbon\Carbon;
use App\ExcelHandler;


class OtReport2Controller extends Controller
{
  public function viewOT(Request $req)//OT Summary
  {
    set_time_limit(0);
    $company = Company::all();
    $state = State::all();
    $region = SetupCode::where('item1', 'region')->get();

    if($req->filled('searching')){
      //view
      if($req->searching == 'main'){
        $pilihcol = $req->cbcol;
        $otr = $this->fetch($req);
        return view('report.otrList',['otrep' => $otr,'cbcolumn'=>$pilihcol]);
      }
      //download
      elseif ($req->searching == 'excelm'){
        return $this->doOTExcel($req);
      }
    }
    //index
      return view('report.otr',['companies'=>$company,'states'=>$state,'regions'=>$region ]);
  }

  public function viewOTd(Request $req)//OT Detail
  {
    // dd('here');
    set_time_limit(0);
    $company = Company::all();
    $state = State::all();
    $region = SetupCode::where('item1', 'region')->get();
    if($req->filled('searching')){
      if($req->searching == 'detail'){
      $pilihcol = $req->cbcol;
      $otr = $this->fetch($req);
      return view('report.otrdetailsList',['otrep' => $otr,'cbcolumn'=>$pilihcol]);
      }
      elseif ($req->searching == 'exceld'){
        return $this->doOTExcel($req);
      }
    }
    return view('report.otrdetails',[ 'companies'=>$company, 'states'=>$state,'regions'=>$region]);
  }

  public function viewStEd(Request $req)//List of Start/End OT Time (Punch)
  {
    set_time_limit(0);
    $company = Company::all();
    $state = State::all();
    $region = SetupCode::where('item1', 'region')->get();
    if($req->filled('searching')){
      if($req->searching == 'StEt'){
      $pilihcol = $req->cbcol;
      $otPunch = $this->fetchSE($req);
      // dd($otPunch);
      return view('report.otSdEdList',['otrep' => $otPunch,'cbcolumn'=>$pilihcol]);
      }
      elseif ($req->searching == 'excelSE'){
        return $this->doSEExcel($req);
      }
    }
    return view('report.otSdEd',['companies'=>$company, 'states'=>$state,'regions'=>$region ]);
  }

  public function viewLC(Request $req)
  {
    set_time_limit(0);

    if($req->filled('searching')){
      if($req->searching == 'log'){
        $otr = $this->fetchLog($req);
        $otid = $otr->pluck('ot_id');
        $justification = OvertimeDetail::whereIn('ot_id', $otid)->where('checked','=','Y')->get();
        return view('report.otLogList',['otrep' => $otr,'otdetail' => $justification ]);
      }elseif($req->searching == 'excellog'){
        return $this->doLogExcel($req);
      }
    }
    return view('report.otLog',[]);
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

  public function fetchSE(Request $req)
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
          // echo("$rekodpengguna->user_id,$rekodpengguna->company_id ,company true <br/>");
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
    // dd('stop');
    return $otrStEd;
  }

  public function fetchLog(Request $req)
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

  public function doLogExcel(Request $req)
  {
    set_time_limit(0);
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

    $otid = $otlog->pluck('ot_id');
    $just = OvertimeDetail::whereIn('ot_id', $otid)->where('checked','=','Y')->get();

    // dd($just,$otid);
    // return $otlog;
    $fdt = new Carbon($req->fdate);
    $tdt = new Carbon($req->tdate);
    $fname = 'OTLogChanges_'.$fdt->format('Ymd').'to'
    .$tdt->format('Ymd'). '.xlsx';

    $headers = ['Reference Number','Personnel Number','Employee Name','IC Number','Staff ID',
    'Action Date','Action Time','Action By','Action Log','Remarks'];
    $otdata = [];
    $eksel = new ExcelHandler($fname);


    foreach($otlog as $value){
      // dd($value);
      $urekod = $value->detail->URecord;
      $detail = $value->detail;
      $cdate = date('d.m.Y', strtotime($value->created_at));
      $ctime = date('H:i:s', strtotime($value->created_at));
      $msgarry= [];
      foreach($just as $justi){
        if($justi->ot_id==$value->ot_id){
          $msg = $justi->justification;
          array_push($msgarry,$msg);
        }
      }
      // dd($msgarry);


      if($value->action =='Submitted'){
        $remark= "Submitted with justification :";
      }else{
        $remark=$value->message;
      }
      // dd($act);
      $info = [
        $detail->refno,
        $detail->user_id,
        $urekod->name,
        $urekod->new_ic,
        $urekod->staffno,
        $cdate,
        $ctime,
        $value->user_id,
        $value->action,
        $remark];

        array_push($otdata, $info);
    }
    $eksel->addSheet('OTLogChanges', $otdata, $headers);
    return $eksel->download();
  }

  public function doSEExcel(Request $req)
  {
    set_time_limit(0);
    $fdate = $req->fdate;
    $tdate = $req->tdate;
    $pilihcol = $req->cbcol;
    $persno = explode(",", $req->fpersno);//convert str to arry
    $company = $req->fcompany;
    $state = $req->fstate;
    $region = $req->fregion;

    // dd($pilihcol,'here');

    $otr = StaffPunch::query();
    if(isset($req->fdate)){
      $otr = $otr->whereBetween('punch_in_time', array($fdate, $tdate));
    }
    if(isset($req->fpersno)){
      $otr = $otr->whereIn('user_id',$persno);
    }

    $otrStEd = $otr->get();

    foreach ($otrStEd as $key => $StEd){
      // cari profile user ni
      // dd($otrStEd);
      $rekodpengguna = $StEd->URecord;
      $rekodregion = $StEd->URecord->Reg;

      if($req->filled('fcompany')){
        // dd('ada input comp')
        if(in_array($rekodpengguna->company_id, $company)){
          // echo("$rekodpengguna->user_id,$rekodpengguna->company_id ,company true <br/>");
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

    // dd('stop');
    // return $otrStEd;

    $fdt = new Carbon($req->fdate);
    $tdt = new Carbon($req->tdate);
    $fname = 'StartEndOTTime'.$fdt->format('Ymd').'to'
    .$tdt->format('Ymd'). '.xlsx';

    $headers = ['Personnel Number','Employee Name','IC Number','Staff ID','Company Code','Date','Start Time','End Time'];
    if(isset($pilihcol)){
      if(in_array('psarea',$pilihcol ))
        {
          array_push($headers, 'Personnel Area');
        }
        if(in_array( 'psbarea',$pilihcol))
        {
          array_push($headers, 'Personnel Subarea');
        }
        if(in_array( 'state',$pilihcol))
        {
          array_push($headers, 'State');
        }
        if(in_array( 'region',$pilihcol))
        {
          array_push($headers, 'Region');
        }
        if(in_array( 'empgrp',$pilihcol))
        {
          array_push($headers, 'Employee Group');
        }
        if(in_array( 'empsubgrp',$pilihcol))
        {
          array_push($headers, 'Employee Subgroup');
        }
        if(in_array( 'dytype',$pilihcol))
        {
          array_push($headers, 'Day Type');
        }
        if(in_array( 'loc',$pilihcol))
        {
          array_push($headers, 'Location');
        }
        if(in_array( 'claim',$pilihcol))
        {
          array_push($headers, 'Apply OT Claim?');
        }
    }
    // dd($headers,$otrStEd);
    $otdata = [];
    $eksel = new ExcelHandler($fname);
    // dd($otrStEd);
    foreach ($otrStEd as $value) {
      $urekod = $value->URecord;
      $pdt = new Carbon($value->punch_in_time);
      $pdt = $pdt->format('d.m.Y');
      $pi = new Carbon($value->punch_in_time);
      $pi = $pi->format('H:i:s');
      $po = new Carbon($value->punch_out_time);
      $po = $po->format('H:i:s');

      $info = [$value->user_id,$urekod->name,$urekod->new_ic,
      $urekod->staffno,$urekod->company_id,$pdt,$pi,$po];
      if(isset($pilihcol)){
        if(in_array('psarea',$pilihcol ))
          {
            array_push($info, $urekod->persarea);
          }
          if(in_array( 'psbarea',$pilihcol))
          {
            array_push($info, $urekod->perssubarea);
          }
          if(in_array( 'state',$pilihcol))
          {
            array_push($info, $urekod->state_id);
          }
          if(in_array( 'region',$pilihcol))
          {
            array_push($info, $urekod->region);
          }
          if(in_array( 'empgrp',$pilihcol))
          {
            array_push($info, $urekod->empgroup);
          }
          if(in_array( 'empsubgrp',$pilihcol))
          {
            array_push($info, $urekod->empsgroup);
          }
          if(in_array( 'dytype',$pilihcol))
          {
            array_push($info, $value->day_type);
          }
          if(in_array( 'loc',$pilihcol))
          {
            array_push($info, '('.$value->in_latitude.','.$value->in_longitude.')');
          }
          if(in_array( 'claim',$pilihcol))
          {
            array_push($info, $value->ot_applied);
          }
      }
      array_push($otdata, $info);
    }
    $eksel->addSheet('StartEndOTTime', $otdata, $headers);
    return $eksel->download();

  }


  public function doOTExcel(Request $req)
  {
    set_time_limit(0);
    $fdate = $req->fdate;
    $tdate = $req->tdate;
    $approver_id = $req->fapprover_id;
    $refno = $req->frefno;
    $verifier_id = $req->fverifier_id;
    $pilihcol = $req->cbcol;
    $persno = explode(",", $req->fpersno);//convert str to arry
    $company = $req->fcompany;
    $state = $req->fstate;
    $region = $req->fregion;
    // Log::info('sebelum query');

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

      // dd($otr);
      // Log::info('get otr');

      if($req->searching == 'exceld'){
          $list_of_id = $otr->pluck('id');
          $otdetail = OvertimeDetail::whereIn('ot_id', $list_of_id)->where('checked','Y')->get();
          $fn ='OTDetails';
        }
      elseif ($req->searching == 'excelm'){
          $fn ='OTSummary';
        }

      // Log::info('lepas OTD/S');

      $fdt = new Carbon($req->fdate);
      $tdt = new Carbon($req->tdate);
      $fname = $fn.'_'.$fdt->format('Ymd').'to'
      .$tdt->format('Ymd'). '.xlsx';

      if($req->searching == 'exceld'){
        $headers = ['Personnel Number','Employee Name','IC Number','Staff ID',
        'Company Code','Reference Number','OT Date','Start Time','End Time'];
      }else {
        $headers = ['Personnel Number','Employee Name','IC Number','Staff ID','Company Code','Reference Number','OT Date'];
      }
      //header
      if(isset($pilihcol)){
        if(in_array('psarea',$pilihcol ))
          {
            array_push($headers, 'Personnel Area');
          }
          if(in_array( 'psbarea',$pilihcol))
          {
            array_push($headers, 'Personnel Subarea');
          }
          if(in_array( 'state',$pilihcol))
          {
            array_push($headers, 'State');
          }
          if(in_array( 'region',$pilihcol))
          {
            array_push($headers, 'Region');
          }
          if(in_array( 'empgrp',$pilihcol))
          {
            array_push($headers, 'Employee Group');
          }
          if(in_array( 'empsubgrp',$pilihcol))
          {
            array_push($headers, 'Employee Subgroup');
          }
          if(in_array( 'salexp',$pilihcol))
          {
            array_push($headers, 'Salary Exception');
          }
          if(in_array( 'capsal',$pilihcol))
          {
            array_push($headers, 'Capping Salary (RM)');
          }
          if(in_array( 'empst',$pilihcol))
          {
            array_push($headers, 'Employment Status');
          }
          if(in_array( 'mflag',$pilihcol))
          {
            array_push($headers, 'Manual Flag');
          }
          if(in_array( 'dytype',$pilihcol))
          {
            array_push($headers, 'Day Type');
          }
          if(in_array( 'loc',$pilihcol))
          {
            array_push($headers, 'Location');
          }
          if(in_array( 'trnscd',$pilihcol))
          {
            array_push($headers, 'Transaction Code');
          }
          if(in_array( 'estamnt',$pilihcol))
          {
            array_push($headers, 'Estimated Amount');
          }
          if(in_array( 'clmstatus',$pilihcol))
          {
            array_push($headers, 'Claim Status');
          }
          if(in_array( 'chrtype',$pilihcol))
          {
            array_push($headers, 'Charge Type');
          }
          if(in_array( 'noh',$pilihcol))
          {
            array_push($headers, 'Number of Hours');
          }
          if(in_array( 'nom',$pilihcol))
          {
            array_push($headers, 'Number of Minutes');
          }
          if(in_array( 'jst',$pilihcol))
          {
            array_push($headers, 'Justification');
          }
          if(in_array( 'bodycc',$pilihcol))
          {
            array_push($headers, 'Body Cost Center');
          }
          if(in_array( 'othrcc',$pilihcol))
          {
            array_push($headers, 'Other Cost Center');
          }
          if(in_array( 'prtype',$pilihcol))
          {
            array_push($headers, 'Project Type');
          }
          if(in_array( 'pnumbr',$pilihcol))
          {
            array_push($headers, 'Project Number');
          }
          if(in_array( 'ntheadr',$pilihcol))
          {
            array_push($headers, 'Network Header');
          }
          if(in_array( 'ntact',$pilihcol))
          {
            array_push($headers, 'Network Activity');
          }
          if(in_array( 'ordnum',$pilihcol))
          {
            array_push($headers, 'Order Number');
          }
          if(in_array( 'tthour',$pilihcol))
          {
            array_push($headers, 'Total Hours');
          }
          if(in_array( 'ttlmin',$pilihcol))
          {
            array_push($headers, 'Total Minutes');
          }
          if(in_array( 'appdate',$pilihcol))
          {
            array_push($headers, 'Application Date');
          }
          if(in_array( 'verdate',$pilihcol))
          {
            array_push($headers, 'Verification Date');
          }
          if(in_array( 'verid',$pilihcol))
          {
            array_push($headers, 'Verifier');
          }
          if(in_array( 'appdate',$pilihcol))
          {
            array_push($headers, 'Approval Date');
          }
          if(in_array( 'apprvrid',$pilihcol))
          {
            array_push($headers, 'Approver');
          }
          if(in_array( 'qrdate',$pilihcol))
          {
            array_push($headers, 'Queried Date');
          }
          if(in_array( 'qrdby',$pilihcol))
          {
            array_push($headers, 'Queried By');
          }
          if(in_array( 'pydate',$pilihcol))
          {
            array_push($headers, 'Payment Date');
          }
      }
      // dd($headers);
      // Log::info('siap buat header');
      $otdata = [];
      $eksel = new ExcelHandler($fname);
      // Log::info('init file excel');

    if($req->searching == 'excelm'){
      foreach($otr as $value){

        $urekod = $value->URecord;
          if($urekod->ot_salary_exception=='X'){
            // $value->ot_hour_exception='Yes';
            $sal_exception='Yes';
            $salarycap='';
          }else{
            // $value->ot_hour_exception='No';
            $sal_exception='No';
            $salarycap=$value->SalCap()->salary_cap;
          }

        $otdt = new Carbon($value->date);
        $otdt = $otdt->format('d.m.Y');
        $dtype = $value->daytype->description;
        $cdt = new Carbon($value->created_at);
        $cdt = $cdt->format('d.m.Y');

        if( $value->verification_date == ''){
            $ver_date = '';
          }else{
            $ver_date = date('d.m.Y H:i:s', strtotime($value->verification_date));
          }

          if( $value->approved_date == ''){
            $appvl_date = '';
          }else{
            $appvl_date = date('d.m.Y H:i:s', strtotime($value->approved_date));
          }

          if( $value->queried_date == ''){
            $queried_date ='';
          }else{
            $queried_date =date('d.m.Y H:i:s', strtotime($value->queried_date));
          }

          if( $value->payment_date == ''){
            $payment_date ='';
          }else{
            $payment_date =date('d.m.Y', strtotime($value->payment_date));
          }

        $info = [$value->user_id,$urekod->name,$urekod->new_ic,$urekod->staffno,$urekod->company_id,$value->refno,$otdt];
        // dd($pilihcol);
        if(isset($pilihcol)){
          if(in_array('psarea',$pilihcol ))
          {
            array_push($info, $urekod->persarea);
          }
          if(in_array( 'psbarea',$pilihcol))
          {
            array_push($info, $urekod->perssubarea);
          }
          if(in_array( 'state',$pilihcol))
          {
            array_push($info, $value->state_id);
          }
          if(in_array( 'region',$pilihcol))
          {
            array_push($info, $value->region);
          }
          if(in_array( 'empgrp',$pilihcol))
          {
            array_push($info, $urekod->empgroup);
          }
          if(in_array( 'empsubgrp',$pilihcol))
          {
            array_push($info, $urekod->empsgroup);
          }
          if(in_array( 'salexp',$pilihcol))
          {
            array_push($info, $sal_exception);
          }
          if(in_array( 'capsal',$pilihcol))
          {
            array_push($info, $salarycap);
          }
          if(in_array( 'empst',$pilihcol))
          {
            array_push($info, $urekod->empstats);
          }
          if(in_array( 'dytype',$pilihcol))
          {
            array_push($info, $dtype);
          }
          if(in_array( 'trnscd',$pilihcol))
          {
            array_push($info, $value->wage_type);
          }
          if(in_array( 'estamnt',$pilihcol))
          {
            array_push($info, $value->amount);
          }
          if(in_array( 'clmstatus',$pilihcol))
          {
            array_push($info, $value->OTStatus()->item3);
          }
          if(in_array( 'chrtype',$pilihcol))
          {
            array_push($info, $value->charge_type);
          }
          if(in_array( 'bodycc',$pilihcol))
          {
            array_push($info, $value->costcenter);
          }
          if(in_array( 'othrcc',$pilihcol))
          {
            array_push($info, $value->other_costcenter);
          }
          if(in_array( 'prtype',$pilihcol))
          {
            array_push($info, $value->project_type);
          }
          if(in_array( 'pnumbr',$pilihcol))
          {
            array_push($info, $value->project_no);
          }
          if(in_array( 'ntheadr',$pilihcol))
          {
            array_push($info, $value->network_header);
          }
          if(in_array( 'ntact',$pilihcol))
          {
            array_push($info, $value->network_act_no);
          }
          if(in_array( 'ordnum',$pilihcol))
          {
            array_push($info, $value->order_no);
          }
          if(in_array( 'tthour',$pilihcol))
          {
            array_push($info, $value->total_hour);
          }
          if(in_array( 'ttlmin',$pilihcol))
          {
            array_push($info, $value->total_minute);
          }
          if(in_array( 'appdate',$pilihcol))
          {
            array_push($info, $cdt);
          }
          if(in_array( 'verdate',$pilihcol))
          {
            array_push($info, $ver_date);
          }
          if(in_array( 'verid',$pilihcol))
          {
            array_push($info, $value->verifier_id);
          }
          if(in_array( 'appdate',$pilihcol))
          {
            array_push($info, $appvl_date);
          }
          if(in_array( 'apprvrid',$pilihcol))
          {
            array_push($info, $value->approver_id);
          }
          if(in_array( 'qrdate',$pilihcol))
          {
            array_push($info, $queried_date);
          }
          if(in_array( 'qrdby',$pilihcol))
          {
            array_push($info, $value->querier_id);
          }
          if(in_array( 'pydate',$pilihcol))
          {
            array_push($info, $payment_date);
          }
        }
        array_push($otdata, $info);
      }
      // dd($otdata);

// Log::info('siap prepare data');
      $sh = 'OvertimeSummary';

    }//ot detail
    elseif($req->searching == 'exceld'){
      foreach($otdetail as $value){

        $urekod = $value->mainOT->URecord;
        $mainOT = $value->mainOT;
        if($urekod->ot_salary_exception=='X'){
            // $mainOT->ot_hour_exception='Yes';
            $sal_exception='Yes';
            $salarycap='';
          }else{
            // $mainOT->ot_hour_exception='No';
            $sal_exception='No';
            $salarycap=$mainOT->SalCap()->salary_cap;
          }

          $otdt = new Carbon($mainOT->date);
          $otdt = $otdt->format('d.m.Y');
          $st = new Carbon($value->start_time);
          $st = $st->format('d.m.Y');
          $et = new Carbon($value->end_time);
          $et = $et->format('d.m.Y');

          $dtype = $mainOT->daytype->description;
          $cdt = new Carbon($mainOT->created_at);
          $cdt = $cdt->format('d.m.Y');

          if( $mainOT->verification_date == ''){
            $ver_date = '';
          }
          else{
            $ver_date = date('d.m.Y H:i:s', strtotime($mainOT->verification_date));
          }

          if( $mainOT->approved_date == ''){
            $appvl_date = '';
          }
          else{
            $appvl_date = date('d.m.Y H:i:s', strtotime($mainOT->approved_date));
          }

          if( $mainOT->queried_date == ''){
            $queried_date ='';
          }
          else{
            $queried_date =date('d.m.Y H:i:s', strtotime($mainOT->queried_date));
          }

          if( $mainOT->payment_date == ''){
            $payment_date ='';
          }
          else{
            $payment_date =date('d.m.Y', strtotime($mainOT->payment_date));
          }

          $info = [$mainOT->user_id,$urekod->name,$urekod->new_ic,$urekod->staffno,$urekod->company_id,$mainOT->refno,$otdt,$st,$et];
          // dd($pilihcol);
          if(isset($pilihcol)){
          if(in_array('psarea',$pilihcol ))
            {
              array_push($info, $urekod->persarea);
            }
            if(in_array( 'psbarea',$pilihcol))
            {
              array_push($info, $urekod->perssubarea);
            }
            if(in_array( 'state',$pilihcol))
            {
              array_push($info, $mainOT->state_id);
            }
            if(in_array( 'region',$pilihcol))
            {
              array_push($info, $mainOT->region);
            }
            if(in_array( 'empgrp',$pilihcol))
            {
              array_push($info, $urekod->empgroup);
            }
            if(in_array( 'empsubgrp',$pilihcol))
            {
              array_push($info, $urekod->empsgroup);
            }
            if(in_array( 'salexp',$pilihcol))
            {
              array_push($info, $sal_exception);
            }
            if(in_array( 'capsal',$pilihcol))
            {
              array_push($info, $salarycap);
            }
            if(in_array( 'empst',$pilihcol))
            {
              array_push($info, $urekod->empstats);
            }
            if(in_array( 'mflag',$pilihcol))
            {
              array_push($info, $value->is_manual);
            }
            if(in_array( 'dytype',$pilihcol))
            {
              array_push($info, $dtype);
            }
            if(in_array( 'loc',$pilihcol))
            {
              array_push($info, '('.$value->in_latitude.','.$value->in_longitude.')');
            }
            if(in_array( 'trnscd',$pilihcol))
            {
              array_push($info, $mainOT->wage_type);
            }
            if(in_array( 'estamnt',$pilihcol))
            {
              array_push($info, $value->amount);
            }
            if(in_array( 'clmstatus',$pilihcol))
            {
              array_push($info, $mainOT->OTStatus()->item3);
            }
            if(in_array( 'chrtype',$pilihcol))
            {
              array_push($info, $mainOT->charge_type);
            }
            if(in_array( 'noh',$pilihcol))
            {
              array_push($info, $value->hour);
            }
            if(in_array( 'nom',$pilihcol))
            {
              array_push($info, $value->minute);
            }
            if(in_array( 'jst',$pilihcol))
            {
              array_push($info, $value->justification);
            }
            if(in_array( 'appdate',$pilihcol))
            {
              array_push($info, $cdt);
            }
            if(in_array( 'verdate',$pilihcol))
            {
              array_push($info, $ver_date);
            }
            if(in_array( 'verid',$pilihcol))
            {
              array_push($info, $mainOT->verifier_id);
            }
            if(in_array( 'appdate',$pilihcol))
            {
              array_push($info, $appvl_date);
            }
            if(in_array( 'apprvrid',$pilihcol))
            {
              array_push($info, $mainOT->approver_id);
            }
            if(in_array( 'qrdate',$pilihcol))
            {
              array_push($info, $queried_date);
            }
            if(in_array( 'qrdby',$pilihcol))
            {
              array_push($info, $mainOT->querier_id);
            }
            if(in_array( 'pydate',$pilihcol))
            {
              array_push($info, $payment_date);
            }
      }
      array_push($otdata, $info);
    }
      $sh = 'OvertimeDetails';
    }
    // dd($otdata,$headers);
      $eksel->addSheet($sh, $otdata, $headers);
      // Log::info('excel loaded');
      return $eksel->download();
  }



}
