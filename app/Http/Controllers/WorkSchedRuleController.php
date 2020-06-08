<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WsrChangeReq;
use App\UserShiftPattern;
use App\ShiftPlanStaffDay;
use App\ShiftPattern;
use App\ShiftGroupMember;
use App\ShiftGroup;
use App\DayType;
use \Carbon\Carbon;
use App\Shared\UserHelper;

class WorkSchedRuleController extends Controller
{
  public function wsrPage(Request $req){
    if($req->filled('page')){
      if($req->page == 'myc'){
        return $this->myCalendar($req);
      } elseif ($req->page == 'teamc') {
        return $this->teamCalendar($req);
      } elseif ($req->page == 'reqs') {
        return $this->listChangeWsr($req);
      } else {
        return $this->wsrMainPage($req);
      }
    } else {
      return $this->wsrMainPage($req);
    }
  }

  private function wsrMainPage(Request $req){
    $cbdate = new Carbon;
    $currwsr = UserHelper::GetWorkSchedRule($req->user()->id, $cbdate);

    $currwsr = WsrChangeReq::where('user_id', $req->user()->id)
      ->where('status', 'Approved')
      ->whereDate('end_date', '>=', $cbdate)
      ->orderBy('start_date', 'desc')
      ->first();

    if($currwsr){

    } else {
      // no approved change req for that date
      // find the data from SAP
      $currwsr = UserShiftPattern::where('user_id', $req->user()->id)
        ->whereDate('start_date', '<=', $cbdate)
        ->whereDate('end_date', '>=', $cbdate)
        ->orderBy('start_date', 'desc')
        ->first();

        if($currwsr){

        } else {
          // also not found. just return OFF1 as default
          $sptr = ShiftPattern::where('code', 'OFF1')->first();

          $sdate = new Carbon;
          $edate = Carbon::maxValue();
          $cspid = $sptr->id;
        }
    }

    if($currwsr){
      $sdate = new Carbon($currwsr->start_date);
      $edate = new Carbon($currwsr->end_date);
      $cspid = $currwsr->shift_pattern_id;
    }

    $planlist = ShiftPattern::where('is_weekly', true)->get();
    $isShiftPlanMem = ShiftGroupMember::where('user_id',$req->user()->id)->count();
    if($isShiftPlanMem > 0){
      $isShiftPlanMem = 1;
    }
    else{
      $isShiftPlan = 0;
    }

    return view('staff.workschedulemain', [
      'cspid' => $cspid,
      'sdate' => $sdate,
      'edate' => $edate,
      'planlist' => $planlist,
      'isShiftPlanMem' => $isShiftPlanMem
    ]);
  }

  public function doEditWsr(Request $req){
    // check for overlapping dates
    $overlap = WsrChangeReq::where('user_id', $req->user()->id)
      ->whereDate('start_date', '<', $req->end_date)
      ->whereDate('end_date', '>', $req->start_date)
      ->first();

    if($overlap){
      return redirect(route('staff.worksched', [], false))->with([
        'feedback' => true,
        'feedback_title' => 'Error',
        'feedback_text' => 'Date range overlapped with existing request.'
      ]);
    }

    // create the request entry
    $wsreq = new WsrChangeReq;
    $wsreq->user_id = $req->user()->id;
    $wsreq->shift_pattern_id = $req->spid;
    $wsreq->start_date = $req->start_date;
    $wsreq->end_date = $req->end_date;
    $wsreq->superior_id = $req->user()->reptto;
    $wsreq->status = 'Pending Approval';
    $wsreq->save();

    return redirect(route('staff.worksched', [], false))->with([
      'feedback' => true,
      'feedback_title' => 'Successfully Submit',
      'feedback_text' => 'You have successfully submit the request to your approver. Your new work schedule will take effect one approved.'
    ]);
  }

  public function myCalendar(Request $req){
    if($req->filled('mon')){
      $indate = new Carbon($req->mon);
    } else {
      $indate = new Carbon();
    }

    $monlabel = $indate->format('F');
    $ylabel = $indate->format('Y');

    $monNext = date('Y-m-d', strtotime('+1 month', strtotime($indate)));
    $monPrev = date('Y-m-d', strtotime('-1 month', strtotime($indate)));

    $startdate = new Carbon($indate->firstOfMonth());
    $endate = $indate->lastOfMonth();
    $endate->addSecond();

    $daterange = new \DatePeriod(
      $startdate,
      \DateInterval::createFromDateString('1 day'),
      $endate
    );

    $head = [];
    foreach($daterange as $ad){
      array_push($head, $ad->format('d-D'));
    }

    $my = $this->getShiftCal($req->user()->id, $daterange);
    
    return view('staff.workcalendar', [
      'mon' => $monlabel,
      'yr' => $ylabel,
      'header' => $head,
      'data' => $my,
      'monNext' => $monNext,
      'monPrev' => $monPrev     
    ]);

  }

