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
  public function showPunchView(Request $req){

    // dd($errors);

    $curp = UserHelper::GetCurrentPunch($req->user()->id);
    if($curp){
      $ps = 'Out';
      $btncol = 'warning';
      $url = route('punch.out', [], false);
    } else {
      $ps = 'In';
      $btncol = 'success';
      $url = route('punch.in', [], false);
    }

    $punlis = UserHelper::GetPunchList($req->user()->id);

    // dd([
    //   'punch_status' => $ps,
    //   'p_url' => $url,
    //   'p_list' => $punlis,
    //   'p_gotdata' => $punlis->count() != 0
    // ]);

    return view('staff.punchlist', [
      'punch_status' => $ps,
      'btncol' => $btncol,
      'p_url' => $url,
      'p_list' => $punlis,
      'p_gotdata' => $punlis->count() != 0
    ]);
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

      // dd($errors);
      StaffPunch::find($req->inputid)->delete();
      OvertimePunch::where('punch_id', $req->inputid)->delete();
      return redirect(route('punch.list', [], false));
    }
}
