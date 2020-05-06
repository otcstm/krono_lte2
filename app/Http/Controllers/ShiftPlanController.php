<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShiftPlan;
use App\ShiftPlanHistory;
use App\ShiftPlanStaff;
use App\ShiftPlanStaffTemplate;
use App\ShiftPlanStaffDay;
use App\ShiftGroup;
use App\ShiftPattern;
use App\User;
use App\Shared\UserHelper;
use App\Shared\ColorHelper;
use \Carbon\Carbon;
use \Calendar;

use App\Notifications\ShiftPlanSubmitted;
use App\Notifications\ShiftPlanApproved;
use App\Notifications\ShiftPlanMembersApproved;
use App\Notifications\ShiftPlanReverted;
use App\Notifications\ShiftPlanRejected;

class ShiftPlanController extends Controller
{
    public function index(Request $req){

      // $planlist = ShiftPlan::where('creator_id', $req->user()->id)
      //       ->orWhere('approver_id', $req->user()->id)
      //       ->get();
      $grouplist = ShiftGroup::where('manager_id', $req->user()->id)
            ->orWhere('planner_id', $req->user()->id)->orderby('id')->get();

      // dd($grouplist->ShiftPlans);

      $nextmon = Carbon::now()->addMonth()->firstOfMonth();

      // dd(session()->all());

      return view('shiftplan.splan_list', [
        // 'p_list' => $planlist,
        'grouplist' => $grouplist,
        'curdate' => $nextmon->format('Y-m-d')
      ]);
    }

    public function addPlan(Request $req){
      // get the first day of month for the given input date
      $fdom = new Carbon($req->plan_month);
      $cuser = $req->user()->id;

      // check for existing
      $dup = ShiftPlan::where('department', $req->shift_group_id)
        ->whereDate('plan_month', $fdom->firstOfMonth())->first();

      if($dup){
        return redirect()->back()->withInput()->withErrors(['plan_month' => 'Plan already exist for selected month']);
      }

      // get group info
      $sgrp = ShiftGroup::find($req->shift_group_id);

      if($sgrp){

        // check if current user is the owner
        if($sgrp->manager_id != $cuser && $sgrp->planner_id != $cuser){
          return redirect()->back()->withInput()->with(['alert' => 'You are not authorized to create plan for this group', 'a_type' => 'danger']);
        }

        $sp = new ShiftPlan;
        $sp->creator_id = $cuser;
        $sp->plan_month = $fdom->firstOfMonth();
        $sp->name = $sgrp->group_name;
        $sp->department = $sgrp->id;
        $sp->status = 'Planning';
        $sp->approver_id = $sgrp->manager_id;
        $sp->save();

        // log the history
        $sphist = new ShiftPlanHistory;
        $sphist->shift_plan_id = $sp->id;
        $sphist->user_id = $cuser;
        $sphist->action = 'Create';
        $sphist->save();

        // add the members
        foreach($sgrp->Members as $amember){
          $uai = UserHelper::GetUserInfo($amember->user_id)['extra'];

          $numbr = new ShiftPlanStaff;
          $numbr->shift_plan_id = $sp->id;
          $numbr->user_id = $amember->user_id;
          $numbr->plan_month = $sp->plan_month;
          $numbr->status = 'Planning';

          if(isset($uai->last_planned_day)){
            $numbr->start_date = $uai->last_planned_day;
          }

          $numbr->save();
        }

        return redirect(route('shift.view', ['id' => $sp->id], false));
      } else {
        // shift group doesnt exist
        return redirect()->back()->withInput()->withErrors(['shift_group_id' => 'Selected group no longer exist']);
      }
    }

