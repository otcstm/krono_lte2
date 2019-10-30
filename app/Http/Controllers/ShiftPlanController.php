<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShiftPlan;
use App\ShiftGroup;
use \Carbon\Carbon;

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
      // check for existing

    }

    public function viewDetail(Request $req){

    }

    public function editPlan(Request $req){

    }

    public function delPlan(Request $req){

    }

    public function submitPlan(Request $req){

    }

    public function approvePlan(Request $req){

    }

    public function revertPlan(Request $req){

    }

    public function staffInfo(Request $req){

    }

    public function staffPushTemplate(Request $req){

    }

    public function staffPopTemplate(Request $req){

    }

}
