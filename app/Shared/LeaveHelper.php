<?php

namespace App\Shared;

use App\User;
use App\UserRecord;
use App\Leave;
use Illuminate\Http\Request;
use GuzzleHttp\Client;



class LeaveHelper
{
  public static function getLeave($userid,$dt)
  {
   $userLeave = Leave::where('user_id',$userid)->where('start_date','<=',$dt)
   ->where('end_date','>=',$dt)->get();

   
   return $userLeave ;

  }
}