    public function viewDetail(Request $req){
      $sp = ShiftPlan::find($req->id);
      $cuserid = $req->user()->id;

      if($sp){

        $slist = $sp->StaffList;
        $eventlist = [];

        foreach ($slist as $key => $value) {
          $value->col = ColorHelper::GetRandColor();

          if($sp->status == 'Planning'){
            // get last planned for this staff
            $staffExtra = UserHelper::GetUserInfo($value->user_id)['extra'];
            if(isset($staffExtra->last_planned_day)){
              $eventlist[] = Calendar::event(
              $value->User->staff_no,
              true,
              $staffExtra->last_planned_day,
              $staffExtra->last_planned_day,
              $value->id,[
                // 'url' => route('area.evdetail', ['id' => $value->id], false),
                'textColor' => '#ffffff',
                'backgroundColor' => '#000000',

              ]);
            }
          }

          // get the calendar for this staff
          foreach ($value->Templates as $vt) {
            $evdate = new Carbon($vt->end_date);
            $evdate->addDay();
            $eventlist[] = Calendar::event(
            $vt->Pattern->code . '-> ' . $value->User->name,
            true,
            $vt->start_date,
            $evdate,
            $vt->id,[
              // 'url' => route('area.evdetail', ['id' => $value->id], false),
              'textColor' => $value->col['f'],
              'backgroundColor' => $value->col['bg'],

            ]);
          }
        }


        $blankc = Calendar::addEvents($eventlist)->setOptions([
          'defaultDate' => $sp->plan_month,
          'eventLimit' => false
        ]);

        $myrole = 'noone';
        // decide next allowed action
        if($cuserid == $sp->Group->manager_id){
          $myrole = 'approver';
        } elseif ($cuserid == $sp->Group->planner_id) {
          $myrole = 'planner';
        }

        return view('shiftplan.plan_detail', [
          'sp' => $sp,
          'cal' => $blankc,
          'stafflist' => $slist,
          'role' => $myrole
        ]);
      } else {
        return redirect(route('shift.index', [], false))->with(['alert' => 'Selected plan not found', 'a_type' => 'warning']);
      }

    }

    public function editPlan(Request $req){

    }

    public function delPlan(Request $req){
      $sp = ShiftPlan::find($req->id);
      $cuser = $req->user()->id;
      if($sp){
        // check plan ownership
        if($sp->approver_id != $cuser && $sp->creator_id != $cuser){
          return redirect()->back()->withInput()->with(['alert' => 'You are not authorized to delete this shift plan', 'a_type' => 'danger']);
        }

        // check the status of this plan
        if($sp->status != 'Planning'){
          return redirect()->back()->withInput()->with(['alert' => 'Can only delete shift plans that are in Planning stage', 'a_type' => 'danger']);
        }

        // delete the plan
        $sp->delete();

        return redirect(route('shift.index', [], false))->with([
          'alert' => 'Shift plan deleted',
          'a_type' => 'warning'
        ]);
      } else {
        return redirect(route('shift.index', [], false))->with([
          'alert' => 'Selected shift plan not found',
          'a_type' => 'warning'
        ]);
      }
    }

    public function takeActionPlan(Request $req){
      $sifplen = ShiftPlan::find($req->plan_id);
      $cuserid = $req->user()->id;

      if($sifplen){
        $grp = $sifplen->Group;

        // double check eligibility
        if($cuserid == $grp->manager_id){
          if($req->action == 'approve'){
            return $this->approvePlan($sifplen, $cuserid);
          } elseif ($req->action == 'reject') {
            return $this->rejectPlan($sifplen, $cuserid, $req->remark);
          } elseif ($req->action == 'revert') {
            return $this->revertPlan($sifplen, $cuserid, $req->remark);
          }
        }

        // if is planner
        if($cuserid == $grp->planner_id){
          if ($req->action == 'submit') {
            return $this->submitPlan($sifplen, $cuserid);
          }
        }

        return redirect()->back()->with([
          'alert' => 'Action not allowed',
          'a_type' => 'warning'
        ]);

      } else {
        return redirect(route('shift.index', [], false))->with([
          'alert' => 'Shift plan not found: ' . $req->plan_id,
          'a_type' => 'warning'
        ]);
      }


    }

