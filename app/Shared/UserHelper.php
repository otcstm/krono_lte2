<?php

namespace App\Shared;

use App\User;
use App\UserLog;
use App\StaffPunch;
use App\SapPersdata;
use App\StaffAdditionalInfo;

class UserHelper {

  public static function CreateUser($input){

  }

  public static function GetUserInfo($staff_id){
    $sai = StaffAdditionalInfo::where('user_id', $staff_id)->first();
    if($sai){

    } else {
      // create new
      $sai = new StaffAdditionalInfo;
      $sai->user_id = $staff_id;
      $sai->save();
    }

    return [
      'extra' => $sai
    ];
  }

  public static function GetRequireAttCount(){
    return 5;
  }

  public static function GetCurrentPunch($staff_id){
    return StaffPunch::where('user_id', $staff_id)->where('status', 'in')->first();
  }

  public static function GetPunchList($staff_id){
    return StaffPunch::where('user_id', $staff_id)->get();
  }

  public static function StaffPunchIn($staff_id, $in_time, $in_lat = 0.0, $in_long = 0.0){
    $currentp = UserHelper::GetCurrentPunch($staff_id);
    $msg = 'OK';
    if($currentp){
      // already punched
      $msg = 'Already Punched In';
    } else {
      $currentp = new StaffPunch;
      $currentp->user_id = $staff_id;
      $currentp->punch_in_time = $in_time;
      $currentp->in_latitude = $in_lat;
      $currentp->in_longitude = $in_long;
      $currentp->save();
    }

    return [
      'status' => $msg,
      'data' => $currentp
    ];
  }

  public static function StaffPunchOut($staff_id, $out_time, $out_lat = 0.0, $out_long = 0.0){
    $currentp = UserHelper::GetCurrentPunch($staff_id);
    $msg = 'OK';
    if($currentp){
      $currentp->punch_out_time = $out_time;
      $currentp->out_latitude = $out_lat;
      $currentp->out_longitude = $out_long;
      $currentp->status = 'out';
      $currentp->save();

    } else {
      $msg = 'Not Punched In';
    }

    return [
      'status' => $msg,
      'data' => $currentp
    ];
  }

  // Update User Activity
  public static function LogUserAct($req, $mn, $at)
    {
        //$req = Request::all();
        $user_logs = new UserLog;

        $user_logs->user_id = $req->user()->id;
        $user_logs->module_name = strtoupper($mn);
        $user_logs->activity_type = ucfirst($at);
        $user_logs->session_id = $req->session()->getId();
        $user_logs->ip_address = $req->ip();
        $user_logs->user_agent = $req->userAgent();
        $user_logs->created_by = $req->user()->id;
        $user_logs->save();

        return 'OK';
    }

  public static function GetMySubords($persno, $recursive = false){
    $retval = [];

    $directreporttome = User::where('reptto', $persno)->get();

    foreach($directreporttome as $onestaff){

      array_push($retval, $onestaff);

      if($recursive){
        // find this person's subs
        $csubord = UserHelper::GetMySubords($onestaff->id, $recursive);
        $retval = array_merge($retval, $csubord);
      }
    }

    return $retval;

  }
}
