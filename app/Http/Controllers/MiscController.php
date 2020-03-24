<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shared\UserHelper;
use App\Shared\URHelper;
use App\StaffPunch;
use App\OvertimePunch;
use App\User;
use App\Overtime;
use App\UserLog;
use App\PaymentSchedule;
use \Carbon\Carbon;
use Session;
use DateTime;
use DB;
//use DateTimeZone;

class MiscController extends Controller
{
  public function home(Request $req){

    $last_month = Carbon::now()->addMonths(-1); 
    $first_last_month = date('01-m-Y',strtotime($last_month));

    $curr_date = now(); 

    $next_month = Carbon::now()->addMonths(1); 
    $first_next_month = date('01-m-Y',strtotime($next_month));

    //user
    //actual payment for current month        
    $act_payment_curr_month = 
    Overtime::where('user_id','=',$req->user()->id)
    ->where('status','=','PAID')
    ->whereYear('date','=', $curr_date)
    ->whereMonth('date','=', $curr_date)
    ->sum('amount');
    //->with(['detail' => function($query){
    //  $query->sum('amount');
    //}])
    //dd($act_payment_curr_month);

    //Pending payment last month      
    $pending_payment_last_month = 
    Overtime::where('user_id','=',$req->user()->id)
    ->where('status','=','PAID')
    ->whereYear('date','=', $last_month)
    ->whereMonth('date','=', $last_month)
    ->sum('amount');

    //total hour OT from current month 
    $total_hour_ot_curr_month = 
    Overtime::where('user_id','=',$req->user()->id)
    ->where('status','=','PAID')
    ->whereYear('date','=', $curr_date)
    ->whereMonth('date','=', $curr_date)
    ->sum('total_hour');

    //next payment schedule
    $next_payment_sch = 
    PaymentSchedule::whereYear('payment_date','=', $next_month)
    ->whereMonth('payment_date','=', $next_month)
    ->max('payment_date');
    //dd($next_payment_sch);  
    
    if(is_null($next_payment_sch)){
      $next_payment_sch = 0;
    };

    //verifier
    //Last approval date
    $last_approval_date = 
    PaymentSchedule::whereYear('payment_date','=', $curr_date)
    ->whereMonth('payment_date','=', $curr_date)
    ->max('last_approval_date');
    
    //approver
    //Last approval date
    $last_approval_date = 
    PaymentSchedule::whereYear('payment_date','=', $curr_date)
    ->whereMonth('payment_date','=', $curr_date)
    ->max('last_approval_date');
    
    //pending approval count()
    $pending_approval_count = 
    Overtime::where('approver_id','=',$req->user()->id)
    ->orWhere('verifier_id','=',$req->user()->id)
    ->whereIn('status',array('PA','PV'))
    ->count();

    //link set default verifier

    //link overtime plan

    //mainpower request count()

    //claim approval report

    //notification top

    //to do notification
    $to_do_list = Overtime::join('setup_codes', 'overtimes.status', '=', 'setup_codes.item2')
    ->select('overtimes.*', 'setup_codes.item3')
    ->where('overtimes.user_id','=',$req->user()->id)
    ->whereIn('overtimes.status',array('PA', 'A'))
    ->groupBy('overtimes.refno')
    ->groupBy('overtimes.status')
    ->get();
    Session::put(['to_do_list' => $to_do_list]);

    //chart yearly bar
    //usage view: {!! $chart->render() !!}

    // //chart dataset 1 pending
    // $dataPendingMonth = 
    // Overtime::select(
    // DB::raw("sum(amount) as sum_amount"), 
    // DB::raw("DATE_FORMAT(date, '%Y') year_label"),  
    // DB::raw("DATE_FORMAT(date, '%m') month_label")
    // )
    // ->where('user_id','=',$req->user()->id)
    // ->whereIn('status',array('PA', 'A'))
    // ->whereYear('date','=', $curr_date)
    // ->groupby('year_label','month_label')
    // ->get();
    // //->toSql();
    // //dd($dataPendingMonth);
    // $pendingMonth = [];
    // foreach ($dataPendingMonth as $key => $value) {
    //   $pendingMonth[] = $value['sum_amount'];
    // }

    // //chart dataset 2 paid
    // $dataPaidMonth = 
    // Overtime::select(
    // DB::raw("sum(amount) as sum_amount"), 
    // DB::raw("DATE_FORMAT(date, '%Y') year_label"),  
    // DB::raw("DATE_FORMAT(date, '%m') month_label")
    // )
    // ->where('user_id','=',$req->user()->id)
    // ->whereIn('status','=','PAID')
    // ->whereYear('date','=', $curr_date)
    // ->groupby('year_label','month_label')
    // ->get();
    // //->toSql();
    // //dd($dataPaidMonth);
    // $paidMonth = [];
    // foreach ($dataPaidMonth as $key => $value) {
    //   $paidMonth[] = $value['sum_amount'];
    // }

    // $monthYearLabel = [];
    // for ($m=1; $m<=12; $m++) {
    //   $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
    //   array_push($monthYearLabel, date('F', mktime(0,0,0,$m, 1, date('Y'))));;
    // }

    // $otYearChart = app()->chartjs
    // ->name('barChartTest')
    // ->type('bar')
    // // ->size(['width' => 400, 'height' => 400])
    // ->labels($monthYear)
    // ->datasets([
    //   [
    //         'label' => 'Pending',
    //         'backgroundColor' => 'rgba(191, 191, 63)',
    //         'borderColor' => '#000',
    //         'data' => $pendingByMonth,
    //         'lineTension' => 0
    //   ],
    //   [
    //         'label' => 'Paid',
    //         'backgroundColor' => 'rgba(63, 191, 63)',
    //         'borderColor' => '#000',
    //         'data' => $pendingByMonth,
    //         'lineTension' => 0
    //   ]
    // ])
    // ->options([
    //   'responsive' => true,
    //   'title' => [
    //     'display' => true,
    //     'text' => 'OT Year',
    //   ],
    //   'tooltips' => [
    //     'mode' => 'index',
    //     'intersect' => false,
    //   ],
    //   'hover' => [
    //     'mode' => 'nearest',
    //     'intersect' => false,
    //   ],
    //   'scales' => [
    //     'xAxes' => [[
    //       'display' => true,
    //       'scaleLabel' => [
    //         'display' => true,
    //         'LabelString' => 'Date',
    //       ]
    //     ]],
    //     'yAxes' => [[
    //       'display' => true,
    //       'scaleLabel' => [
    //         'display' => true,
    //         'LabelString' => '%',
    //       ]
    //     ]]
    //   ]
    // ]);


    // dd($req->user()->name);
    return view('home', [
      'uname' => $req->user()->name,
      'first_last_month' => $first_last_month,
      'first_next_month' => $first_next_month,
      'act_payment_curr_month' => $act_payment_curr_month,
      'pending_payment_last_month' => $pending_payment_last_month,
      'total_hour_ot_curr_month' => $total_hour_ot_curr_month,
      'next_payment_sch' => $next_payment_sch,
      'last_approval_date' => $last_approval_date,
      'pending_approval_count' => $pending_approval_count,
      'to_do_list' => $to_do_list
      //'otYearChart' => $otYearChart      
      ]);
  }

