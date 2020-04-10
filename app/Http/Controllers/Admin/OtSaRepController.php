<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Log;
use \DB;
use App\Overtime;
use App\BatchJob;
use App\State;
use App\SetupCode;
use App\Company;
use App\OvertimeDetail;
use App\OvertimeLog;
use App\StaffPunch;
use \Carbon\Carbon;
use App\ExcelHandler;
use App\Jobs\ReportOT;
use App\Jobs\ReportOTSE;
use App\Jobs\ReportOTLC;


class OtSaRepController extends Controller
{
  public function viewOT(Request $req)//OT Summary
  {
    // dd($req->searching);
    if($req->filled('searching') && $req->searching == 'gexcelm'){
        return $this->doGeOTExcel($req);
    }
    //index
    $company = Company::all();
    $state = State::all();
    $region = SetupCode::where('item1', 'region')->get();

    $rpthis = BatchJob::where('job_type', 'SummaryOTReport')
      ->orderBy('created_at', 'DESC')
      ->limit(100)
      ->get();

    return view('report.sysadmot',[
      'companies'=>$company,
      'states'=>$state,
      'regions'=>$region,
      'history'=>$rpthis
    ]);
  }

  public function viewOTd(Request $req)//OT Detail
  {
     // dd($req->searching);
    if($req->filled('searching') && $req->searching == 'gexceld'){
      // dd('go gen excel');
        return $this->doGeOTExcel($req);
    }
    //index
    $company = Company::all();
    $state = State::all();
    $region = SetupCode::where('item1', 'region')->get();

    $rpthis = BatchJob::where('job_type', 'OvertimeDetailsReport')
      ->orderBy('created_at', 'DESC')
      ->limit(100)
      ->get();

    return view('report.sysadmOtd',[
      'companies'=>$company,
      'states'=>$state,
      'regions'=>$region,
      'history'=>$rpthis
    ]);
  }

  public function viewStEd(Request $req)//List of Start/End OT Time (Punch)
  {
    if($req->filled('searching') && $req->searching == 'gexcelSE'){
      // dd('go gen excel');
        return $this->doGeOTExcel($req);
    }

    $company = Company::all();
    $state = State::all();
    $region = SetupCode::where('item1', 'region')->get();

    $rpthis = BatchJob::where('job_type', 'StartEndOTTimeReport')
      ->orderBy('created_at', 'DESC')
      ->limit(100)
      ->get();

    return view('report.sysadmSE',[
      'companies'=>$company,
      'states'=>$state,
      'regions'=>$region,
      'history'=>$rpthis
    ]);
  }

  public function viewLC(Request $req)//List of Start/End OT Time (Punch)
  {
    if($req->filled('searching') && $req->searching == 'gexcelLC'){
      // dd('go gen excel');
        return $this->doGeOTExcel($req);
    }

    $rpthis = BatchJob::where('job_type', 'OTLogChangesReport')
      ->orderBy('created_at', 'DESC')
      ->limit(100)
      ->get();

    return view('report.sysadmLC',[
      'history'=>$rpthis
    ]);
  }

  public function doGeOTExcel(Request $req)
  {
    if(!empty($req->fpersno)){
      $persno = explode(",", $req->fpersno);
    }else{
      $persno=null;
    }
    if($req->searching == 'gexcelSE'){
      ReportOTSE::dispatch($req->fdate,$req->tdate,$persno,$req->fcompany,$req->fstate,
      $req->fregion,$req->cbcol);

      return redirect(route('rep.sa.StEd', [], false))->with([
        'alert' => 'Report scheduled to be processed',
        'a_type' => 'success'
      ]);

    }elseif($req->searching == 'gexcelLC'){
      // dd('gexcelLC');
      ReportOTLC::dispatch($req->fdate,$req->tdate,$persno,$req->frefno);

      return redirect(route('rep.sa.OTLog', [], false))->with([
        'alert' => 'Report scheduled to be processed',
        'a_type' => 'success'
      ]);


    }else {
      ReportOT::dispatch($req->fdate,$req->tdate,$req->fapprover_id,
      $req->fverifier_id,$req->frefno,$persno,$req->fcompany,$req->fstate,
      $req->fregion,$req->cbcol,$req->searching);

        if($req->searching == 'gexcelm'){
          return redirect(route('rep.sa.OT', [], false))->with([
            'alert' => 'Report scheduled to be processed',
            'a_type' => 'success'
          ]);
        }
        elseif($req->searching == 'gexceld'){
          return redirect(route('rep.sa.OTd', [], false))->with([
            'alert' => 'Report scheduled to be processed',
            'a_type' => 'success'
          ]);
        }
    }
  }

  public function joblist(Request $req){
    if($req->filled('bjid')){
      $bj = BatchJob::find($req->bjid);

      if($bj){
        // return ExcelHandler::DownloadFromBin($bj->attachment, $bj->extra_info);
        return ExcelHandler::DownloadFromPerStorage($bj->remark);
      } else {
        return redirect()->back()->withInput()->with([
          'alert' => 'Report no longer exist',
          'a_type' => 'danger'
        ]);
      }
    } else {
      return redirect()->back()->withInput()->with([
        'alert' => 'Missing ID in input',
        'a_type' => 'warning'
      ]);
    }
  }

}
