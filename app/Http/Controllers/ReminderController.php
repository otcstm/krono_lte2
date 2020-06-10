<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReminderJobDetail;
use App\Overtime;
use \Carbon\Carbon;
use App\Notifications\WeeklyReminder;

class ReminderController extends Controller
{
  public function resend(Request $req){

    if($req->filled('id')){

    } else {
      return back();
    }

    $rjd = ReminderJobDetail::find($req->id);
    if($rjd){
      $user = $rjd->User;

      $user->notify(new WeeklyReminder($rjd));

    } else {
      abort(404);
    }
  }

  public function jobs(Request $req){
    $now = new Carbon;
    dd($now->year);
  }

  public function detail(Request $req){

  }

  public function delete(Request $req){

  }

  public function staff(Request $req){

  }
}
