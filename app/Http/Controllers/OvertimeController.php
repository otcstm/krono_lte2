<?php

namespace App\Http\Controllers;

use App\Shared\TimeHelper;
use App\Overtime;
use App\OvertimeMonth;
use Illuminate\Http\Request;

class OvertimeController extends Controller{
    public function showx(Request $req){
        $hour = 104;
        $minute = 0;
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

    public function show(){

        // $time = TimeHelper::GetTime();
        // $claimHour = OvertimeMonth::where('user_id')
        $otlist = Overtime::where('user_id', $req->user()->id)->get();
        return view('staff.overtime', ['otlist' => $otlist]);
        
        // dd(date('c'));
    }

    public function store(Request $req){
        
    }
}
