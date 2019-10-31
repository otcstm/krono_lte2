<?php

namespace App\Http\Controllers;

use App\Shared\TimeHelper;
use App\Overtime;
use App\OvertimeMonth;
use App\OvertimeDetail;
use Session;
use Illuminate\Http\Request;

class OvertimeController extends Controller{
    public function showx(Request $req){
        $hour = 104;
        $minute = 0;
        
        // $claimmonth = date_parse_from_format("Y-m-d", $claimdate);
        // $claimedHour = Overtime::where('user_id', $req->user()->id)->sum('total_hour');
        // $claimedMinute = Overtime::where('user_id', $req->user()->id)->sum('total_minute');
        // for($i = 1, $j = $claimedMinute; $j > 15; $j=$j - 15, $i++){
        //     if($i==4){
        //         $claimedHour++;
        //         $i = 1;
        //         $claimedMinute = $claimedMinute - 60;
        //     }
        // }
        // $hour = $hour - $claimedHour;
        // $minute = $claimedMinute;
        // $carbon = Carbon::now();
        // $time = new DateTime('NOW');
        // $time->setTimezone(new DateTimeZone('+0800'));
        // $claimTime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $time->year())->where('month', $time->month());
        // $month = $time->year;
        
        $overtime = Overtime::where('user_id', $req->user()->id)->get();
        dd(date('c'));
        return view('staff.overtime', ['claimTime' => $claimTime, 'hour' => $hour, 'minute' => $minute, 'overtimes' => $overtime]);
        
    }

    public function showOT(Request $req){
        $otlist = Overtime::where('user_id', $req->user()->id)->get();
        return view('staff.overtime', ['otlist' => $otlist]);
    }

    public function showDetails(Request $req){
        $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->
        where('year', date("y", strtotime($req->session()->get('claimdate'))))->where('month', date("m", strtotime($req->session()->get('claimdate'))))->first();
        $otlist = OvertimeDetail::where('ot_id', $req->session()->get('claim')->id)->get();
        if($req->session()->has('staffs')) {
            return view('staff.otdetails',[
                'claimtime' => $claimtime, 'claimdate' => $req->session()->get('claimdate'), 'claimday' => $req->session()->get('claimday'), 'claim' => $req->session()->get('claim'), 'otlist' =>  $req->session()->get('otlist'),
              'feedback' => $req->session()->get('feedback'),
              'feedback_text' => $req->session()->get('feedback_text'),
              'feedback_icon' => $req->session()->get('feedback_icon'),
              'feedback_color' =>  $req->session()->get('feedback_color')
            ]);
          }else{
            return view('staff.otdetails', ['claimtime' => $claimtime, 'claimdate' => $req->session()->get('claimdate'), 'claimday' => $req->session()->get('claimday'), 'claim' => $req->session()->get('claim'), 'otlist' =>  $otlist]);
          }
    }

    public function create(Request $req){
        $claimdate = $req->inputdate;
        $claimmonth = date("m", strtotime($claimdate));
        $claimyear = date("y", strtotime($claimdate));
        $claimday = date("l", strtotime($claimdate));
        $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->first();
        if(empty($claimtime)){
            $newmonth = new OvertimeMonth;
            $newmonth->hour = 104;
            $newmonth->minute = 0;
            $newmonth->user_id = $req->user()->id;
            $newmonth->year = $claimyear;
            $newmonth->month = $claimmonth;
            $newmonth->save();
        }
        $claim = Overtime::where('user_id', $req->user()->id)->where('date', $claimdate)->first();
        if(empty($claim)){
            $ref = "OT".date("ymd", strtotime($claimdate))."-".sprintf("%08d", $req->user()->id)."-".rand(10000,99999);
            $newclaim = new Overtime;
            $newclaim->refno = $ref;
            $newclaim->user_id = $req->user()->id;
            $newclaim->date = $claimdate;
            $newclaim->date_created = date("Y-m-d");
            $newclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));;
            $newclaim->total_hour = 0;
            $newclaim->total_minute = 0;
            $newclaim->status = 'Draft';
            $newclaim->save();
            $claim = Overtime::where('user_id', $req->user()->id)->where('date', $claimdate)->first();
        }
        Session::put(['claimdate' => $claimdate, 'claimday' => $claimday, 'claim' => $claim]);
        return redirect(route('ot.showDetails',[],false));
    }

    public function addtime(Request $req){        
        $dif = (strtotime($req->inputend) - strtotime($req->inputstart))/60;
        $hour = (int) ($dif/60);
        $minute = $dif%60;
        $availableclaim = OvertimeDetail::where('ot_id', $req->inputid)->get();
        $x= 0;
        foreach($availableclaim as $singleuser){
            if((strtotime($singleuser->start_time)>=strtotime($req->inputstart)&&strtotime($singleuser->start_end)<=strtotime($req->inputstart))
                ||(strtotime($singleuser->start_time)>=strtotime($req->inputend)&&strtotime($singleuser->start_end)<=strtotime($req->inputend))
                ||(strtotime($req->inputstart)>=strtotime($singleuser->start_time)&&strtotime($req->inputstart)<=strtotime($singleuser->end_time))
                ||(strtotime($req->inputend)>=strtotime($singleuser->start_time)&&strtotime($req->inputend)<=strtotime($singleuser->end_time))){
                return redirect(route('ot.showDetails',[],false))->with([
                    'feedback' => true,
                    'feedback_text' => "There is already a duplicate with your time range input!",
                    'feedback_icon' => "remove",
                    'feedback_color' => "#D9534F"]
                );
                exit();
            }
        }
        
        $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', date("y", strtotime($req->inputdate)))->where('month', date("m", strtotime($req->inputdate)))->first();
        $totalleft=($claimtime->hour*60)+$claimtime->minute;
        if($totalleft>=$dif){
            $newclaim = new OvertimeDetail;
            $newclaim->ot_id = $req->inputid;
            $newclaim->start_time = $req->inputdate." ".$req->inputstart.":00";
            $newclaim->end_time = $req->inputdate." ".$req->inputend.":00";
            $newclaim->hour = $hour;
            $newclaim->minute = $minute;
            $newclaim->justification = $req->inputremark;
            $newclaim->save();
            $updatemonth = OvertimeMonth::find($claimtime->id);
            $updatemonth->hour = ((int)(($totalleft-$dif)/60));
            $updatemonth->minute = (($totalleft-$dif)%60);
            $updatemonth->save();
            return redirect(route('ot.showDetails',[],false));
        }else{
            return redirect(route('ot.showDetails',[],false))->with([
                'feedback' => true,
                'feedback_text' => "Available time left to claim is not enough!",
                'feedback_icon' => "remove",
                'feedback_color' => "#D9534F"]
            );
        }
    }

    public function store(Request $req){
        
    }

}
