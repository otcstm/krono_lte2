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

  public static function LoadTodoList(){
    // dd(Auth::user());
    if(Auth::check()){

      $todolist = [];
      // Claim List <number of claim with status draft & query>
      $claim_list = \App\Overtime::where('user_id',Auth::user()->id)
      ->whereIn('status',['D1','D2','Q1','Q2'])
      ->get();

      array_push($todolist,[
        'stats' => 1, 
        'name' => 'Claim List', 
        'list_count' => $claim_list->count(),
        'icon' => 'glyphicon glyphicon-ok-circle',
        'icon_color' => 'text-aqua',
        'route' => 'ot.list'
      ]);

      // Claim Verification <number of pending item>
      $claim_verification_stats = 0;
      $claim_verification = \App\Overtime::where('verifier_id',Auth::user()->id)
      ->get();
      if($claim_verification->count() > 0){
        $claim_verification_stats = 1;
      }

      $claim_verification_list = \App\Overtime::where('verifier_id',Auth::user()->id)
      ->where('status','PV')
      ->get();
      
      array_push($todolist,[
        'stats' => $claim_verification_stats, 
        'name' => 'Claim Verification',
        'list_count' => $claim_verification_list->count(),
        'icon' => 'glyphicon glyphicon-ok-circle',
        'icon_color' => 'text-yellow',
        'route' => 'ot.verify'
      ]);

      // Claim Approval <number of pending item>
      $claim_approval_stats = 0;
      $claim_approval = \App\Overtime::where('approver_id',Auth::user()->id)
      ->get();
      if($claim_approval->count() > 0){
        $claim_approval_stats = 1;
      }

      $claim_approval_list = \App\Overtime::where('approver_id',Auth::user()->id)
      ->where('status','PA')
      ->get();
      
      array_push($todolist,[
        'stats' => $claim_approval_stats, 
        'name' => 'Claim Approval',
        'list_count' => $claim_approval_list->count(),
        'icon' => 'glyphicon glyphicon-ok-sign',
        'icon_color' => 'text-red',
        'route' => 'ot.approval'
      ]);

      // Shift Planning Approval <number of pending item>
      $shiftplan_approval_stats = 0;
      $shiftplan_approval = \App\ShiftPlan::where('approver_id',Auth::user()->id)
      ->get();    
      if($shiftplan_approval->count() > 0){
        $shiftplan_approval_stats = 1;
      }  

      $shiftplan_approval_list = \App\ShiftPlan::where('approver_id',Auth::user()->id)
      ->where('status','Submitted')
      ->get();     
      
      array_push($todolist,[
        'stats' => $shiftplan_approval_stats, 
        'name' => 'Shift Planning Approval',
        'list_count' => $shiftplan_approval_list->count(),
        'icon' => 'glyphicon glyphicon-ok-sign',
        'icon_color' => 'text-red',
        'route' => 'shift.index'
      ]); 

      $todocount = $claim_list->count() + $claim_verification_list->count() + $claim_approval_list->count() + $shiftplan_approval_list->count();

      //dd($todolist);

      //$nitofylist = Auth::user()->unreadNotifications;
      session([
        'todolist' => $todolist,
        'todocount' => $todocount
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
