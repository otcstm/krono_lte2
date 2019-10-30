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
use App\Shared\UserHelper;
use \Carbon\Carbon;
use \Calendar;

class ShiftPlanController extends Controller
{
    public function index(Request $req){

      $planlist = ShiftPlan::all();
      $grouplist = ShiftGroup::where('manager_id', $req->user()->id)
            ->orWhere('planner_id', $req->user()->id)->get();

      $nextmon = Carbon::now()->addMonth()->firstOfMonth();

      return view('shiftplan.splan_list', [
        'p_list' => $planlist,
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
      $blankc = Calendar::addEvents([]);
      if($sp){
        return view('shiftplan.plan_detail', [
          'sp' => $sp,
          'cal' => $blankc
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

    public function submitPlan(Request $req){

    }

    public function approvePlan(Request $req){

    }

    public function revertPlan(Request $req){

    }

    public function staffInfo(Request $req){
      $sps = ShiftPlanStaff::find($req->id);
      $spattern = ShiftPattern::all();
      if($sps){

        return view('shiftplan.staff_detail', [
          'sps' => $sps,
          'patterns' => $spattern
        ]);

      } else {
        return redirect()->back()->with(['alert' => 'Selected staff no longer exist in plan group', 'a_type' => 'danger']);
      }
    }

    public function staffPushTemplate(Request $req){

    }

    public function staffPopTemplate(Request $req){

    }

}