  public function index(){
    return view('welcome');
  }

  // =============================
  // clock in
  // public function showPunchView(Request $req){

  //   // dd($errors);

  //   $curp = UserHelper::GetCurrentPunch($req->user()->id);
  //   if($curp){
  //     $ps = 'Out';
  //     $btncol = 'warning';
  //     $url = route('punch.out', [], false);
  //   } else {
  //     $ps = 'In';
  //     $btncol = 'success';
  //     $url = route('punch.in', [], false);
  //   }

  //   $punlis = UserHelper::GetPunchList($req->user()->id);

  //   // dd([
  //   //   'punch_status' => $ps,
  //   //   'p_url' => $url,
  //   //   'p_list' => $punlis,
  //   //   'p_gotdata' => $punlis->count() != 0
  //   // ]);

  //   return view('staff.punchlist', [
  //     'punch_status' => $ps,
  //     'btncol' => $btncol,
  //     'p_url' => $url,
  //     'p_list' => $punlis,
  //     'p_gotdata' => $punlis->count() != 0
  //   ]);
  // }


//================================================================================================
  public function showPunchView(Request $req){
    $punlis = UserHelper::GetPunchList($req->user()->id);
    return view('staff.punchlist', [

      'p_list' => $punlis,
      'p_gotdata' => $punlis->count() != 0
    ]);
  }

