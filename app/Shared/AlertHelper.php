<?php

namespace App\Shared;


use Illuminate\Support\Facades\Auth;

class AlertHelper {

  /*
format: code - related notification class - target destination to redirect

list of notification mapping:
sgc - App\Notifications\ShiftGroupCreated - my list of shift group


   */

  public static function LoadNotifyList(){
    // dd(Auth::user());
    if(Auth::check()){
      $nitofylist = Auth::user()->unreadNotifications;
      session([
        'notifylist' => $nitofylist,
        'notifycount' => $nitofylist->count()
      ]);

      // dd(session()->all());
    } else {
      // dd('no login');
    }
  }

  public static function getUrl($notifyobject){

    $data = $notifyobject->data;
    if($data['param'] == ''){
      $param = [];
    } else {
      $param = [$data['param'] => $data['id']];
    }
    return route($data['route_name'], $param);
  }

}
