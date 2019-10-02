<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shared\UserHelper;
use App\StaffPunch;
use App\User;
use App\UserLog;
use DateTime;
use DateTimeZone;

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
    $time->setTimezone(new DateTimeZone('+0800'));

    $pun = UserHelper::StaffPunchIn($req->user()->id, $time);
    if($pun['status'] == 'OK'){
      return redirect(route('punch.list', [], false));
    } else {
      return redirect()->back()->withErrors(['punch' => $pun['status']]);
    }
  }

  public function doClockOut(Request $req){
    $time = new DateTime('NOW');
    $time->setTimezone(new DateTimeZone('+0800'));

    $pun = UserHelper::StaffPunchOut($req->user()->id, $time);
    if($pun['status'] == 'OK'){
      return redirect(route('punch.list', [], false));
    } else {
      return redirect()->back()->withErrors(['punch' => $pun['status']]);
    }
  }

  // end clock in
  // ================================

  // Search User
  public function listStaff(){
    $allusers = User::all();
    return view('staff.liststaff', ['staffs' => $allusers]);
  }

  public function searchStaff(Request $req){
      $staff = User::all();
      $search = 0;
      return view('staff.searchstaff', ['staffs' => $staff], ['search' => $search]);
  }

  public function doSearchStaff(Request $req){
      $no = $req->inputstaffid;
      $name = $req->inputstaffname;
      $search = 1;
      $staff = array();;
      if(!empty($no)){
        $staff = User::where('staff_no', 'LIKE', '%' .$no. '%')->get();
        if(!empty($name)){
          $staff = User::where('staff_no', 'LIKE', '%' .$no. '%')->where('name','LIKE','%'.$name.'%')->get();
        }
      }else if(!empty($name)){
        $staff = User::where('name', 'LIKE', '%' . $name . '%')->get();
      }
      else{
        return view('staff.searchStaff', ['staffs' => $staff,'search' => $search, 'message' => 'Please enter staff no or staff name to search.']);
      }

      if(count($staff)>0){
        return view('staff.searchStaff', ['staffs' => $staff, 'search' => $search]);
      }else{
        return view('staff.searchStaff', ['staffs' => $staff, 'search' => $search, 'message' => 'No maching records found. Try to search again.']);
      }
  }

  //retrive list user logs
  public function listUserLogs()
    {
        //retrieve data from table user_logs
        $listUserLogs = UserLog::all();
        //dd($listUserLogs);
        return view('log.listUserLogs', compact('listUserLogs'))
        //count row display only
        ->with('i', (request()->input('page', 1) - 1) * 5);;
    }

  //update user logs
  public function doUserLogs(Request $req)
    {

    $execute = UserHelper::LogUserAct($req, $mn, $at);
        //retrieve data from table user_logs
        $listUserLogs = UserLog::all();
        //dd($listUserLogs);
        return view('log.listUserLogs', compact('listUserLogs'))
        //count row display only
        ->with('i', (request()->input('page', 1) - 1) * 5);;
    }

}
