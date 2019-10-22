<?php

namespace App\Http\Controllers;

use App\Overtime;
use Illuminate\Http\Request;

class OvertimeController extends Controller{
    public function show(Request $req){
        $hour = 104;
        $minute = 0;
        $claimedHour = Overtime::where('user_id', $req->user()->id)->sum('total_hour');
        $claimedMinute = Overtime::where('user_id', $req->user()->id)->sum('total_minute');
        for($i = 1, $j = $claimedMinute; $j > 15; $j=$j - 15, $i++){
            if($i==4){
                $claimedHour++;
                $i = 1;
                $claimedMinute = $claimedMinute - 60;
            }
        }
        $hour = $hour - $claimedHour;
        $minute = $claimedMinute;
        $overtime = Overtime::where('user_id', $req->user()->id)->get();
        return view('staff.overtime', ['hour' => $hour, 'minute' => $minute, 'overtimes' => $overtime]);
    }

    public function store(Request $req){
        
    }
}
