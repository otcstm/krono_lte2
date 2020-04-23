<?php

namespace App\Shared;

use App\User;
use App\UserLog;
use App\Overtime;
use App\OvertimeLog;
use App\OvertimePunch;
use App\StaffPunch;
use App\WsrChangeReq;
use App\UserShiftPattern;
use App\ShiftPlanStaffDay;
use App\ShiftPattern;
use App\DayType;
use App\SapPersdata;
use App\UserRecord;
use App\Holiday;
use App\HolidayCalendar;
use App\Leave;
use \Carbon\Carbon;
use DateTime;
use App\StaffAdditionalInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
    // $count = OvertimeController::getQueryAmount();
    $count = UserHelper::getQueryAmount();
    return $count;
  }

  public static function getQueryAmount(){
    if(Auth::check()){
      $curruserid = Auth::user()->id;
      $nitofylist = [];
    }
    // $user = Auth::user()->id;
    // $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', 'PV')->orWhere('approver_id', $req->user()->id)->where('status', 'PA')->orderBy('date_expiry')->orderBy('date')->get();
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
    $date = new Carbon($in_time->format('Y-m-d'));
    $day = UserHelper::CheckDay($staff_id, $date);
    // dd($day[2]);
    $in_time =  Carbon::create(2020, 1, 22, 18, 39, 0); //testing time

    if($currentp){
      // already punched
      $msg = 'Already Punched In';
    } else {
      $currentp = new StaffPunch;
      $currentp->user_id = $staff_id;
      $currentp->day_type = $day[2];
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
    $date = new Carbon($out_time->format('Y-m-d'));
    $day = UserHelper::CheckDay($staff_id, $date);
    $ori_punch = $currentp;
    $msg = 'OK';

    if($currentp){

      $timein = new Carbon($currentp->punch_in_time);
      $punchinori = new Carbon($timein->format('Y-m-d'));
      $punchin = new Carbon($timein->format('Y-m-d'));
      $out_time =  Carbon::create(2020, 1, 23, 06, 38, 0); //testing time

      $timeout = new Carbon($out_time->format('Y-m-d'));
      // 1. check keluar hari yang sama atau tak
      if($punchinori->diff($timeout)->days != 0){

        while($punchin->diff($timeout)->days > 0){
          $punchin->addDay();
          $out = $punchin->toDateTimeString();
          $in = new Carbon($timein->format('Y-m-d'));

          //cek $punchinori = $in, kalo tak same insert new staffpunch
          if($punchinori->diff($in)->days != 0){
            //new record punch in nextday 00:00:00
            $currentp = new StaffPunch;
            $currentp->user_id = $staff_id;
            $currentp->day_type = $day[2];
            $currentp->punch_in_time = $in;
            $currentp->in_latitude = $ori_punch->in_latitude;
            $currentp->in_longitude =  $ori_punch->in_longitude;
          }
          $currentp->punch_out_time = $out;
          $currentp->out_latitude = $out_lat;
          $currentp->out_longitude = $out_long;
          $currentp->status ='out';
          $currentp->parent =  $ori_punch->id;
          $currentp->save();
          $date = new Carbon($punchin->format('Y-m-d'));
          $date->subDay();
          $execute = UserHelper::AddOTPunch($staff_id, $date, $timein, $punchin, $currentp->id, $currentp->in_latitude, $currentp->in_longitude, $out_lat, $out_long);
          $timein = new Carbon($punchin);
        }

      // punch out ori = punch in (adday)
        $currentp = new StaffPunch;
        $currentp->user_id = $staff_id;
        $currentp->day_type = $day[2];
        $currentp->punch_in_time = $timein;
        $currentp->in_latitude = $ori_punch->in_latitude;
        $currentp->in_longitude =  $ori_punch->in_longitude;
        $currentp->punch_out_time = $out_time;
        $currentp->out_latitude = $out_lat;
        $currentp->out_longitude = $out_long;
        $currentp->status = 'out';
        $currentp->parent =  $ori_punch->id;
        $currentp->save();
        $date = new Carbon($out_time->format('Y-m-d'));
        $execute = UserHelper::AddOTPunch($staff_id, $date, $timein, $out_time, $currentp->id, $currentp->in_latitude, $currentp->in_longitude, $out_lat, $out_long);

      }else{
        //cek out hari sama!!!
        $currentp->punch_out_time = $out_time;
        $currentp->out_latitude = $out_lat;
        $currentp->out_longitude = $out_long;
        $currentp->status = 'out';
        // if($req->session()->get('latestpdate')!=null){
        //   if($req->session()->get('latestpdate')==date('Y-m-d', strtotime($currentp->created_at))){
        //     $currentp->parent = $req->session()->get('latestpid');
        //   }else{
        //     $currentp->parent = $currentp->id;
        //   }
        // }else{
        //   Session::put(['latestpdate' => date('Y-m-d', strtotime($currentp->created_at)), 'latestpid' => $currentp->id]);
        // }
        $currentp->save();
        $date = new Carbon($out_time->format('Y-m-d'));
        $execute = UserHelper::AddOTPunch($staff_id, $date, $timein, $out_time, $currentp->id, $currentp->in_latitude, $currentp->in_longitude, $out_lat, $out_long);
      }



    } else {
      $msg = 'Not Punched In';
    }

    return [
      'status' => $msg,
      'data' => $currentp
    ];
  }

  //Add punch data to overtime punch
  public static function AddOTPunch($staff_id, $date, $timein, $out_time, $id, $in_lat, $in_long, $out_lat, $out_long)
  {

    $parentp = StaffPunch::whereDate('punch_in_time', $date)->first();
    // $start = $timein->format('Y-m-d H:i:s');
    // $end = $out_time->format('Y-m-d H:i:s');
    $day = UserHelper::CheckDay($staff_id, $date);
    $startt = strtotime($timein);
    $endt = strtotime($out_time);
    $startd = strtotime($date." ".$day[0].":00");
    $endd = strtotime($date." ".$day[1].":00");
    // dd($startt." ".$endt." ".$startd." ".$endd);
    // dd($start." ".$end." - ".date("Y-m-d", strtotime($date))." ".$day[0].":00 ".date("Y-m-d", strtotime($date))." ".$day[1].":00");
    // dd($startd."<".$endt."&&".$endd.">".$startt);
    if(($startd<$endt) && ($endd>$startt)){
      if(($endt>$endd)&&($startt>$startd)){

        // dd("1");
        $newtime = new OvertimePunch;
        $newtime->user_id = $staff_id;
        $newtime->punch_id = $id;
        $newtime->date = $date;
        $newtime->start_time = $date." ".$day[1].":00";
        $newtime->end_time = $out_time;
        $dif = (strtotime($out_time) - strtotime($date." ".$day[1].":00"))/60;
        $newtime->hour = (int) ($dif/60);
        $newtime->minute = $dif%60;
        $newtime->in_latitude = $in_lat;
        $newtime->in_longitude = $in_long;
        $newtime->out_latitude = $out_lat;
        $newtime->out_longitude = $out_long;
        $newtime->save();
      }else if(($endt<$endd)&&($startt<$startd)){
        // dd("2");
        $newtime = new OvertimePunch;
        $newtime->user_id = $staff_id;
        $newtime->punch_id = $id;
        $newtime->date = $date;
        $newtime->start_time = $timein;
        $newtime->end_time = $date." ".$day[0].":00";
        $dif = (strtotime($date." ".$day[0].":00") - strtotime($timein))/60;
        $newtime->hour = (int) ($dif/60);
        $newtime->minute = $dif%60;
        $newtime->in_latitude = $in_lat;
        $newtime->in_longitude = $in_long;
        $newtime->out_latitude = $out_lat;
        $newtime->out_longitude = $out_long;
        $newtime->save();
      }else if(!(($startt>$startd)&&($startt<$endd))){
        // dd("3");
        $newtime = new OvertimePunch;
        $newtime->user_id = $staff_id;
        $newtime->punch_id = $id;
        $newtime->date = $date;
        $newtime->start_time = $timein;
        $newtime->end_time = $date." ".$day[0].":00";
        $dif = (strtotime($date." ".$day[0].":00") - strtotime($timein))/60;
        $newtime->hour = (int) ($dif/60);
        $newtime->minute = $dif%60;
        $newtime->in_latitude = $in_lat;
        $newtime->in_longitude = $in_long;
        $newtime->out_latitude = $out_lat;
        $newtime->out_longitude = $out_long;
        $newtime->save();
        $newtime = new OvertimePunch;
        $newtime->user_id = $staff_id;
        $newtime->punch_id = $id;
        $newtime->date = $date;
        $newtime->start_time = $date." ".$day[1].":00";
        $newtime->end_time = $out_time;
        $dif = (strtotime($out_time) - strtotime($date." ".$day[1].":00"))/60;
        $newtime->hour = (int) ($dif/60);
        $newtime->minute = $dif%60;
        $newtime->in_latitude = $in_lat;
        $newtime->in_longitude = $in_long;
        $newtime->out_latitude = $out_lat;
        $newtime->out_longitude = $out_long;
        $newtime->save();
      }
    }else{
      $newtime = new OvertimePunch;
      $newtime->user_id = $staff_id;
      $newtime->punch_id = $id;
      $newtime->date = $date;
      $newtime->start_time = $timein;
      $newtime->end_time = $out_time;
      $dif = (strtotime($out_time) - strtotime($timein))/60;
      $newtime->hour = (int) ($dif/60);
      $newtime->minute = $dif%60;
      $newtime->in_latitude = $in_lat;
      $newtime->in_longitude = $in_long;
      $newtime->out_latitude = $out_lat;
      $newtime->out_longitude = $out_long;
      $newtime->save();
    }
  }

  // public static function AddOTPunch($staff_id, $date, $timein, $out_time, $id, $in_lat, $in_long, $out_lat, $out_long)
  // {

  //   $parentp = StaffPunch::whereDate('punch_in_time', $date)->first();
  //   $start = $timein->format('Y-m-d H:i:s');
  //   $end = $out_time->format('Y-m-d H:i:s');
  //   $day = UserHelper::CheckDay($staff_id, $date);
  //   $startt = strtotime($start);
  //   $endt = strtotime($end);
  //   $startd = strtotime(date("Y-m-d", strtotime($date))." ".$day[0].":00");
  //   $endd = strtotime(date("Y-m-d", strtotime($date))." ".$day[1].":00");
  //   // dd($startt." ".$endt." ".$startd." ".$endd);
  //   // dd($start." ".$end." - ".date("Y-m-d", strtotime($date))." ".$day[0].":00 ".date("Y-m-d", strtotime($date))." ".$day[1].":00");
  //   // dd($startd."<".$endt."&&".$endd.">".$startt);
  //   if(($startd<$endt) && ($endd>$startt)){
  //     if(($endt>$endd)&&($startt>$startd)){

  //       // dd("1");
  //       $newtime = new OvertimePunch;
  //       $newtime->user_id = $staff_id;
  //       $newtime->punch_id = $id;
  //       $newtime->parent_punch = $parentp->id;
  //       $newtime->date = $date;
  //       $newtime->start_time = date("Y-m-d", strtotime($date))." ".$day[1].":00";
  //       $newtime->end_time = $end;
  //       $dif = (strtotime($end) - strtotime(date("Y-m-d", strtotime($date))." ".$day[1].":00"))/60;
  //       $newtime->hour = (int) ($dif/60);
  //       $newtime->minute = $dif%60;
  //       $newtime->in_latitude = $in_lat;
  //       $newtime->in_longitude = $in_long;
  //       $newtime->out_latitude = $out_lat;
  //       $newtime->out_longitude = $out_long;
  //       $newtime->save();
  //     }else if(($endt<$endd)&&($startt<$startd)){
  //       // dd("2");
  //       $newtime = new OvertimePunch;
  //       $newtime->user_id = $staff_id;
  //       $newtime->punch_id = $id;
  //       $newtime->parent_punch = $parentp->id;
  //       $newtime->date = $date;
  //       $newtime->start_time = $start;
  //       $newtime->end_time = date("Y-m-d", strtotime($date))." ".$day[0].":00";
  //       $dif = (strtotime(date("Y-m-d", strtotime($date))." ".$day[0].":00") - strtotime($start))/60;
  //       $newtime->hour = (int) ($dif/60);
  //       $newtime->minute = $dif%60;
  //       $newtime->in_latitude = $in_lat;
  //       $newtime->in_longitude = $in_long;
  //       $newtime->out_latitude = $out_lat;
  //       $newtime->out_longitude = $out_long;
  //       $newtime->save();
  //     }else if(!(($startt>$startd)&&($startt<$endd))){
  //       // dd("3");
  //       $newtime = new OvertimePunch;
  //       $newtime->user_id = $staff_id;
  //       $newtime->punch_id = $id;
  //       $newtime->parent_punch = $parentp->id;
  //       $newtime->date = $date;
  //       $newtime->start_time = $start;
  //       $newtime->end_time = date("Y-m-d", strtotime($date))." ".$day[0].":00";
  //       $dif = (strtotime(date("Y-m-d", strtotime($date))." ".$day[0].":00") - strtotime($start))/60;
  //       $newtime->hour = (int) ($dif/60);
  //       $newtime->minute = $dif%60;
  //       $newtime->in_latitude = $in_lat;
  //       $newtime->in_longitude = $in_long;
  //       $newtime->out_latitude = $out_lat;
  //       $newtime->out_longitude = $out_long;
  //       $newtime->save();
  //       $newtime = new OvertimePunch;
  //       $newtime->user_id = $staff_id;
  //       $newtime->punch_id = $id;
  //       $newtime->parent_punch = $parentp->id;
  //       $newtime->date = $date;
  //       $newtime->start_time = date("Y-m-d", strtotime($date))." ".$day[1].":00";
  //       $newtime->end_time = $end;
  //       $dif = (strtotime($end) - strtotime(date("Y-m-d", strtotime($date))." ".$day[1].":00"))/60;
  //       $newtime->hour = (int) ($dif/60);
  //       $newtime->minute = $dif%60;
  //       $newtime->in_latitude = $in_lat;
  //       $newtime->in_longitude = $in_long;
  //       $newtime->out_latitude = $out_lat;
  //       $newtime->out_longitude = $out_long;
  //       $newtime->save();
  //     }
  //   }else{
  //     $newtime = new OvertimePunch;
  //     $newtime->user_id = $staff_id;
  //     $newtime->punch_id = $id;
  //     $newtime->parent_punch = $parentp->id;
  //     $newtime->date = $date;
  //     $newtime->start_time = $start;
  //     $newtime->end_time = $end;
  //     $dif = (strtotime($end) - strtotime($start))/60;
  //     $newtime->hour = (int) ($dif/60);
  //     $newtime->minute = $dif%60;
  //     $newtime->in_latitude = $in_lat;
  //     $newtime->in_longitude = $in_long;
  //     $newtime->out_latitude = $out_lat;
  //     $newtime->out_longitude = $out_long;
  //     $newtime->save();
  //   }
  // }
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

  public static function LogOT($otid, $udid, $a, $m)
    {
        $ot_logs = new OvertimeLog;
        $ot_logs->ot_id = $otid;
        $ot_logs->user_id = $udid;
        $ot_logs->action = $a;
        $ot_logs->message = $m;
        $ot_logs->save();

        return 'OK';
    }

    public static function CalOT($salary, $h, $m)
    {
      $time = ($h*60)+$m;
      $work = 26*7*60;
      $rate = $salary/$work;
      $pay = 1.5*$rate*$time;
      return $pay;
    }

    public static function CheckLeave($user, $date)
    {
      $leave = Leave::where('user_id', $user)->whereDate('start_date','<=',$date)->whereDate('end_date','>=',$date)->where('leave_status', 'POSTED')->orderby('upd_sap', "DESC")->first();
      if($leave){
        return $leave->opr;
      }
      return null;
    }

    // temp=====================================================
    public static function CheckDay($user, $date)
    {
      $day = date('N', strtotime($date));

      // first, check if there's any shift planned for this person
      $wd = ShiftPlanStaffDay::where('user_id', $user)
        ->whereDate('work_date', $date)->first();
// dd($wd);
      if($wd){

      } else {
        // not a shift staff. get based on the wsr
        $currwsr = UserHelper::GetWorkSchedRule($user, $date);
        // then get that day
        $wd = $currwsr->ListDays->where('day_seq', $day)->first();
      };
      // get the day info
      $theday = $wd->Day;
      $idday = $wd->day_type_id;
      $ph = Holiday::where("dt", date("Y-m-d", strtotime($date)))->first();
      $hc = null;
      if($ph){
        // dd($userstate);
        $userstate = UserRecord::where('user_id', $user)->where('upd_sap','<=',$date)->first();
        $hc = HolidayCalendar::where('holiday_id', $ph->id)->where('state_id', $userstate->state_id)->first();
      }
      if($hc){
        $start = "00:00";
        $end =  "00:00";
        $day_type = 'Public Holiday';
        $dy = DayType::where('description', 'Public Holiday')->first();
        $idday = $dy->id;
      }else{
        if($theday->is_work_day == true){
          $day_type = 'Normal Day';
          $stime = new Carbon($theday->start_time);
          $etime = new Carbon($theday->start_time);
          $etime->addMinutes($theday->total_minute);

          $start = $stime->format('H:i');
          $end =  $etime->format('H:i');
        } else {
          $start = "00:00";
          $end =  "00:00";
          $day_type = $theday->description;
        }
      }
      $day_type_id = "";
      // return ["09:43", "00:00", $day_type, $day, $wd->day_type_id];
      return [$start, $end, $day_type, $day, $idday];

      // below is the original temp

      // $day = 6;
      // dd($day);
      // $start = "00:00";
      // $end =  "00:00";

      // // $day_type = 'Off Day'; //temp
      // if($day==6){
      //   $day_type = 'Off Day';
      // }elseif($day>6){
      //   $day_type = 'Rest Day';
      // }else{
      //   $start = "08:30";
      //   $end = "17:30";
      //   // $end = "22:30";
      //   $day_type = 'Normal Day';
      // }

      // $daytpe = DayType::where()->first();



      // return [$start, $end, $day_type, $day];
    }
     // temp=====================================================

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

  public static function CheckGM($todate, $otdate){
    $difdatem = date('m',strtotime($todate)) - date('m',strtotime($otdate));
    $difdated = date('d',strtotime($todate)) - date('d',strtotime($otdate));
        if($difdatem<0){
            $difdatem=$difdatem+12;
        }

        // dd($otdate);
        $gm = true;
        if(($difdatem<4)){
            $gm = false;
            if($difdatem==3){
                if($difdated>=0){
                  $gm = true;
                }
            }
        }
        return $gm;
  }

  public static function GetWorkSchedRule($staffid, $idate){
    // first, check if there's any approved change req
    $currwsr = WsrChangeReq::where('user_id', $staffid)
      ->where('status', 'Approved')
      ->whereDate('start_date', '<=', $idate)
      ->whereDate('end_date', '>=', $idate)
      ->orderBy('action_date', 'desc')
      ->first();

    if($currwsr){

    } else {
      // no approved change req for that date
      // find the data from SAP
      $currwsr = UserShiftPattern::where('user_id', $staffid)
        ->whereDate('start_date', '<=', $idate)
        ->whereDate('end_date', '>=', $idate)
        ->orderBy('start_date', 'desc')
        ->first();

        if($currwsr){

        } else {
          // also not found. just return OFF1 as default
          $sptr = ShiftPattern::where('code', 'OFF1')->first();
          return $sptr;
        }
    }

    return $currwsr->shiftpattern;
  }

}