    private function approvePlan(ShiftPlan $theplan, $cuserid){

      // double check curren plan status
      if($theplan->status == 'Approved'){
        return redirect()->back()->with([
          'alert' => 'Shift plan is already approved',
          'a_type' => 'danger'
        ]);
      }

      $theplan->status = 'Approved';
      $theplan->save();

      // add to plan ShiftPlanHistory
      $sphist = new ShiftPlanHistory;
      $sphist->shift_plan_id = $theplan->id;
      $sphist->user_id = $cuserid;
      $sphist->action = 'Approve';
      $sphist->save();
      
      // E_0017
      // php artisan make:notification ShiftPlanApproved
      // Notification to Shift Planner once Group Owner approve Shift Planning.
      // to: Group Planner
      // cc: Group Owner
      
      // user yang akan terima notification tu
      $to_user = User::where('id',$theplan->Group->planner_id)->first();

      // object yang nak dinotify / tengok bila penerima notify tekan link
      $shift_grp = \App\ShiftGroup::where('id', $theplan->Group->id)->first();   
      try{
        // hantar notification ke planner tu, untuk action yang berkaitan
        $to_user->notify(new ShiftPlanApproved($shift_grp, $theplan));
      } catch(\Exception $e){    
      } 
      // also updatae the status for each of the staff plan
      foreach($theplan->StaffList as $asps){
        $asps->status = 'Approved';
        $asps->save();

        // todo: send alert
        $to_user_member = User::where('id',$asps->user_id)->first();
        try{
          // hantar notification ke user tu, untuk action yang berkaitan
            $to_user_member->notify(new ShiftPlanMembersApproved($shift_grp, $theplan, $asps));
        } catch(\Exception $e){    
        } 
      }
            

      return redirect(route('shift.view', ['id' => $theplan->id], false))
        ->with([
          'alert' => 'Shift plan approved',
          'a_type' => 'success'
        ]);

    }

    private function revertPlan(ShiftPlan $theplan, $cuserid, $reason){
      // double check curren plan status
      if($theplan->status != 'Approved'){
        return redirect()->back()->with([
          'alert' => 'Shift plan is not yet approved',
          'a_type' => 'danger'
        ]);
      }

      $theplan->status = 'Planning';
      $theplan->save();

      // add to plan ShiftPlanHistory
      $sphist = new ShiftPlanHistory;
      $sphist->shift_plan_id = $theplan->id;
      $sphist->user_id = $cuserid;
      $sphist->remark = $reason;
      $sphist->action = 'Revert';
      $sphist->save();

      // also updatae the status for each of the staff plan
      foreach($theplan->StaffList as $asps){
        $asps->status = 'Planning';
        $asps->save();
        // todo: send alert
      }

      // E_0019
      // php artisan make:notification ShiftPlanReverted
      // Notification to Shift Planner once Group Owner revert Shift Planning
      // to: Group Planner
      // cc: Group Owner
      
      // user yang akan terima notification tu
      $to_user = User::where('id',$theplan->Group->planner_id)->first();

      // object yang nak dinotify / tengok bila penerima notify tekan link
      $shift_grp = \App\ShiftGroup::where('id', $theplan->Group->id)->first();  

      try{
        // hantar notification ke planner tu, untuk action yang berkaitan
        $to_user->notify(new ShiftPlanReverted($shift_grp, $theplan, $reason));
      } catch(\Exception $e){    
      } 

      return redirect(route('shift.view', ['id' => $theplan->id], false))
        ->with([
          'alert' => 'Shift plan reverted to planning stage',
          'a_type' => 'warning'
        ]);
    }

