<?php

namespace App\Shared;

use App\Shared\UserHelper;
use App\Shared\URHelper;
use App\User;
use App\UserLog;
use App\Overtime;
use App\OvertimeDetail;
use App\OvertimeLog;
use App\OvertimeFormula;
use App\OvertimeEligibility;
use App\OvertimePunch;
use App\StaffPunch;
use App\WsrChangeReq;
use App\UserShiftPattern;
use App\ShiftPlan;
use App\ShiftPlanStaffDay;
use App\ShiftPattern;
use App\DayType;
use App\DayTag;
use App\Salary;
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

  public static function GetShiftCal($staff_id, $daterange){
    $rv = [];
    foreach ($daterange as $key => $value) {
      $sd = ShiftPlanStaffDay::where('user_id', $staff_id)
        ->whereDate('work_date', $value)
        ->first();

      if($sd){
        array_push($rv, [
          'type' => $sd->Day->description,
          'time' => $sd->Day->getTimeRange(),
          'bg' => '',
          'dateWork' => $sd->work_day
        ]);
      } else {
        array_push($rv, [
          'type' => 'N/A',
          'time' => '',
          'bg' => 'pink',
          'dateWork' => ''
        ]);
      }
    }

    return $rv;
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
    $req = Request::all();
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

    public static function CalOT($otdid){
      // dd("s");
      $otd = OvertimeDetail::where('id', $otdid)->first();
      $ot = Overtime::where('id', $otd->ot_id)->first();
      $ur = URHelper::getUserRecordByDate($ot->user_id, $ot->date);
      $cd = UserHelper::CheckDay($ot->user_id, $ot->date);
      $dt = DayType::where("id", $cd[4])->first();
      $salary=$ur->salary+$ur->allowance;
      if($ur->ot_salary_exception == "Y"){
        $salary=$ur->salary+$ur->allowance;
      }else{
        $oe = URHelper::getUserEligibility($ot->user_id, $ot->date);
        if($oe){
          if($salary>$oe->salary_cap){
            $salary = $oe->salary_cap;
          }
        }
      }
      //check if there's any shift planned for this person
      $wd = ShiftPlanStaffDay::where('user_id', $ot->user_id)->whereDate('work_date', $ot->date)->first();
      if($wd){
        $whmax = $dt->working_hour;
        $whmin = $dt->working_hour/2;
      } else {
        $whmax = 7;
        $whmin = 3.5;
      }

      if($dt->day_type=="N"){ //=================================================NORMAL
        $dayt = "NOR";
        $lg = OvertimeFormula::where('company_id',$ur->company_id)->where('region',$ot->region)
        ->where("day_type", $dayt)->first();
        if(26*$dt->working_hour==0){
          $amount = 0;
        }else{
          $amount= $lg->rate*(($salary)/(26*7))*((($otd->hour*60)+$otd->minute)/60);
        }

      }else{
        if($dt->day_type=="PH"){ //=================================================PUBLIC HOLIDAY
          $dayt = "PHD";
          $lg = OvertimeFormula::query();
          $lg = $lg->where('company_id',$ur->company_id)
          ->where('region',$ot->region)->where("day_type", $dayt)
          ->where('min_hour','<=',$otd->hour)
          ->where('max_hour','>=',$otd->hour);
          if((($otd->hour*60)+$otd->minute)>($whmax*60)){
            $lg = $lg->where('min_minute', 1)
            ->orderby('id')->first();
            $lg2 = OvertimeFormula::where('company_id',$ur->company_id)
            ->where('region',$ot->region)->where("day_type", $dayt)
            ->where('min_hour',0)->where('min_minute', 0)
            ->orderby('id')->first();
            if(26*$dt->working_hour==0){
              $amount = 0;
            }else{
              // $amount2= $lg2->rate*(($salary+$ur->allowance)/(26*$dt->working_hour))*($whmax);
              $amount2= $lg2->rate*(($salary)/(26*7))*(7);
              $amount= $amount2 + ($lg->rate*(($salary)/(26*7))*(((($otd->hour*60)+$otd->minute)-(7*60))/60));
              // $amount= $amount2 + ($lg->rate*(($salary+$ur->allowance)/(26*$dt->working_hour))*(((($otd->hour*60)+$otd->minute)-($whmax*60))/60));
            }
          }else{
            $lg = $lg->where('min_minute', 0)
            ->orderby('id')->first();
            $amount= $lg->rate*(($salary)/26);
          }
  
        }else if($dt->day_type=="R"){ //=================================================RESTDAY
          $dayt = "RST";
          if((($otd->hour*60)+$otd->minute)<=($whmin*60)){
            $amount= 0.5*(($salary)/26);
  
  
          }else if((($otd->hour*60)+$otd->minute)>($whmax*60)){
            if(26*$dt->working_hour==0){
              $amount = 0;
            }else{ 
              $amount2= 1*(($salary)/26);
              $amount= $amount2+(2*(($salary)/(26*7))*(((($otd->hour*60)+$otd->minute)-(7*60))/60));
              // $amount= $amount2+(2*(($salary+$ur->allowance)/(26*$dt->working_hour))*(((($otd->hour*60)+$otd->minute)-($whmax*60))/60));
            }
  
          }else{
            $amount= 1*(($salary)/26);
          }
          
          // $lg = $lg->first();
          // $legacy = $lg->legacy_codes;
  
        }else{
          $dayt = "OFF";
          if(26*$dt->working_hour==0){
            $amount = 0;
          }else{
            $amount= 1.5*(($salary)/(26*7))*((($otd->hour*60)+$otd->minute)/60);
          }
        }
        
      }
      return $amount;
    }

    // public static function CalOT($salary, $h, $m)
    // {
    //   $time = ($h*60)+$m;
    //   $work = 26*7*60;
    //   $rate = $salary/$work;
    //   $pay = 1.5*$rate*$time;
    //   return $pay;
    // }

    public static function CheckLeave($user, $date)
    {
      $leave = Leave::where('user_id', $user)->whereDate('start_date','<=',$date)->whereDate('end_date','>=',$date)->orderby('upd_sap', "DESC")->first();
      if($leave){
        return $leave;
      }
      return null;
    }

    public static function CheckDay($user, $date)
    {
      $day = date('N', strtotime($date));
      $shift = null;
      $ph = null;
      $hc = null;
      $hcc = null;
      $sp = null;
      $sameday = true;
      $ed = "24:00";
      $wd = null;
      
      // first, check if there's any shift planned for this person
      $usp = UserShiftPattern::where("user_id", $user)
        ->whereDate('start_date','<=', $date)
        ->whereDate('end_date','>=', $date)->first();        

      $shiftpattern = ShiftPattern::where('code', $usp->sap_code)->first();
      if($shiftpattern->is_weekly != 1){
        $wd = ShiftPlanStaffDay::where('user_id', $user)
          ->whereDate('work_date', $date)->first();
        if($wd){
          $sp = ShiftPlan::where("id", $wd->shift_plan_id)->first();
          if($sp){
            if($sp->status=="Approved"){
              $shift = "Yes";
            }else{
              $wd = null;
            }
          }else{
            $wd = null;
          }
        }
      }

      if($wd){
        $ph = Holiday::where("dt", date("Y-m-d", strtotime($date)))->first();
      } else {
        // not a shift staff. get based on the wsr
        $ph = Holiday::where("dt", date("Y-m-d", strtotime($date)))->first();
        $currwsr = UserHelper::GetWorkSchedRule($user, $date);
        // then get that day
        $wd = $currwsr->ListDays->where('day_seq', $day)->first();
      };
      // get the day info
      $theday = $wd->Day;
      $idday = $wd->day_type_id;
      // $ph = Holiday::where("dt", date("Y-m-d", strtotime($date)))->first();
      // dd($ph);
      // if($ph!=null){
      //   $hcc = HolidayCalendar::where('holiday_id', $ph->id)->get();
      // }
      // // dd($hcc);
      // if($hcc){
      //   if(count($hcc)!=0){
      //   //   // $userstate = UserRecord::where('user_id', $user)->where('upd_sap','<=',$date)->first();
      //     $userstate = URHelper::getUserRecordByDate($user,$date);
      //     // dd($userstate);
      //   //   // $hcal =  HolidayCalendar::where('state_id', $userstate->state_id)->get();
      //   //   $hc = HolidayCalendar::where('holiday_id', $ph->id)->where('state_id', $userstate->state_id)->first();
      //     foreach($hcc as $phol){
      //       $hc = HolidayCalendar::where('id', $phol->id)->first();
      //       // dd($phol->id);
      //       // dd($hc);
      //       if($hc->state_id == $userstate->state_id){
      //         break;
      //       }else{
      //         $hc = null;
      //       }
      //     }
      //   }
      // }
      
        //check if exist in day_tags table
        // $checkDayTagsExist = DayTag::where('user_id', $user)
        // ->where('status', 'ACTIVE')
        // ->where('phdate', date("Y-m-d", strtotime($date)))
        // ->orWhere('date', date("Y-m-d", strtotime($date)))
        // ->first();
        // //dd($checkDayTagsExist);
        // //must check also if existing overtime is exist, else the wd2 + date2 problem

        // //if not exist then populate PH tagging
        // if(!$checkDayTagsExist){
          $populate_phtagging = UserHelper::populatePhTag($user, $date);
        // }

      $checkphday = DayTag::where('user_id', $user)
      ->where('date', date("Y-m-d", strtotime($date)))
      ->where('status', 'ACTIVE')
      ->first(); 
      // dd($checkphday);
      if($checkphday!=null){
      //   if($shift=="Yes"){
      //     $wd = ShiftPlanStaffDay::where('user_id', $user)
      //     ->whereDate('work_date', date("Y-m-d", strtotime($date."+1 day")))->first();
      //     $theday = $wd->Day;
      //     $stime = new Carbon($theday->start_time);
      //     $ed =  $stime->format('H:i');
          
      //   }else{
      //     $start = "00:00";
      //     $end =  "00:00";
      //   }
      // $day_type = 'Public Holiday';
      //   //$dy = DayType::where('description', 'Public Holiday')->first();
        $dy = DayType::where('code', 'PH')->first();
        $idday = $dy->id;
      }
      // }else{
      if($theday->is_work_day == true){
        $day_type = 'Normal Day';
        $stime = new Carbon($theday->start_time);
        $etime = new Carbon($theday->start_time);
        $etime->addMinutes($theday->total_minute);

        if( $stime->format('Y-MM-DD') != $etime->format('Y-MM-DD')){
          $sameday = false;
        }
        $start = $stime->format('H:i');
        $end =  $etime->format('H:i');
        $sd = $start;
        
        if($checkphday!=null){
          $day_type = 'Public Holiday';
          $start = $stime->format('H:i');
          $end = $stime->format('H:i');
        }
      } else {
        
        if($shift=="Yes"){
        
          $stime = new Carbon($theday->start_time);
          $etime = new Carbon($theday->start_time);
          $etime->addMinutes($theday->total_minute);
          if( $stime->format('Y-MM-DD') != $etime->format('Y-MM-DD')){
            $sameday = false;
          }
          $start = $stime->format('H:i');
          $sd = $start;
          $end =  $etime->format('H:i');
          $day_type = $theday->description;
          if($checkphday!=null){
            $day_type = 'Public Holiday';
            $start = $stime->format('H:i');
            $end = $stime->format('H:i');
          }
        }else{
          $start = "00:00";
          $end =  "00:00";
          $day_type = $theday->description;
          $sd = $start;
          if($checkphday!=null){
            $day_type = 'Public Holiday';
          }
        }
      }
      
      if($shift=="Yes"){
        $wd = ShiftPlanStaffDay::where('user_id', $user)
        ->whereDate('work_date', date("Y-m-d", strtotime($date."+1 day")))->first();
        $theday = $wd->Day;
        $stime = new Carbon($theday->start_time);
        $ed =  $stime->format('H:i');
        
      }
      // return ["09:43", "00:00", $day_type, $day, $wd->day_type_id];
      return [$start, $end, $day_type, $day, $idday, $ed, $sameday, $date , $sd];
      //[0] Start work time
      //[1] End work time
      //[2] Day type
      //[3] Day name
      //[4] Day id
      //[5] End of day cycle
      //[6] Is same day or not
      //[7] Date
      //[8] Start day
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

        // dd($currwsr);
        if($currwsr){

        } else {
          // also not found. just return OFF1 as default
          $sptr = ShiftPattern::where('code', 'OFF1')->first();
          return $sptr;
        }
    }

        // dd($currwsr->shiftpattern);
    return $currwsr->shiftpattern;
  }

  public static function GetUserShiftPatternSAP($otid, $otdate){
    $ushiftp = UserShiftPattern::where('user_id', $otid)
            ->whereDate('start_date','<=', $otdate)
            ->whereDate('end_date','>=', $otdate)->first();
    if($ushiftp){
      $sapc = $ushiftp->sap_code;
    }else{
      $sapc = "OFF1";
    }
    return $sapc; 
  }    

  public static function GetWageLegacyAmount($otid){
    $ot = Overtime::where('id', $otid)->first();
    $ur = URHelper::getUserRecordByDate($ot->user_id, $ot->date);
    $cd = UserHelper::CheckDay($ot->user_id, $ot->date);
    $dt = DayType::where("id", $cd[4])->first();
    $salary=$ur->salary+$ur->allowance;
      if($ur->ot_salary_exception == "N"){
        $oe = URHelper::getUserEligibility($ot->user_id, $ot->date);
        if($oe){
          if($salary>$oe->salary_cap){
            $salary = $oe->salary_cap;
          }
        }
      }
    //check if there's any shift planned for this person
    $wd = ShiftPlanStaffDay::where('user_id', $ot->user_id)->whereDate('work_date', $ot->date)->first();
    if($wd){
      $whmax = $dt->working_hour;
      $whmin = $dt->working_hour/2;
    } else {
      $whmax = 7;
      $whmin = 3.5;
    }
    if($dt->day_type=="N"){ //=================================================NORMAL
      $dayt = "NOR";
      $lg = OvertimeFormula::where('company_id',$ur->company_id)->where('region',$ot->region)
      ->where("day_type", $dayt)->first();
      $legacy = $lg->legacy_codes;
      if(26*$dt->working_hour==0){
        $amount = 0;
      }else{
        $amount= $lg->rate*(($salary)/(26*7))*($ot->total_hours_minutes);
      }

    }else{
      if($dt->day_type=="PH"){ //=================================================PUBLIC HOLIDAY
        $dayt = "PHD";
        $lg = OvertimeFormula::query();
        $lg = $lg->where('company_id',$ur->company_id)
        ->where('region',$ot->region)->where("day_type", $dayt)
        ->where('min_hour','<=',$ot->total_hour)
        ->where('max_hour','>=',$ot->total_hour);
        if($ot->total_hours_minutes>$whmax){
          $lg = $lg->where('min_minute', 1)
          ->orderby('id')->first();
          $lg2 = OvertimeFormula::where('company_id',$ur->company_id)
          ->where('region',$ot->region)->where("day_type", $dayt)
          ->where('min_hour',0)->where('min_minute', 0)
          ->orderby('id')->first();
          if(26*$dt->working_hour==0){
            $amount = 0;
          }else{
            $amount2= $lg2->rate*(($salary)/(26*7))*($whmax);
            $amount= $amount2 + ($lg->rate*(($salary)/(26*7))*($ot->total_hours_minutes - $whmax));
          }
        }else{
          $lg = $lg->where('min_minute', 0)
          ->orderby('id')->first();
          $amount= $lg->rate*(($salary)/26);
        }
        $legacy = $lg->legacy_codes;

      }else if($dt->day_type=="R"){ //=================================================RESTDAY
        $dayt = "RST";
        if($ot->total_hours_minutes<=$whmin){
          if($ot->region=="SEM"){
            $legacy = '052';
          }else if($ot->region=="SBH"){
            $legacy = '152';
          }else{
            $legacy = '252';
          }
          $amount= 0.5*(($salary)/26);
          // dd($ot->total_hours_minutes." s");

        }else if($ot->total_hours_minutes>$whmax){
          if($ot->region=="SEM"){
            $legacy = '054';
          }else if($ot->region=="SBH"){
            $legacy = '154';
          }else{
            $legacy = '254';
          }
          if(26*$dt->working_hour==0){
            $amount = 0;
          }else{ 
            $amount2= 1*(($salary)/26);
            $amount= $amount2+(2*(($salary)/(26*7))*($ot->total_hours_minutes-$whmax));
          }
          // dd($ot->total_hours_minutes." ss");
        }else{
          // dd($ot->total_hours_minutes." sss");
          if($ot->region=="SEM"){
            $legacy = '053';
          }else if($ot->region=="SBH"){
            $legacy = '153';
          }else{
            $legacy = '253';
          }
          $amount= 1*(($salary)/26);
        }
        
        // $lg = $lg->first();
        // $legacy = $lg->legacy_codes;
      }else{
        $dayt = "OFF";
        $lg = OvertimeFormula::where('company_id',$ur->company_id)->where('region',$ot->region)->where("day_type", $dayt)->first();
        if($lg){
          $legacy = $lg->legacy_codes;
        }else{
          $legacy = '05K';
        }
        if(26*$dt->working_hour==0){
          $amount = 0;
        }else{
          $amount= 1.5*(($salary)/(26*7))*($ot->total_hours_minutes);
        }
      }
      
    }
    // $lg = OvertimeFormula::where('company_id',$ot->company_id)->where('region',$ot->region)->where("day_type", $dayt)->where('min_hour','<=',$ot->total_hour)->where('min_minute','<=', $ot->total_minute)->where('max_hour','>',$ot->total_hour)->where('max_minute','>', $ot->total_minute)->first();
    // dd($lg);
    // $legacy = $lg->legacy_code;
    return [$legacy, $amount];
  }


  
  public static function populatePhTag($uid, $otdate){

    $us = User::find($uid);
    // ->where('empsgroup','Non Executive')
    // ->where('empstats',3)
    // ->get(); 

    $prev_daytocheck = 3;    

    $startdate = date("Y-m-d", strtotime($otdate. "-".$prev_daytocheck." days"));    
    //dd($startdate, $prev_daytocheck);
    // foreach($user as $us){
    //ini_set('max_execution_time', 60);
    // $logPhTagAct = UserHelper::LogUserAct(
    //   $req, "OVERTIME-PHTAG", "866, START uid:".$uid." Selected:".$otdate." SDateLoop".$startdate); 
        for($i = 1; $i <= $prev_daytocheck; $i++){
            $ph = null;
            $hc = null;
            $hcc = null;
            $wd = null;
            $statuschange = false;
            $date = date("Y-m-d", strtotime($startdate. "+". $i. " days"));
            $day = date('N', strtotime($date));
            //$logPhTagAct = UserHelper::LogUserAct(
            //  $req, "OVERTIME-PHTAG", "866, FOR(".$i.") uid:".$uid." date:".$date." Selected:".$otdate." SDateLoop".$startdate); 
            $ushiftp = UserHelper::GetUserShiftPatternSAP($us->id, date('Y-m-d', strtotime($date." 00:00:00")));
            $shiftpattern = ShiftPattern::where('code', $ushiftp)->first();
            if($shiftpattern->is_weekly != 1){
                $wd = ShiftPlanStaffDay::where('user_id', $us->id)
                ->whereDate('work_date', $date)
                ->orderby('id','desc')
                ->first();
                if($wd){
                    $sp = ShiftPlan::where("id", $wd->shift_plan_id)->first();
                    if($sp){
                        if($sp->status=="Revert"){
                            $statuschange = true;
                        }else if($sp->status!="Approved"){
                            $wd = null;
                        }
                    }else{
                        $wd = null;
                    }
                }
            }
            
            if($wd){
            } else {              
                // not a shift staff. get based on the wsr
                $currwsr = UserHelper::GetWorkSchedRule($us->id, $date);

                //if wsr no record
                if($currwsr){
                    // then get that day
                    $wd = $currwsr->ListDays->where('day_seq', $day)->first();
                } else {
                    $wd = null;
                }
            };
            //if($wd->day_type_id){  

            if($wd){              
                $idday = $wd->day_type_id;
                $dy = DayType::where('id', $idday)->first();
                $ph = Holiday::where("dt", date("Y-m-d", strtotime($date)))->first();
                if($ph!=null){
                    $hcc = HolidayCalendar::where('holiday_id', $ph->id)->get();
                }
                if($hcc){
                    if(count($hcc)!=0){
                        $userstate = URHelper::getUserRecordByDate($us->id,$date);
                        foreach($hcc as $phol){
                            $hc = HolidayCalendar::where('id', $phol->id)->first();
                            if($hc->state_id == $userstate->state_id){
                                break;
                            }else{
                                $hc = null;
                            }
                        }
                    }
                }
                
          //$logPhTagAct = UserHelper::LogUserAct(
          //  $req, "OVERTIME-PHTAG", "927, hc?:".$hc." date:".$date." Selected:".$otdate." SDateLoop".$startdate);  
                if($hc){  //if public holiday exist
                    if($dy->day_type!="R"){
                        $existTag = DayTag::where('user_id', $us->id)->where('date', $date)->first();                        
                        if(!($existTag)){
                            $tagPH = new DayTag;
                            $tagPH->user_id = $us->id;
                            $tagPH->date = $date;
                            $tagPH->phdate = $date;
                            $tagPH->status = "ACTIVE";
                            $tagPH->save();
                        }else{
                            $tagPH = DayTag::find($existTag->id);
                            if($statuschange){
                                $tagPH->status = "INACTIVE";
                                $tagPH->save();
                            }else{
                                $tagPH->status = "ACTIVE";
                                $tagPH->save();
                            }
                        }
                    }else{
                      
                        $dt = $dy->day_type;
                        $x = 1;
                        $existTag = null; 
                        //ini_set('max_execution_time', 3);
                        while(($dt=="O")||($dt=="R")||($dt=="PH")||($existTag)){                          
                            $existTag = null;
                            $wd2 = null;
                            $date2 = date("Y-m-d", strtotime($date. "+".$x." days"));
                            $day = date('N', strtotime($date2));
                            //$logPhTagAct = UserHelper::LogUserAct($req, "OVERTIME-PHTAG", 
                            //"950, WHILE(".$x.") dt2:".$date2." dt:".$dt." day:".$day." existag:".$existTag." wd2:".$wd2." Selected:".$otdate." RunDate".$startdate);
                            
                            $ushiftp = UserHelper::GetUserShiftPatternSAP($us->id, date('Y-m-d', strtotime($date2." 00:00:00")));
                            $shiftpattern = ShiftPattern::where('code', $ushiftp)->first();
                            if($shiftpattern->is_weekly != 1){
                                $wd2 = ShiftPlanStaffDay::where('user_id', $us->id)
                                ->whereDate('work_date', $date2)->first();
                                if($wd2){
                                    $sp2 = ShiftPlan::where("id", $wd2->shift_plan_id)->first();
                                    if($sp2){
                                        if($sp->status=="Revert"){
                                        }else if($sp->status!="Approved"){
                                            $wd2 = null;
                                        }
                                    }else{
                                        $wd = null;
                                    }
                                }
                            }
                            if($wd2){
                            } else {
                                // not a shift staff. get based on the wsr
                                $currwsr = UserHelper::GetWorkSchedRule($us->id, $date2);
                                // then get that day
                                $wd2 = $currwsr->ListDays->where('day_seq', $day)->first();
                            };
                            
                            $dx = DayType::where('id', $wd2->day_type_id)->first();
                            $dt = $dx->day_type;
                            $existTag = DayTag::where('user_id', $us->id)->where('phdate', $date)->first();
                            $x++;

                            //added this because infinite loop
                            if($dt=='N'){
                              break;
                            }
                        }
                        if(!($existTag)){
                            $tagPH = new DayTag;
                            $tagPH->user_id = $us->id;
                            $tagPH->date = $date2;
                            $tagPH->phdate = $date;
                            // $tagPH->status = $dt;
                            $tagPH->status = "ACTIVE";
                            $tagPH->save();
                        }
                    }
                }else{  //if public holiday cancel
                  
            //UserHelper::LogUserAct(
            //$req, "OVERTIME-PHTAG", "1012, xhc:".$hc." date:".$date." Selected:".$otdate." SDateLoop".$startdate);  

                    $existTag = DayTag::where('user_id', $us->id)->where('phdate', $date)->first();
                    if($existTag){
                        $tagPH = DayTag::find($existTag->id);
                        $tagPH->status = "INACTIVE";
                        $tagPH->save();
                    }
                }
            }
        }
        //$logPhTagAct = UserHelper::LogUserAct(
        //  $req, "OVERTIME-PHTAG", "1024, END date:".$date." Selected:".$otdate." SDateLoop".$startdate); 
    
  }

}
