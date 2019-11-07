<?php

namespace App\Http\Controllers;

use App\Shared\TimeHelper;
use App\Overtime;
use App\OvertimeMonth;
use App\OvertimeDetail;
use Session;
use Illuminate\Http\Request;

class OvertimeController extends Controller{
    public function list(Request $req){
        $otlist = Overtime::where('user_id', $req->user()->id)->orderBy('status')->orderBy('date')->get();
        return view('staff.overtime', ['otlist' => $otlist]);
    }

    public function form(Request $req){
        return view('staff.otform', []);
    }
}