  public function teamCalendar(Request $req){
    if($req->filled('mon')){
      $indate = new Carbon($req->mon);
    } else {
      $indate = new Carbon();
    }

    $monlabel = $indate->format('F');
    $ylabel = $indate->format('Y');

    $monNext = date('Y-m-d', strtotime('+1 month', strtotime($indate)));
    $monPrev = date('Y-m-d', strtotime('-1 month', strtotime($indate)));

    $startdate = new Carbon($indate->firstOfMonth());
    $endate = $indate->lastOfMonth();
    $endate->addSecond();

    $daterange = new \DatePeriod(
      $startdate,
      \DateInterval::createFromDateString('1 day'),
      $endate
    );

    $head = ['Staff ID', 'Name'];
    foreach($daterange as $ad){
      array_push($head, $ad->format('d-D'));
    }

    $caldata = [];

    // find team member
    $mysg = ShiftGroupMember::where('user_id', $req->user()->id)->first();
    if($mysg){
      foreach($mysg->Group->Members as $amember){
        $my = $this->getShiftCal($amember->User->id, $daterange);
        array_push($caldata, [
          'id' => $amember->User->id,
          'staffno' => $amember->User->staff_no,
          'name' => $amember->User->name,
          'data' => $my
        ]);
      }
    }

    $gid_self = [0];
    if($mysg){
        array_push($gid_self,$mysg->shift_group_id);
    }
    // manager_id, find team member, multi group
    $mgr_sg = ShiftGroup::where('manager_id', $req->user()->id)
    ->whereNotin('id',$gid_self)
    ->get();

    if($mgr_sg){
      foreach($mgr_sg as $amgrgrp){
        foreach($amgrgrp->Members as $amember){
          $my = $this->getShiftCal($amember->User->id, $daterange);
          array_push($caldata, [
            'id' => $amember->User->id,
            'staffno' => $amember->User->staff_no,
            'name' => $amember->User->name,
            'data' => $my
          ]);
        }
      }
    }

    //  planner_id, find team member, multi group
    $plnr_sg = ShiftGroup::where('planner_id', $req->user()->id)
    ->whereNotin('id',$gid_self)
    ->get();
    
    if($plnr_sg){
      foreach($plnr_sg as $aplnnnergrp){
        foreach($aplnnnergrp->Members as $amember){
          $my = $this->getShiftCal($amember->User->id, $daterange);
          array_push($caldata, [
            'id' => $amember->User->id,
            'staffno' => $amember->User->staff_no,
            'name' => $amember->User->name,
            'data' => $my
          ]);
        }
      }
    }

    return view('staff.workteamcalendar', [
      'mon' => $monlabel,
      'yr' => $ylabel,
      'header' => $head,
      'staffs' => $caldata,
      'monNext' => $monNext,
      'monPrev' => $monPrev      
    ]);
  }

  private function getShiftCal($staff_id, $daterange){
    $rv = [];
    foreach ($daterange as $key => $value) {
      $sd = ShiftPlanStaffDay::where('user_id', $staff_id)
        ->whereDate('work_date', $value)
        ->first();

      if($sd){
        array_push($rv, [
          'type' => $sd->Day->code,
          'type_descr' => $sd->Day->description,
          'time' => $sd->Day->getTimeRange()
        ]);
      } else {
        array_push($rv, [
          'type' => 'N/A',
          'type_descr' => 'N/A',
          'time' => ''
        ]);
      }
    }

    return $rv;
  }

  public function listChangeWsr(Request $req){
    $pendingapp = WsrChangeReq::where('superior_id', $req->user()->id)
      ->where('status', 'Pending Approval')->get();
    $myown = WsrChangeReq::withTrashed()->where('user_id', $req->user()->id)->get();

    return view('staff.workschedulereqs', [
      'requests' => $pendingapp,
      'mine' => $myown
    ]);

  }

  public function doApproveWsr(Request $req){
    $wcr = WsrChangeReq::find($req->id);
    // check if the req exists
    if($wcr){
      // check if current user is the superior
      if($wcr->superior_id != $req->user()->id){
        return redirect(route('staff.worksched', ['page' => 'reqs'], false))->with([
          'feedback' => true,
          'feedback_title' => 'Not Allowed',
          'feedback_text' => 'You are not allowed to take action on this request'
        ]);
      }

      // check current status
      if($wcr->status != 'Pending Approval'){
        return redirect(route('staff.worksched', ['page' => 'reqs'], false))->with([
          'feedback' => true,
          'feedback_title' => 'Not Allowed',
          'feedback_text' => 'Selected request no longer require approval'
        ]);
      }

      // do the approval process
      $cbdate = new Carbon;
      if($req->action == 'Approve'){
        $wcr->status = 'Approved';
        $wcr->action_date = $cbdate;
        $wcr->remark = $req->remark;
        $wcr->save();
      } else {
        $wcr->status = 'Rejected';
        $wcr->action_date = $cbdate;
        $wcr->remark = $req->remark;
        $wcr->save();
        $wcr->delete();
      }

      return redirect(route('staff.worksched', ['page' => 'reqs'], false))->with([
        'feedback' => true,
        'feedback_title' => 'Request '. $wcr->status,
        'feedback_text' => 'The selected change request has been processed'
      ]);

    } else {
      return redirect(route('staff.worksched', ['page' => 'reqs'], false))->with([
        'feedback' => true,
        'feedback_title' => 'Request Not Found',
        'feedback_text' => 'The selected change request does not exist'
      ]);
    }

  }

  public function ApiGetWsrDays(Request $req){
    $datsp = ShiftPattern::find($req->id);
    $dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
    if($datsp){
      $retv = [];
      foreach($datsp->ListDays as $oneday){
        $dayindex = $oneday->day_seq % 7;

        array_push($retv, [
          'day' => $dowMap[$dayindex],
          'time' => $oneday->Day->getTimeRange()
        ]);

      }
      return $retv;
    } else {
      return [];
    }
  }
}