  public function startPunch(Request $req){

    // $req->time = "2020-03-04 07:30:00"; //testing
    // $req->time = "2020-02-05 19:24:09"; //testing
    
    $date = date("Y-m-d", strtotime($req->time));
    $day = UserHelper::CheckDay($req->user()->id, $date);
    $userrecordid = URHelper::getUserRecordByDate($req->user()->id, $date);
    $currentp = new StaffPunch;
    $currentp->user_id = $req->user()->id;
    $currentp->day_type = $day[2];
    $currentp->punch_in_time = $req->time;
    $currentp->user_records_id = $userrecordid->id;
    // $currentp->in_latitude = 0.0; //temp
    // $currentp->in_longitude = 0.0; //temp
    $currentp->in_latitude = $req->lat; //temp
    $currentp->in_longitude = $req->long; //temp
   
    $currentp->save();
  }

  public function checkPunch(Request $req){
    $currentp = StaffPunch::where("user_id", $req->user()->id)->where("punch_out_time", NULL)->first();
    if($currentp!=NULL){
      return ['result'=> true, 'time'=>date('Y/m/d/H/i/s', strtotime($currentp->punch_in_time)), 'stime'=>date('Y-m-d H:i:s', strtotime($currentp->punch_in_time))];
    }else{
      return ['result'=> false];
    }
  }

  public function checkDay(Request $req){
    $check = UserHelper::CheckDay($req->user()->id, date("Y-m-d", strtotime($req->date)));
    if($check[3]<6){
      $stime = explode(":", $check[0]);
      $etime = explode(":", $check[1]);
      $sstime = $stime[0]*60+$stime[1];
      $eetime = $etime[0]*60+$etime[1];
      $time=date("G", strtotime($req->date))*60+date("i", strtotime($req->date));

      if(($time<$sstime)||($time>=$eetime)){
        return ['result'=> true];
      }else{
        return ['result'=> false];
      }
    }else{
      return ['result'=> true];
    }
  }

