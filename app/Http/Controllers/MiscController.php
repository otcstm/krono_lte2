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

    //pending verification count()
    $pending_approval_count =
    Overtime::where('approver_id','=',$req->user()->id)
    ->whereIn('status',array('PA'))
    ->count();

    //pending approval count()
    $pending_verification_count =
    Overtime::where('verifier_id','=',$req->user()->id)
    ->whereIn('status',array('PV'))
    ->count();


    //link set default verifier

    //link overtime plan

    //mainpower request count()

    //claim approval report

    //notification top


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

    // $pendingMonthly = [];
    // for ($m=1; $m<=12; $m++) {
    //   if($dataPendingMonth->count() > 0)
    //   {
    //     if($dataPendingMonth->month_label == $m){
    //       array_push($pendingMonthly, $dataPendingMonth->sum_amount);
    //     }
    //     else
    //     {
    //       array_push($pendingMonthly, 0);
    //     }
    //   }
    //   else
    //   {
    //     array_push($pendingMonthly, 0);
    //   }
    // }

    // chart dataset 2 paid
      $dataPaidMonth = Overtime::select(
      DB::raw("sum(amount) as sum_amount"),
      DB::raw("DATE_FORMAT(date, '%Y') as year_label"),
      DB::raw("DATE_FORMAT(date, '%c') as month_label")
      )
      ->where('user_id','=',$req->user()->id)
      ->where('status','=','PAID')
      ->whereYear('date','=', $curr_date)
      ->groupby('year_label','month_label')
      ->get();
    //->toSql();
     //dd($dataPaidMonth->sum('sum_amount'));
    // dd($dataPaidMonth->count());

      $paidMonthly = [];

        for ($m=1; $m<=12; $m++) {
          if($dataPaidMonth->count() > 0)
          {

          foreach($dataPaidMonth as $dataPaidMonthRow){
            if($dataPaidMonthRow->month_label == $m){
              array_push($paidMonthly, $dataPaidMonthRow->sum_amount);
            }
            else
            {
              array_push($paidMonthly, 0);
            }
          }

          }
          else
          {
            array_push($paidMonthly, 0);
          }
        }


    //dd($paidMonthly);

    $monthYearLabel = [];
    for ($m=1; $m<=12; $m++) {
      //$month = date('F', mktime(0,0,0,$m, 1, date('Y')));
      array_push($monthYearLabel, date('F', mktime(0,0,0,$m, 1, date('Y'))));;
    }

  //$colordata1 = "rgba(193, 66, 66, 0.5)";
  $colordata2 = "rgba(0,83,132,1)";

  if($dataPaidMonth->sum('sum_amount') > 0){
    $setScalesMax = $dataPaidMonth->sum('sum_amount')+10;
    $setStepSize = $dataPaidMonth->sum('sum_amount')+10;
  }
  else{
    $setScalesMax = 100;
    $setStepSize = 10;
  }

    $otYearChart = app()->chartjs
         ->name('barChartTest')
         ->type('bar')
         //->size(['width' => 400, 'height' => 200])
         ->labels($monthYearLabel)
         ->datasets([
            //  [
            //      "label" => "Pending",
            //      'backgroundColor' => [
            //       $colordata1, $colordata1, $colordata1, $colordata1,
            //       $colordata1, $colordata1, $colordata1, $colordata1,
            //       $colordata1, $colordata1, $colordata1, $colordata1
            //      ],
            //      'borderColor' => '#000',
            //      'lineTension' => 0,
            //      'data' => $pendingMonthly
            //  ],
             [
                 "label" => "Paid",
                 'backgroundColor' => [
                  $colordata2, $colordata2, $colordata2, $colordata2,
                  $colordata2, $colordata2, $colordata2, $colordata2,
                  $colordata2, $colordata2, $colordata2, $colordata2
                 ],
                 'borderColor' => '#000',
                 'lineTension' => 0,
                 'data' => $paidMonthly
             ]
         ])
         ->options([
          'scales' => [
                 'yAxes' => [[
                   'display' => true,
                   'ticks' => [
                     'suggestedMin' => 0,
                     'beginAtZero' => 0,
                     'min' => 0,
                     'max' => $setScalesMax,
                     'stepSize' => $setStepSize
                   ],
                   'scaleLabel' => [
                     'display' => true,
                     'labelString' => 'RM'
                    ]
                 ]]
               ]
         ]);

    //verifier
    $isVerifier = 0;
    $checkVerifier = Overtime::where('verifier_id','=',$req->user()->id)
    ->get();

    if($checkVerifier->count() > 0)
    {
      $isVerifier = 1;
    }

    //approver
    $isApprover = 0;
    $checkApprover = Overtime::where('approver_id','=',$req->user()->id)
    ->get();
    if($checkApprover->count() > 0)
    {
      $isApprover = 1;
    }

    //UserAdmin
    $isUserAdmin = 0;
    $checkUserAdmin = DB::table('role_user')
    ->where('role_id','=',3)
    ->where('user_id','=',$req->user()->id)
    ->get();
    if($checkUserAdmin->count() > 0)
    {
      $isUserAdmin = 1;
    }

    //SysAdmin
    $isSysAdmin = 0;
    $checkSysAdmin = DB::table('role_user')
    ->where('role_id','=',2)
    ->where('user_id','=',$req->user()->id)
    ->get();
    if($checkSysAdmin->count() > 0)
    {
      $isSysAdmin = 1;
    }

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
      'isVerifier' => $isVerifier,
      'isApprover' => $isApprover,
      'isUserAdmin' => $isUserAdmin,
      'isSysAdmin' => $isSysAdmin,
      'otYearChart' => $otYearChart,
      'chartSumYear' => $dataPaidMonth->sum('sum_amount')
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
    // return ['test' => $date];
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

  public function checkWorkTime(Request $req){
    $i = 1;
    $check[0]="00:00";
    $times = strtr($req->time, '/', '-');
    $timex = date("Y-m-d H:i:s", strtotime($times));
    do{
      $check = UserHelper::CheckDay($req->user()->id, date("Y-m-d", strtotime($req->time . ' +'.$i.' day')));
      $i++;
    }while($check[0]=="00:00");
    $i--;
    $stime = explode(":", $check[0]);
    if(((date("G", strtotime($times))*60)+date("i", strtotime($times)))<($stime[0]*60+$stime[1])){
      $i= 0;
    }
    return ["swtime" => $check[0], "addday" => $i, "test1" => ((date("G", strtotime($times))*60)+date("i", strtotime($times))), "test2" => ($stime[0]*60+$stime[1]), 'test3' => $req->timex];
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
