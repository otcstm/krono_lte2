<?php

namespace App\Http\Controllers;

use App\Overtime;
use Illuminate\Http\Request;

class OvertimeController extends Controller{
    public function show(Request $req){
        $overtime = Overtime::where('user_id', $req->user()->id)->get();
        return view('staff.overtime', ['overtimes' => $overtime]);
    }
}
