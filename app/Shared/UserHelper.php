<?php

namespace App\Shared;

use App\User;
use App\StaffPunch;

class UserHelper {

  public static function CreateUser($input){

  }

  public static function GetUserInfo($staff_no){

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
}
