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
use App\StaffAdditionalInfo;
use App\User;
use App\Shared\UserHelper;
use App\Shared\ColorHelper;
use \Carbon\Carbon;
use \Calendar;
use DB;

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
        $startdate = new Carbon($sp->plan_month);
        $startdate->firstOfMonth();
        $enddate = new Carbon($startdate);
        $enddate->addMonth();

        $daterange = new \DatePeriod(
          $startdate,
          \DateInterval::createFromDateString('1 day'),
          $enddate
        );

        $head = [];
        foreach($daterange as $ad){
          array_push($head, $ad->format('d-D'));
        }

        foreach ($slist as $key => $value) {
          array_push($eventlist, [
            'id' => $value->User->id,
            'name' => $value->User->name,
            'data' => UserHelper::GetShiftCal($value->User->id, $daterange)
          ]);
        }

        $myrole = 'noone';
        // decide next allowed action
        if($cuserid == $sp->Group->manager_id){
          $myrole = 'approver';
        } elseif ($cuserid == $sp->Group->planner_id) {
          $myrole = 'planner';
        }

        return view('shiftplan.plan_detail', [
          'sp' => $sp,
          'header' => $head,
          'cal' => $eventlist,
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
      $sps_stafflist = $sp->StaffList->pluck('user_id');
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

        //new update fix last_planning_day
        $sps = ShiftPlanStaff::select('user_id','plan_month', DB::raw('max(end_date) as max_plan_enddate'))
        ->whereIn('user_id',$sps_stafflist)
        ->groupBy('user_id')
        ->get();
        //dd($sps);

        foreach($sps as $asps){
          
          $upd_lastplanningdate = $asps->max_plan_enddate;

          //update staff_additional_info
          $staffExtra = UserHelper::GetUserInfo($asps->user_id)['extra'];          
          $staffExtra->last_planning_day = $upd_lastplanningdate;
          $staffExtra->save();
        }


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
        $startdate = new Carbon($sps->plan_month);
        $startdate->firstOfMonth();
        $enddate = new Carbon($startdate);
        $enddate->addMonth();

        $daterange = new \DatePeriod(
          $startdate,
          \DateInterval::createFromDateString('1 day'),
          $enddate
        );

        $head = [];
        foreach($daterange as $ad){
          array_push($head, $ad->format('d-D'));
        }

        $blankc[] = [
          'data' => UserHelper::GetShiftCal($sps->user_id, $daterange)
        ];

        // check if already use all of this month
        $lastmon = new Carbon($sps->plan_month);
        $lastmon->addMonth();

        //new changes enable backdated 20200811
        //related need to add validation cant redundant date @ 
        //method overwrite condition using staffadditioninfo->last_planning_day
        //$filled = $lastplan->gte($lastmon); 
        $filled = false;

        $fd_planMonth = $sps->plan_month;
        $ed_planMonth = $sps->plan_month->endOfMonth();

        //check selceted month has plan or not
        $spsd = ShiftPlanStaffDay::where('user_id',$sps->user_id)
        //->where('shift_plan_id',$sps->id)
        ->whereBetween('work_date', [$fd_planMonth, $ed_planMonth])
        ->orderby('work_date','desc')
        ->first();

        //check record for selected month
        if($sps->end_date){      
            //has record. get max date -> add 1 day
            $lastmon = new Carbon($sps->end_date);
            $sdate_default = $lastmon->addDay();    
            $datelock = 'readonly="readonly"';    
        } else{
          //no record sps, check spsd default it to min work_date         
          if($spsd){  
            $mindate_selectedmonth =   new Carbon($spsd->work_date);
            $sdate_default = $mindate_selectedmonth->addDay();
          } else{
            $sdate_default = new Carbon($sps->plan_month);
          }  
          $datelock = '';
        }
        // dd($sdate_default, $lastmon, $lastplan, $sps, $fd_planMonth, $ed_planMonth, $spsd);

        // dd($blankc->getOptionsJson());
        return view('shiftplan.staff_detail', [
          'sps' => $sps,
          // 'patterns' => $spattern,
          'header' => $head,
          'cal' => $blankc,
          'sdate' => $sdate_default->format('Y-m-d'),
          'mindate' => $mindate->format('Y-m-d'),
          'maxdate' => $maxdate->format('Y-m-d'),
          'dlock' => $datelock,          
          // disable this 
          //'filled' => $lastplan->gte($lastmon)
          'filled' => $filled
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
      $hour_gap = intVal(30);
      $warning_msg = "";
      
      // disable for backdated 20200811
      // double check if the start date is before the last planning date
      // if(isset($staffExtra->last_planning_day)){
      //   $lpd = new Carbon($staffExtra->last_planning_day);

      //   if($startdate->lt($lpd)) {
      //     return redirect()->back()->withInput()->withErrors([
      //       'sdate' => 'Already planned until ' . $staffExtra->last_planning_day
      //     ])->with([
      //       'alert' => 'Overlapping plan. Please refresh the page to get updated info ' . $startdate,
      //       'a_type' => 'danger'
      //     ]);
      //   }

      //   // check for gap -- todo?
        
      // }

      // add checking cannot redundant template
      $dayToAdd = (($stemplate->days_count)-1);

      $fd_template = new Carbon($req->sdate);

      $ed_template = new Carbon($req->sdate);
      $ed_template = $ed_template->addDay($dayToAdd);

      $spsd = ShiftPlanStaffDay::where('user_id',$sps->user_id)
        ->whereBetween('work_date', [$fd_template, $ed_template])
        ->orderby('work_date','asc')
        ->first();
      //dd($fd_template, $ed_template, $spsd, $spsd->ShiftPlan);

      // if got template in between
      if($spsd) {
        $min_dt_overlap = new carbon($spsd->work_date);
        $sf_planmonth_overlap = $spsd->ShiftPlan->plan_month->format('Ym').' ('.$spsd->ShiftPlan->plan_month->format('M Y').')';

          return redirect()->back()->withInput()->withErrors([
            'sdate' => 'Already has plan between this template.'
          ])->with([
            'alert' => 'Overlapping with other plan. ' . $sf_planmonth_overlap,
            'a_type' => 'danger'
          ]);
      };        


      //check gap 30hour from last working day in "shift pattern day"
      $spsdall = ShiftPlanStaffDay::where('user_id',$sps->user_id)
      ->whereDate('work_date','<',$req->sdate)
      ->where('is_work_day', 1)
      ->orderBy('work_date','desc')
      ->first();

      //2nd option get previous "shift pattern" and get column start time
      if($spsdall)
      {      
        $sdtm_prev = $spsdall->start_time;

        $sd_add = $startdate;  
        $std1 = $stemplate->ListDays->where('day_seq','1');
        foreach($stemplate->ListDays->where('day_seq','1') as $aDay){
          $dtype = $aDay->Day;
          $sdtm_add = new Carbon($startdate->format('Y-m-d') . ' ' . $dtype->start_time);        
        }

        //fetch date -1 day from start selection shift pattern
        $sdate_prev = date('Y-m-d',strtotime('-1 day',strtotime($sdtm_add)));
        //fetch time working hour maximum date previous
        $stime_prev = date('H:i:s',strtotime($sdtm_prev));
        //new create date time
        $sdtm_prev = new Carbon($sdate_prev.' '.$stime_prev);
        $timestamp1 = strtotime($sdtm_prev);
        $timestamp2 = strtotime($sdtm_add);

        $diff_hour = abs($timestamp2 - $timestamp1)/(60*60);
        $min_nextdatetime = date('Y-m-d H:i:s',strtotime('+'.$hour_gap.' hour',strtotime($sdtm_prev)));
        //dd(date('Y-m-d H:i:s',$timestamp1), date('Y-m-d H:i:s',$timestamp2), $timestamp1, $timestamp2, $diff_hour);

        //if gap between shift pattern less than 30 hour return error
        if((int)$diff_hour < (int)$hour_gap){
          //RPM instruct just give message 25/6/2020
          $warning_msg = 'Shift pattern’s rest day does not meet the required minimum hours by Employment Act. Please contact your respective HCBD for further information.';
          // return redirect(route('shift.staff', ['id' => $sps->id], false))
          // ->with([
          //   'alert' => 'Selected Shift Pattern less than '.$hour_gap.' hours from previous shift pattern. Please select shift pattern with first day start time atleast '.date('d-m-Y H:i',strtotime($min_nextdatetime)),
          //   'a_type' => 'warning'
          // ]);
        }
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
        $nexmon = $nexmon->addMonth()->firstOfMonth();
        //check if end_date is null - 20200804
        if(empty($sps->end_date)){
          $endate = $nexmon;    
        }else{
          $endate = new Carbon($sps->end_date);
        }
        //dd($nexmon, $endate, $sps);
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
          'alert' => $daycount . ' days added.',
          'a_type' => 'success',
          'warning_msg' => $warning_msg
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

          // check if the plan already has claim. 20200811
          // because the team agree can revert plan even has a claim on it
          //dd($spst);
          

          // no issues detected


          // delete the days of that template
          ShiftPlanStaffDay::where('shift_plan_staff_template_id', $spst->id)->delete();
          // delete the template
          $spst->delete();

          // update back the staff plan info
          $spst->StaffPlan->updateSums();

          // if the total days is 0, just reset back the planning day to be the same as current planned day
          if($theStaffPlan->total_days == 0){
            $theStaffPlan->start_date = null;
            $theStaffPlan->end_date = null;
            $theStaffPlan->save();
          }         

            //new update fix last_planning_day
            $lastmaxsps = ShiftPlanStaff::select('user_id','plan_month', DB::raw('max(end_date) as max_plan_enddate'))
            ->where('user_id',$theStaffPlan->user_id)
            ->groupBy('user_id')
            ->first();
            //dd($lastmaxsps);

            $upd_lastplanningdate = $lastmaxsps->max_plan_enddate;

            //update staff_additional_info
            $staffExtra1 = UserHelper::GetUserInfo($theStaffPlan->user_id)['extra'];  
            $staffExtra1->last_planning_day = $upd_lastplanningdate;
            $staffExtra1->save();

          return redirect(route('shift.staff', ['id' => $req->sps_id], false))
            ->with(['alert' => 'Last template removed', 'a_type' => 'success']);

      } else {

        return redirect()->back()->with(['alert' => 'Selected template no longer exist for this staff', 'a_type' => 'danger']);
      }
    }

}
