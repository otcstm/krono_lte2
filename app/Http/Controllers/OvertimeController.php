<?php

namespace App\Http\Controllers;

use App\Shared\TimeHelper;
use App\Overtime;
use App\OvertimeMonth;
use App\OvertimeDetail;
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

    public function show(Request $req){
        $otlist = Overtime::where('user_id', $req->user()->id)->get();
        return view('staff.overtime', ['otlist' => $otlist]);
    }

    public function create(Request $req){
        $claimdate = $req->inputdate;
        $claimmonth = date("m", strtotime($claimdate));
        $claimyear = date("y", strtotime($claimdate));
        $claimday = date("l", strtotime($claimdate));
        $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->get();
        if(count($claimtime)==0){
            $newmonth = new OvertimeMonth;
            $newmonth->hour = 104;
            $newmonth->minute = 0;
            $newmonth->user_id = $req->user()->id;
            $newmonth->year = $claimyear;
            $newmonth->month = $claimmonth;
            $newmonth->save();
            $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->get();
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
        $claim_id = $claim->id;
        $otlist = OvertimeDetail::where('ot_id', $claim_id)->get();
        
        return view('staff.createOT', ['claimtime' => $claimtime, 'claimdate' => $claimdate, 'claimday' => $claimday, 'claim' => $claim, 'otlist' => $otlist]);
    }

    public function store(Request $req){
        
    }
}