  public function endPunch(Request $req){
    
    // $req->stime = "2020-03-04 07:30:00"; //testing
    // $req->etime = "2020-03-04 08:30:00"; //testing
    // $req->stime = "2020-02-05 19:24:09"; //testing
    // $req->etime = "2020-02-05 20:40:09"; //testing

    $sdate = date("Y-m-d", strtotime($req->stime));
    $edate = date("Y-m-d", strtotime($req->etime));
    $eday = UserHelper::CheckDay($req->user()->id, $req->etime);
    $userrecordid = URHelper::getUserRecordByDate($req->user()->id, $sdate);
    $currentp = StaffPunch::where("user_id", $req->user()->id)->where("punch_in_time", $req->stime)->first();
    if(((date("j", strtotime($req->etime)))- (date("j", strtotime($req->stime)))) > 0){
      $currentp->punch_out_time = $edate." 00:00:00";
      
      $currentp->out_latitude = $req->lat2; //temp
      $currentp->out_longitude = $req->long2; //temp
      // $currentp->out_latitude = 0.0; //temp
      // $currentp->out_longitude = 0.0; //temp
      $currentp->status = 'out';
      $currentp->save();
      $execute = UserHelper::AddOTPunch($req->user()->id, $sdate, $req->stime, $edate." 00:00:00", $currentp->id, $currentp->in_latitude, $currentp->in_longitude, $currentp->out_latitude, $currentp->out_longitude);
      $currentp = new StaffPunch;
      $currentp->user_id = $req->user()->id;
      $currentp->day_type = $eday[2];
      $currentp->punch_in_time = $edate." 00:00:00";
      $currentp->in_latitude = $req->lat; 
      $currentp->in_longitude = $req->long; 
      $currentp->punch_out_time = $req->etime;
      $currentp->out_latitude = $req->lat2; 
      $currentp->out_longitude = $req->long2; 
      $currentp->status = 'out';
      $currentp->user_records_id = $userrecordid->id;
      $currentp->save();
      $execute = UserHelper::AddOTPunch($req->user()->id, $edate, $edate." 00:00:00", $req->etime, $currentp->id, $currentp->in_latitude, $currentp->in_longitude, $currentp->out_latitude, $currentp->out_longitude);
      $dt = OvertimePunch::where('punch_id', $currentp->id)->get();
      if(count($dt)==0){
        $currentp->delete();
      }
      return ['result'=> 'tea'];
    }else{
      $currentp->punch_out_time = $req->etime;
      $currentp->out_latitude = $req->lat2; 
      $currentp->out_longitude = $req->long2; 
      $currentp->status = 'out';
      $currentp->save();
      // return ['result'=> $currentp->in_latitude];
      $execute = UserHelper::AddOTPunch($req->user()->id, $edate, $req->stime, $req->etime, $currentp->id, $currentp->in_latitude, $currentp->in_longitude, $currentp->out_latitude, $currentp->out_longitude);
      $dt = OvertimePunch::where('punch_id', $currentp->id)->get();
      if(count($dt)==0){
        $currentp->delete();
      }
    }

    // return ['result'=>(date("j", strtotime($req->etime)))- (date("j", strtotime($req->stime)))];
  }

  public function cancelPunch(Request $req){
    $currentp = StaffPunch::where("user_id", $req->user()->id)->where("punch_in_time", $req->time)->delete();
  }


//================================================================================================
  public function doClockIn(Request $req){

    $time = new DateTime('NOW');
    //$time->setTimezone(new DateTimeZone('+0800'));

    $pun = UserHelper::StaffPunchIn($req->user()->id, $time);
    if($pun['status'] == 'OK'){
      return redirect(route('punch.list', [], false));
    } else {
      return redirect()->back()->withErrors(['punch' => $pun['status']]);
    }
  }



  public function doClockOut(Request $req){
    $time = Carbon::now('Asia/Kuala_Lumpur');
    // Carbon::now('Europe/London');
    //$time->setTimezone(new DateTimeZone('+0800'));

    $pun = UserHelper::StaffPunchOut($req->user()->id, $time);
    if($pun['status'] == 'OK'){
      return redirect(route('punch.list', [], false));
    } else {
      return redirect()->back()->withErrors(['punch' => $pun['status']]);
    }
  }

  // end clock in
  // ================================

  //retrive list user logs
  public function listUserLogs(Request $req)
    {
        //retrieve data from table user_logs
        $listUserLogs = UserLog::where('user_id', $req->user()->id)->get();
        //dd($listUserLogs);
        return view('log.listUserLogs', compact('listUserLogs'))
        //count row display only
        ->with('i', (request()->input('page', 1) - 1) * 5);;
    }

    public function delete(Request $req){
      // $time = StaffPunch::whereDate('punch_in_time', $req->inputid)->get();
      // // $time = StaffPunch::whereDate('punch_in_time', $req->inputid)->get();
      // foreach($time as $deltime){
      //   $deltime->delete();
      // }
      $otime = OvertimePunch::where('id', $req->inputid)->first();
      $id = $otime->punch_id;
      $delotime = OvertimePunch::find($req->inputid)->delete();
      $allotime = OvertimePunch::where('punch_id', $id)->get();
      if(count($allotime)==0){
        $delotime = StaffPunch::find($id)->delete();
      }
      return redirect(route('punch.list', [], false));
    }
}
