<?php

namespace App\Shared;

use App\Overtime;
use App\ShiftPlan;


use Illuminate\Support\Facades\Auth;

class AlertHelper
{

  /*
format: code - related notification class - target destination to redirect

list of notification mapping:
sgc - App\Notifications\ShiftGroupCreated - my list of shift group


   */

  public static function LoadNotifyList()
  {
    // dd(Auth::user());
    if (Auth::check()) {
      $nitofylist = Auth::user()->unreadNotifications;

      foreach($nitofylist as $nit){
       $id = $nit->data['id'];
       $route_name = $nit->data['route_name'];
       //dd( Auth::user()->id ) ;  



      };
      
     // dd($nitofylist);

      session([
        'notifylist' => $nitofylist,
        'notifycount' => $nitofylist->count()
      ]);

      // dd(session()->all());
    } else {
      // dd('no login');
    }
  }

  public static function LoadTodoList()
  {
    $user = Auth::user();
    if (Auth::check()) {
      $todocount = 0;
      $todolist = [];

      //For status Draft & Query
      $draftCount = Overtime::where('user_id', $user->id)
        ->whereIn('status', array('D1', 'D2', 'Q1', 'Q2'))->get()->count();
      if ($draftCount != 0) {
        $todocount += $draftCount;

        $arrDraftCount =  [
          'rcount' => $draftCount, // record count
          'route_name' => 'ot.list',
          'text' => 'Claim List  (' . $draftCount . ')',
          'icon' => 'fas fa-user-clock'

        ];

        array_push($todolist, $arrDraftCount);
      }

      //For all pending item Claim Verification
      $verifierCount = Overtime::where('verifier_id', $user->id)
        ->whereIn('status', array('PV'))->get()->count();
      if ($verifierCount != 0) {
        $todocount += $verifierCount;

        $arrVerifierCount =  [
          'rcount' => $verifierCount,
          'route_name' => 'ot.verify',
          'text' => 'Claim Verification (' . $verifierCount . ')',
          'icon' => 'fas fa-user-clock'
        ];
        array_push($todolist, $arrVerifierCount);
      }

      //For all pending item Claim Approval (2)
      $approvalCount = Overtime::where('approver_id', $user->id)
        ->whereIn('status', array('PA'))
        ->get()->count();
      if ($approvalCount != 0) {
        $todocount += $approvalCount;

        array_push($todolist, [
          'rcount' => $approvalCount,
          'route_name' => 'ot.approval',
          'text' => 'Claim Approval (' . $approvalCount . ')',
          'icon' => 'fas fa-user-clock'
        ]);
      }

      //For all pending item Shift Planning Approval (1)
      $shiftplanApprovalCount = ShiftPlan::where('approver_id', $user->id)
        ->where('status', 'Submitted')->get()->count();

      if ($shiftplanApprovalCount != 0) {
        $todocount += $shiftplanApprovalCount;
        
        array_push($todolist, [
          'rcount' => $shiftplanApprovalCount,
          'route_name' => 'shift.index',
          'text' => 'Shift Planning Approval (' . $shiftplanApprovalCount . ')',
          'icon' => 'fas fa-clock '
        ]);
      }


      session([
        'todolist' => $todolist,
        'todocount' => $todocount
      ]);
    } else {
      //  dd('You are not authorised');
    }
  }

  public static function getUrl($notifyobject)
  {

    $data = $notifyobject->data;
    if ($data['param'] == '') {
      $param = [];
    } else {
      $param = [$data['param'] => $data['id']];
    }
    return route($data['route_name'], $param);
  }
}