    private function submitPlan(ShiftPlan $theplan, $cuserid){
      if($theplan->status != 'Planning'){
        return redirect()->back()->with([
          'alert' => 'Shift plan is not in planning stage',
          'a_type' => 'danger'
        ]);
      }

      $theplan->status = 'Submitted';
      $theplan->save();

      // add to plan ShiftPlanHistory
      $sphist = new ShiftPlanHistory;
      $sphist->shift_plan_id = $theplan->id;
      $sphist->user_id = $cuserid;
      $sphist->action = 'Submit';
      $sphist->save();

      // also updatae the status for each of the staff plan
      foreach($theplan->StaffList as $asps){
        $asps->status = 'Submitted';
        $asps->save();
        // todo: send alert
      }

      // E_0016
      // php artisan make:notification ShiftPlanSubmitted
      // Notification to Group Owner once Shift Planner assign work schedule rule to Members (Shift Planning)
      // to: Group Owner
      // cc: Group Planner
      
      // user yang akan terima notification tu
      $to_user = User::where('id',$theplan->Group->manager_id)->first();

      // object yang nak dinotify / tengok bila penerima notify tekan link
      $shift_grp = \App\ShiftGroup::where('id', $theplan->Group->id)->first();    
     
  try{
    // hantar notification ke user tu, untuk action yang berkaitan
    $to_user->notify(new ShiftPlanSubmitted($shift_grp, $theplan));
  } catch(\Exception $e){    
  } 


      return redirect(route('shift.view', ['id' => $theplan->id], false))
        ->with([
          'alert' => 'Shift plan submitted to approver',
          'a_type' => 'success'
        ]);

    }

    private function rejectPlan(ShiftPlan $theplan, $cuserid, $reason){
      if($theplan->status != 'Submitted'){
        return redirect()->back()->with([
          'alert' => 'Shift plan has not being submitted yet',
          'a_type' => 'danger'
        ]);
      }

      $theplan->status = 'Planning';
      $theplan->save();

      // add to plan ShiftPlanHistory
      $sphist = new ShiftPlanHistory;
      $sphist->shift_plan_id = $theplan->id;
      $sphist->user_id = $cuserid;
      $sphist->remark = $reason;
      $sphist->action = 'Reject';
      $sphist->save();

      // also updatae the status for each of the staff plan
      foreach($theplan->StaffList as $asps){
        $asps->status = 'Planning';
        $asps->save();
        // todo: send alert
      }

       // E_0020
      // php artisan make:notification ShiftPlanRejected
      // Notification to Shift Planner once Group Owner revert Shift Planning
      // to: Group Planner
      // cc: Group Owner
      
      // user yang akan terima notification tu
      $to_user = User::where('id',$theplan->Group->planner_id)->first();

      // object yang nak dinotify / tengok bila penerima notify tekan link
      $shift_grp = \App\ShiftGroup::where('id', $theplan->Group->id)->first();  

      try{
        // hantar notification ke planner tu, untuk action yang berkaitan
        $to_user->notify(new ShiftPlanRejected($shift_grp, $theplan, $reason));
      } catch(\Exception $e){    
      } 

      return redirect(route('shift.view', ['id' => $theplan->id], false))
        ->with([
          'alert' => 'Shift plan rejected. Back to planning stage',
          'a_type' => 'warning'
        ]);
    }

