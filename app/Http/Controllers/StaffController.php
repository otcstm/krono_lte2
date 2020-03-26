<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{

  public function showCalendar(Request $req){
    $cds = \Calendar::addEvents([]);

    return view('staff.workschedule', [
      'cal' => $cds
    ]);
  }


}
