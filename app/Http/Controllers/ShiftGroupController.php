<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shared\UserHelper;
use App\User;
use App\ShiftGroupMember;
use App\ShiftGroup;
use App\ShiftPattern;

class ShiftGroupController extends Controller
{
  public function index(Request $req){

    $all_subord = UserHelper::GetMySubords($req->user()->id, true);
    $freesubord = [];
    $ingroup = [];

    foreach($all_subord as $os){
      // check if this staff already in a group
      $sgrp = ShiftGroupMember::where('user_id', $os['id'])->first();
      if($sgrp){
        // already in group
        array_push($ingroup, $sgrp);
      } else {
        array_push($freesubord, $os);
      }
    }

    $glist = ShiftGroup::where('manager_id', $req->user()->id)
      ->orWhere('planner_id', $req->user()->id)->get();

    return view('shiftplan.shift_group', [
      'p_list' => $glist
    ]);
  }

  public function addGroup(Request $req){
    // dd($req->all());
    // check for duplicate code
    $dups = ShiftGroup::where('manager_id', $req->group_owner_id)
      ->where('group_code', $req->group_code)->first();

    if($dups){
      return redirect()->back()->withInput()->with(['alert' => 'Duplicate code with ' . $dups->group_name, 'a_type' => 'danger']);
    }

    // no duplicate. proceed create new group
    $nugrup = new ShiftGroup;
    $nugrup->manager_id = $req->group_owner_id;
    $nugrup->group_name = $req->group_name;
    $nugrup->group_code = $req->group_code;
    $nugrup->save();

    return redirect(route('shift.group.view', ['id' => $nugrup->id], false));
  }

  public function viewGroup(Request $req){

    $grup = ShiftGroup::find($req->id);
    if($grup){

      $therestofthepattern = ShiftPattern::whereNotIn('id', $grup->ShiftPatterns->pluck('id'))->get();

      return view('shiftplan.shift_group_detail', [
        'groupd' => $grup,
        'spattern' => $therestofthepattern
      ]);
    } else {
      return redirect(route('shift.group', [], false))->with(['alert' => 'Group not found', 'a_type' => 'warning']);
    }

  }

  public function addSpToGroup(Request $req){

    $grup = ShiftGroup::find($req->group_id);
    if($grup){
      $grup->shiftpatterns()->attach($req->sp_id);
      return redirect(route('shift.group.view', ['id' => $grup->id], false));
        -with([
          'alert' => 'Shift template added to group', 'a_type' => 'info'
        ]);
    } else {
      return redirect(route('shift.group', [], false))->with(['alert' => 'Group not found', 'a_type' => 'warning']);
    }
  }

  public function delSpFromGroup(Request $req){

    $grup = ShiftGroup::find($req->group_id);
    if($grup){

      $grup->shiftpatterns()->detach($req->sp_id);

      return redirect(route('shift.group.view', ['id' => $grup->id], false));
        -with([
          'alert' => 'Shift template removed from group', 'a_type' => 'info'
        ]);
    } else {
      return redirect(route('shift.group', [], false))->with(['alert' => 'Group not found', 'a_type' => 'warning']);
    }
  }

  public function addStaff(Request $req){
    // double check if this staff in any group
    $ingrp = ShiftGroupMember::where('user_id', $req->user_id)->first();
    if($ingrp){
      return redirect()->back()->with(['alert' => 'Selected user already in group', 'a_type' => 'danger']);
    }

    // add to group
    $nugrpmbr = new ShiftGroupMember;
    $nugrpmbr->user_id = $req->user_id;
    $nugrpmbr->shift_group_id = $req->group_id;
    $nugrpmbr->save();

    return redirect(route('shift.group.view', ['id' => $req->group_id], false))->with(['alert' => 'Staff added to group', 'a_type' => 'success']);

  }

  public function removeStaff(Request $req){
    $ingrp = ShiftGroupMember::where('user_id', $req->user_id)
            ->where('shift_group_id', $req->group_id)
            ->first();

    if($ingrp){
      $ingrp->delete();

      return redirect(route('shift.group.view', ['id' => $req->group_id], false))->with(['alert' => 'Staff removed from group', 'a_type' => 'success']);
    } else {
      return redirect(route('shift.group.view', ['id' => $req->group_id], false))->with(['alert' => 'Selected staff is not in this group', 'a_type' => 'warning']);
    }
  }

  public function editGroup(Request $req){
    $cgroup = ShiftGroup::find($req->id);

    if($cgroup){
      $cgroup->group_name = $req->group_name;
      $cgroup->manager_id = $req->group_owner_id;
      $cgroup->save();
      return redirect(route('shift.group.view', ['id' => $req->id], false))->with(['alert' => 'Shift Group updated', 'a_type' => 'success']);
    } else {
      return redirect(route('shift.group', [], false))->with(['alert' => 'Group not found', 'a_type' => 'warning']);
    }
  }

  public function delGroup(Request $req){
    $cgroup = ShiftGroup::find($req->id);
    
    if($cgroup){
      $gname = $cgroup->group_code;
      // remove all member of this group first
      ShiftGroupMember::where('shift_group_id', $cgroup->id)->delete();

      // then only delete this group
      $cgroup->delete();

      return redirect(route('shift.group', [], false))->with(['alert' => 'Shift Group ' . $gname . ' deleted', 'a_type' => 'warning']);
    } else {
      return redirect(route('shift.group', [], false))->with(['alert' => 'Group not found', 'a_type' => 'warning']);
    }
  }

  public function ApiSearchStaff(Request $req){
    $retarr = [];

    if($req->filled('input')){
      // first, try to search by exact persno
      if(is_int($req->input)){
        $yser = User::find($req->input);
        if($yser){
          // found exact persno. return it
          array_push($retarr, [
            'staff_no' => $yser->staff_no,
            'name' => $yser->name,
            'id' => $yser->id
          ]);
          return $retarr;
        }
      }

      // then try search by staff_no
      $yser = User::where('staff_no', $req->input)->first();
      if($yser){
        // found exact staff. return it
        array_push($retarr, [
          'staff_no' => $yser->staff_no,
          'name' => $yser->name,
          'id' => $yser->id
        ]);
        return $retarr;
      }

      // if it reaches here, try to search by name
      $user = User::where('name', 'like', '%' . $req->input . '%')->get();
      foreach ($user as $key => $value) {
        array_push($retarr, [
          'staff_no' => $value->staff_no,
          'name' => $value->name,
          'id' => $value->id
        ]);
      }
    }

    return $retarr;
  }

  public function ApiGetStaffName(Request $req){
    if($req->filled('uid')){
      $user = User::find($req->uid);
      if($user){
        return $user->name;
      }
    }

    return "404";
  }

}