    public function staffInfo(Request $req){
      $sps = ShiftPlanStaff::find($req->id);

      if($sps){
        $staffAddInfo = UserHelper::GetUserInfo($sps->user_id);
        $curmon = new Carbon($sps->plan_month);

        // set the max date input
        $maxdate = new Carbon($sps->plan_month);
        $maxdate->addMonth();
        $maxdate->addDays(-1);

        // set min date input
        $mindate = new Carbon($sps->plan_month);
        $mindate->addDays(-7);

        // check for null previous
        if(isset($staffAddInfo['extra']->last_planning_day)){
          $lastplan = new Carbon($staffAddInfo['extra']->last_planning_day);
          $lastplan->addDay();
          // lock the date change ability
          $datelock = 'readonly="readonly"';
        } else {
          // if null, just default it to 1st of the plan month
          $lastplan = new Carbon($sps->plan_month);
          $datelock = '';
        }

        // just in case, if the previous planned day is too far,
        if($lastplan->lt($curmon) && $curmon->diff($lastplan)->days > 15){
          $datelock = '';
        }

        // $spattern = ShiftPattern::all();

        // populate the calendar
        $lastmon = new Carbon($sps->plan_month);
        $lastmon->addMonths(-1);
        $worklists = ShiftPlanStaffDay::where('user_id', $sps->user_id)
          ->whereDate('work_date', '>=', $lastmon)
          ->get();

        $eventlist = [];
        foreach ($worklists as $key => $value) {
          $tday = $value->Day;
          $display = $tday->code . ' (' . $value->StaffTemplate->Pattern->code . ')';
          if($value->is_work_day){
            $fullday = false;
            $stime = $value->start_time;
            $etime = $value->end_time;
          } else {
            $fullday = true;
            $stime = $value->work_date;
            $etime = $value->work_date;
          }

          $eventlist[] = Calendar::event(
          $display,
          $fullday,
          $stime,
          $etime,
          $value->id,[
            // 'url' => route('area.evdetail', ['id' => $value->id], false),
            'textColor' => $tday->font_color,
            'backgroundColor' => $tday->bg_color,

          ]);
        }

        // check if already use all of this month
        $lastmon = new Carbon($sps->plan_month);
        $lastmon->addMonth();


        $blankc = Calendar::addEvents($eventlist)->setOptions(['defaultDate' => $lastplan->format('Y-m-d')]);

        // dd($blankc->getOptionsJson());
        return view('shiftplan.staff_detail', [
          'sps' => $sps,
          // 'patterns' => $spattern,
          'cal' => $blankc,
          'sdate' => $lastplan->format('Y-m-d'),
          'mindate' => $mindate->format('Y-m-d'),
          'maxdate' => $maxdate->format('Y-m-d'),
          'dlock' => $datelock,
          'filled' => $lastplan->gte($lastmon)
        ]);

      } else {
        return redirect()->back()->with(['alert' => 'Selected staff no longer exist in plan group', 'a_type' => 'danger']);
      }
    }

    public function staffPushTemplate(Request $req){
      $cuserid = $req->user()->id;
      $sps = ShiftPlanStaff::find($req->sps_id);
      $staffExtra = UserHelper::GetUserInfo($sps->user_id)['extra'];
      $stemplate = ShiftPattern::find($req->spattern_id);
      $startdate = new Carbon($req->sdate);

      // double check if the start date is before the last planning date
      if(isset($staffExtra->last_planning_day)){
        $lpd = new Carbon($staffExtra->last_planning_day);

        if($startdate->lt($lpd)) {
          return redirect()->back()->withInput()->withErrors([
            'sdate' => 'Already planned until ' . $staffExtra->last_planning_day
          ])->with([
            'alert' => 'Overlapping plan. Please refresh the page to get updated info ' . $startdate,
            'a_type' => 'danger'
          ]);
        }

        // check for gap -- todo?
      }

      // check if selected template exist
      if($stemplate){
      } else {
        return redirect()->back()->withInput()->withErrors([
          'alert' => 'Selected shift template no longer exist. Please refresh the page to get updated list',
          'a_type' => 'danger',
          'spattern_id' => 'Template no longer exist'
        ]);
      }

      if($sps){
        // check plan status
        if($sps->status != 'Planning'){
          return redirect()->back()->with(['alert' => 'No longer in planning stage', 'a_type' => 'danger']);
        }

        $theGroup = $sps->ShiftPlan->Group;

        // check ownership
        if($cuserid != $theGroup->manager_id && $cuserid != $theGroup->planner_id){
          return redirect()->back()->with([
            'alert' => 'You are not authorized to edit this shift group',
            'a_type' => 'danger'
          ]);
        }

        // check if already crossed over the month date limit
        $nexmon = new Carbon($sps->plan_month);
        $nexmon->addMonth()->firstOfMonth();
        $endate = new Carbon($sps->end_date);

        if($endate->gt($nexmon)){
          return redirect()->back()->with([
            'alert' => 'Already overflow to the following month',
            'a_type' => 'warning'
          ]);
        }

        // no issue. append the template
        $nustempl = new ShiftPlanStaffTemplate;
        $nustempl->day_seq = $sps->Templates->count() + 1;
        $nustempl->shift_plan_id = $sps->ShiftPlan->id;
        $nustempl->shift_plan_staff_id = $sps->id;
        $nustempl->shift_pattern_id = $stemplate->id;
        $nustempl->start_date = $startdate->format('Y-m-d');
        $nustempl->save();

        // create the plan days
        $daycount = 0;
        foreach ($stemplate->ListDays->sortBy('day_seq') as $value) {
          $spsd = new ShiftPlanStaffDay;
          $spsd->shift_plan_id = $nustempl->shift_plan_id;
          $spsd->shift_plan_staff_id = $sps->id;
          $spsd->shift_plan_staff_template_id = $nustempl->id;
          $spsd->user_id = $sps->user_id;
          $spsd->day_type_id = $value->day_type_id;
          $spsd->work_date = $startdate->format('Y-m-d');

          // get info about that day type
          $dtype = $value->Day;
          $spsd->is_work_day = $dtype->is_work_day;
          if($dtype->is_work_day){
            $work_start_time = new Carbon($startdate->format('Y-m-d') . ' ' . $dtype->start_time);
            $spsd->start_time = $work_start_time;
            $work_end_time = new Carbon($startdate->format('Y-m-d') . ' ' . $dtype->start_time);
            $work_end_time->addMinutes($dtype->total_minute);
            $spsd->end_time = $work_end_time;
          }

          $spsd->save();

          // increment the day
          $startdate->addDay();
          $daycount++;

        }

        if($daycount > 0){
          // reduce back the day
          $startdate->addDays(-1);
        }


        // update the end date
        $nustempl->end_date = $startdate->format('Y-m-d');
        $nustempl->save();
        // update the planning date
        $staffExtra->last_planning_day = $startdate->format('Y-m-d');
        $staffExtra->save();

        // update the staffplan sums
        ShiftPlanStaff::find($req->sps_id)->updateSums();

        return redirect(route('shift.staff', ['id' => $sps->id], false))->with([
          'alert' => $daycount . ' days added',
          'a_type' => 'success'
        ]);

      } else {
        return redirect(route('shift.group', ['id' => $req->sp_id], false))
          ->with(['alert' => 'Selected staff no longer exist in plan group', 'a_type' => 'danger']);
      }

    }

