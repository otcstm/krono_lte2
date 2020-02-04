<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shared\UserHelper;
use App\StaffPunch;
use App\OvertimePunch;
use App\User;
use App\UserLog;
use \Carbon\Carbon;
use DateTime;
//use DateTimeZone;

class MiscController extends Controller
{
  public function home(Request $req){
    // dd($req->user()->name);
    return view('home', ['uname' => $req->user()->name]);
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

    $req->time = "2020-02-05 07:24:09"; //testing

    $date = date("Y-m-d", strtotime($req->time));
    $day = UserHelper::CheckDay($req->user()->id, $date);
    $currentp = new StaffPunch;
    $currentp->user_id = $req->user()->id;
    $currentp->day_type = $day[2];
    $currentp->punch_in_time = $req->time;
    $currentp->in_latitude = 0.0; //temp
    $currentp->in_longitude = 0.0; //temp
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

  public function endPunch(Request $req){
    
    $req->stime = "2020-02-05 07:24:09"; //testing
    $req->etime = "2020-02-05 18:40:09"; //testing

    $sdate = date("Y-m-d", strtotime($req->stime));
    $edate = date("Y-m-d", strtotime($req->etime));
    $eday = UserHelper::CheckDay($req->user()->id, $req->etime);
    $currentp = StaffPunch::where("user_id", $req->user()->id)->where("punch_in_time", $req->stime)->first();
    if(((date("j", strtotime($req->etime)))- (date("j", strtotime($req->stime)))) > 0){
      $currentp->punch_out_time = $edate." 00:00:00";
      $currentp->out_latitude = 0.0; //temp
      $currentp->out_longitude = 0.0; //temp
      $currentp->status = 'out';
      $currentp->save();
      $execute = UserHelper::AddOTPunch($req->user()->id, $sdate, $req->stime, $edate." 00:00:00", $currentp->id, $currentp->in_latitude, $currentp->in_longitude, $currentp->out_latitude, $currentp->out_longitude);
      $currentp = new StaffPunch;
      $currentp->user_id = $req->user()->id;
      $currentp->day_type = $eday[2];
      $currentp->punch_in_time = $edate." 00:00:00";
      $currentp->in_latitude = 0.0; //temp
      $currentp->in_longitude = 0.0; //temp
      $currentp->punch_out_time = $req->etime;
      $currentp->out_latitude = 0.0; //temp
      $currentp->out_longitude = 0.0; //temp
      $currentp->status = 'out';
      $currentp->save();
      $execute = UserHelper::AddOTPunch($req->user()->id, $edate, $edate." 00:00:00", $req->etime, $currentp->id, $currentp->in_latitude, $currentp->in_longitude, $currentp->out_latitude, $currentp->out_longitude);
    
      // return ['result'=> 'tea'];
    }else{
      $currentp->punch_out_time = $req->etime;
      $currentp->out_latitude = 0.0; //temp
      $currentp->out_longitude = 0.0; //temp
      $currentp->status = 'out';
      $currentp->save();
      // return ['result'=> $currentp->in_latitude];
      $execute = UserHelper::AddOTPunch($req->user()->id, $edate, $req->stime, $req->etime, $currentp->id, $currentp->in_latitude, $currentp->in_longitude, $currentp->out_latitude, $currentp->out_longitude);
      // return ['result'=>'kontol'];
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
      $time = StaffPunch::whereDate('punch_in_time', $req->inputid)->get();
      foreach($time as $deltime){
        $deltime->delete();
      }
      $otime = OvertimePunch::where('date', $req->inputid)->get();
      foreach($otime as $delotime){
        $delotime->delete();
      }
      return redirect(route('punch.list', [], false));
    }
}