    public function staffPopTemplate(Request $req){
      $cuserid = $req->user()->id;
      $spst = ShiftPlanStaffTemplate::find($req->id);

      // check if plan exist
      if($spst){
          // check for plan status
          if($spst->StaffPlan->status != 'Planning'){
            return redirect()->back()->with(['alert' => 'No longer in planning stage', 'a_type' => 'danger']);
          }

          $theStaffPlan = $spst->StaffPlan;
          $theGroup = $theStaffPlan->ShiftPlan->Group;

          // check ownership
          if($cuserid != $theGroup->manager_id && $cuserid != $theGroup->planner_id){
            return redirect()->back()->with(['alert' => 'You are not authorized to edit this shift group', 'a_type' => 'danger']);
          }
          // no issues detected

          // undo the last planning day
          $lastpdate = new Carbon($spst->start_date);
          $lastpdate->addDays(-1);
          $staffExtra = UserHelper::GetUserInfo($theStaffPlan->user_id)['extra'];
          $staffExtra->last_planning_day = $lastpdate;
          $staffExtra->save();


          // delete the days of that template
          ShiftPlanStaffDay::where('shift_plan_staff_template_id', $spst->id)->delete();
          // delete the template
          $spst->delete();

          // update back the staff plan info
          $spst->StaffPlan->updateSums();

          // if the total days is 0, just reset back the planning day to be the same as current planned day
          if($theStaffPlan->total_days == 0){
            $staffExtra->last_planning_day = $staffExtra->last_planned_day;
            $staffExtra->save();
          }

          return redirect(route('shift.staff', ['id' => $req->sps_id], false))
            ->with(['alert' => 'Last template removed', 'a_type' => 'success']);

      } else {
        return redirect()->back()->with(['alert' => 'Selected template no longer exist for this staff', 'a_type' => 'danger']);
      }
    }

}
