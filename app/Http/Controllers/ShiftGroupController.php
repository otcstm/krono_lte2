<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shared\UserHelper;
use App\User;
use App\ShiftGroupMember;
use App\ShiftGroup;
use App\ShiftPattern;
use App\CompanyShiftPattern;
use App\ViewShiftGroup;
use DB;

use App\Notifications\GroupOwnerAssigned;
use App\Notifications\GroupPlannerAssigned;
use App\Notifications\GroupMemberAssigned;

class ShiftGroupController extends Controller
{
  public function index(Request $req){

    $glist = ShiftGroup::all();

    $new_gid = DB::table('INFORMATION_SCHEMA.TABLES')
    ->select('AUTO_INCREMENT as id')
    ->where('TABLE_SCHEMA','otcs')
    ->where('TABLE_NAME','shift_groups')
    ->first();

    //$autogen_gcode = "G".str_pad($new_gid->id,11,"0",STR_PAD_LEFT);
    $autogen_gcode = "GS".$new_gid->id;
    //dd($autogen_gcode);

    return view('shiftplan.shift_group', [
      'p_list' => $glist,
      'autogen_gcode' => $autogen_gcode 
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


    // user yang akan terima notification tu
    $to_user = User::where('id',$req->group_owner_id)->first();

    //$hcbd_role = DB::Table('role_user')->where('role_id',3);
    //$hcbd_role_list = $hcbd_role->pluck('user_id')->toArray();

    $cc_user = \App\User::where('id',$req->user()->id);
    $cc_user_list = $cc_user->pluck('email')->toArray();

    // object yang nak dinotify / tengok bila penerima notify tekan link
    $shift_grp = \App\ShiftGroup::where('id', $nugrup->id)->first();
    try{
      // hantar notification ke user tu, untuk action yang berkaitan
      $to_user->notify(new GroupOwnerAssigned($shift_grp,$cc_user_list));
    } catch(\Exception $e){
    }

    return redirect(route('shift.group.view', ['id' => $nugrup->id], false));

  }

  public function addGroupWithPlanner(Request $req){
    //dd($req->all());
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
    $nugrup->planner_id = $req->planner_id;
    $nugrup->save();


    // group owner yang akan terima notification tu
    $to_user = User::where('id',$req->group_owner_id)->first();

    //$hcbd_role = DB::Table('role_user')->where('role_id',3);
    //$hcbd_role_list = $hcbd_role->pluck('user_id')->toArray();

    $cc_user = \App\User::where('id',$req->user()->id);
    $cc_user_list = $cc_user->pluck('email')->toArray();

    // object yang nak dinotify / tengok bila penerima notify tekan link
    $shift_grp = \App\ShiftGroup::where('id', $nugrup->id)->first();
    try{
      // hantar notification ke user tu, untuk action yang berkaitan
      $to_user->notify(new GroupOwnerAssigned($shift_grp,$cc_user_list));
    } catch(\Exception $e){
    }

    // planner yang akan terima notification tu
    $to_user = User::where('id',$req->planner_id)->first();
    $cc_user = User::select('email')
    ->where('id',$req->group_owner_id)->first();
   
    // object yang nak dinotify / tengok bila penerima notify tekan link
    $shift_grp = \App\ShiftGroup::where('id', $nugrup->id)->first();
   
    try{
       // hantar notification ke user tu, untuk action yang berkaitan
       $to_user->notify(new GroupPlannerAssigned($shift_grp,$cc_user));
    } catch(\Exception $e){
    }

    return redirect(route('shift.mygroup.view', ['sgid' => $nugrup->id], false));

  }

  public function viewGroup(Request $req){

    $grup = ShiftGroup::find($req->id);
    if($grup){

      //dd($grup->Manager->company_id);
      $SpFilterByComp = CompanyShiftPattern::where('company_id', $grup->Manager->company_id)
      ->distinct('company_id')
      ->get();
      
      $therestofthepattern = [];  
      if($SpFilterByComp->count() > 0)
      {
        $therestofthepattern = ShiftPattern::whereIn('id', $SpFilterByComp->pluck('shift_pattern_id'))
        ->whereNotIn('id', $grup->ShiftPatterns->pluck('id'))
        ->where('is_weekly', false)->get();   
      }
      // else{
      //   // $therestofthepattern = ShiftPattern::whereNotIn('id', $grup->ShiftPatterns->pluck('id'))
      //   // ->where('is_weekly', false)->get();   
      // }    

      //dd($therestofthepattern); 
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
      return redirect(route('shift.group.view', ['id' => $grup->id], false))
        ->with([
          'alert' => 'Shift template added to group', 'a_type' => 'success disabled'
        ]);
    } else {
      return redirect(route('shift.group', [], false))->with(['alert' => 'Group not found', 'a_type' => 'warning']);
    }
  }

  public function delSpFromGroup(Request $req){

    $grup = ShiftGroup::find($req->group_id);
    if($grup){

      $grup->shiftpatterns()->detach($req->sp_id);

      return redirect(route('shift.group.view', ['id' => $grup->id], false))
        ->with([
          'alert' => 'Shift template removed from group', 'a_type' => 'warning disabled'
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

    // E_0015
    // Notification to Team Members once Group Owner add members
    // to: Group Member
    // cc: Group Owner, Group Planner

    // user yang akan terima notification tu
    $to_user = User::where('id',$req->user_id)->first();

    // object yang nak dinotify / tengok bila penerima notify tekan link
    $shift_grp = \App\ShiftGroup::where('id', $req->group_id)->first();

    $cc_user_list = [];
    $cc_user_owner = $shift_grp->Manager->email;
    array_push($cc_user_list, $cc_user_owner);

    if($shift_grp->Planner){
      $cc_user_planner = $shift_grp->Planner->email;
      array_push($cc_user_list, $cc_user_planner);
    }

  try{
    // hantar notification ke user tu, untuk action yang berkaitan
    $to_user->notify(new GroupMemberAssigned($shift_grp,$cc_user_list,$to_user));
  } catch(\Exception $e){
  }

    return redirect(route('shift.mygroup.view', ['sgid' => $req->group_id], false))
    ->with(['alert' => 'Staff added to group', 'a_type' => 'success']);

  }

  public function removeStaff(Request $req){
    $ingrp = ShiftGroupMember::find($req->id);

    if($ingrp){
      $grpid = $ingrp->shift_group_id;
      $ingrp->delete();

      return redirect(route('shift.mygroup.view', ['sgid' => $grpid], false))->with(['alert' => 'Staff removed from group', 'a_type' => 'secondary']);
    } else {
      return redirect()->back()->with(['alert' => 'Selected staff is not in this group', 'a_type' => 'warning']);
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

  public function mygroup(Request $req){
    $dups = ShiftGroup::where('manager_id', $req->user()->id)->get();

    if($req->filled('sgid')){
      $tsg = ShiftGroup::find($req->sgid);

      if($tsg){
        if($tsg->manager_id != $req->user()->id){
          return redirect(route('shift.mygroup'))->with([
            'alert' => 'You are not the owner of that group',
            'a_type' => 'warning'
          ]);
        }

        $plannername = '';
        if(isset($tsg->planner_id) && $tsg->planner_id != 0){
          $plannername = $tsg->Planner->name;
        }

        return view('shiftplan.mygroup', [
          'p_list' => $dups,
          'sgid' => $req->sgid,
          'grp' => $tsg,
          'planner_name' => $plannername
        ]);

      }
      else {
        // group 404
        return redirect(route('shift.mygroup'))->with([
          'alert' => 'Shift group not found',
          'a_type' => 'warning'
        ]);
        }


    }
    else{
      return view('shiftplan.mygroup', [
        'p_list' => $dups
      ]);
    }

  }

  public function mygroupdetail(Request $req){

    // skip if no shift group id
    if($req->filled('sgid')){
      // return redirect(route('shift.mygroup'))
      // ->with([
      //   'sgid' => $req->sgid
      // ]);
    } else {
      return redirect(route('shift.mygroup'));
    }


    $tsg = ShiftGroup::find($req->sgid);

    if($tsg){
      if($tsg->manager_id != $req->user()->id){
        return redirect(route('shift.mygroup'))->with([
          'alert' => 'You are not the owner of that group',
          'a_type' => 'warning'
        ]);
      }

      $plannername = '';
      if(isset($tsg->planner_id) && $tsg->planner_id != 0){
        $plannername = $tsg->Planner->name;
      }

      $allgrp = ShiftGroupMember::all();
      $allgrp_list_member = $allgrp->pluck('user_id')->toArray();

      $ingrp = ShiftGroupMember::where('shift_group_id',$req->sgid)->orderby('id')->get();
      $ingrp_list = $ingrp->pluck('user_id')->toArray();
      // foreach ($ingrp as $row) {
      //   array_push($ingrp_list, $row->User->id);
      // }
      
      $reptto_list_id = $this->getListReptto($req, $tsg->manager_id);    

      $outgrp = User::whereIn('persno', $reptto_list_id)
      ->whereNotIn('persno',$allgrp_list_member)
      ->get();

      return view('shiftplan.mygrpdetail', [
        //'p_list' => $dups,
        'sgid' => $req->sgid,
        'grp' => $tsg,
        'planner' => $plannername,
        'ingrp' => $ingrp,
        'outgrp' => $outgrp,

      ]);

    } else {
      // group 404
      return redirect(route('shift.mygroup'))->with([
        'alert' => 'Shift group not found',
        'a_type' => 'warning'
      ]);
    }


  }

  public function mygroupsetplanner(Request $req){
    // malas way to skip if not enough param
    if($req->filled('sgid')){
    } else {
      return redirect(route('shift.mygroup'));
    }

    if($req->filled('planner_id')){
    } else {
      return redirect(route('shift.mygroup.view', ['sgid' => $req->sgid], false))
      ->with([
        'alert' => 'Please select planner to proceed',
        'a_type' => 'warning'
      ]
      );
    }

    $tsg = ShiftGroup::find($req->sgid);

    if($tsg){

      if($tsg->manager_id != $req->user()->id){
        return redirect(route('shift.mygroup'))->with([
          'alert' => 'You are not the owner of that group',
          'a_type' => 'warning'
        ]);
      }

      $tsg->planner_id = $req->planner_id;
      $tsg->save();


    // user yang akan terima notification tu
    $to_user = User::where('id',$req->planner_id)->first();
    $cc_user = $tsg->Manager->email;

    // object yang nak dinotify / tengok bila penerima notify tekan link
    $shift_grp = \App\ShiftGroup::where('id', $req->sgid)->first();

  try{
    // hantar notification ke user tu, untuk action yang berkaitan
    $to_user->notify(new GroupPlannerAssigned($shift_grp,$cc_user));
  } catch(\Exception $e){
  }


      $plannername = '';
      if(isset($tsg->planner_id) && $tsg->planner_id != 0){
        $plannername = $tsg->Planner->name;
      }

      return redirect(route('shift.mygroup.view', ['sgid' => $tsg->id]))
        ->with([
          'alert' => 'Planner assigned',
          'a_type' => 'success'
        ]);

    } else {
      // group 404
      return redirect(route('shift.mygroup'))->with([
        'alert' => 'Shift group not found',
        'a_type' => 'warning'
      ]);
    }
  }

  public function mygroupdelplanner(Request $req){
    // dd($req->all());
    // malas way to skip if not enough param
    if($req->filled('sgid')){
    } else {
      return redirect(route('shift.mygroup'));
    }

    $tsg = ShiftGroup::find($req->sgid);

    if($tsg){

      if($tsg->manager_id != $req->user()->id){
        return redirect(route('shift.mygroup'))->with([
          'alert' => 'You are not the owner of that group',
          'a_type' => 'warning'
        ]);
      }

      $tsg->planner_id = null;
      $tsg->save();

      return redirect(route('shift.mygroup.view', ['sgid' => $tsg->id]))
        ->with([
          'alert' => 'Planner removed',
          'a_type' => 'info'
        ]);

    } else {
      // group 404
      return redirect(route('shift.mygroup'))->with([
        'alert' => 'Shift group not found',
        'a_type' => 'warning'
      ]);
    }
  }

  public function getListReptto(Request $req, $parentid)
    {  
        //direct rept crawl 8 tier
        $reptto_id = [];

        //1
        $data = User::select("persno as user_id","name")
        ->where('reptto','=',$parentid)
        ->get();

        $reptto_id = $data->pluck('user_id')->toarray();
        
        //2
        if($data->count() > 0){ 
        $data = User::select("persno as user_id","name")
        ->whereIn('reptto',$reptto_id)
        ->get();
        
        $reptto_array = $data->pluck('user_id')->toarray();
        foreach($reptto_array as $a)
        {
           array_push($reptto_id,$a);
        }

            //3
            if($data->count() > 0){ 
                
                $data = User::select("persno as user_id","name")
                ->whereIn('reptto',$reptto_id)
                ->get();
                
                $reptto_array = $data->pluck('user_id')->toarray();
                foreach($reptto_array as $a)
                {
                array_push($reptto_id,$a);
                }

                
                //4
                if($data->count() > 0){ 
                    $data = User::select("persno as user_id","name")
                    ->whereIn('reptto',$reptto_id)
                    ->get();
                
                    $reptto_array = $data->pluck('user_id')->toarray();
                    foreach($reptto_array as $a)
                    {
                    array_push($reptto_id,$a);
                    }

                    //5
                    if($data->count() > 0){ 
                        $data = User::select("persno as user_id","name")
                        ->whereIn('reptto',$reptto_id)
                        ->get();
                    
                        $reptto_array = $data->pluck('user_id')->toarray();
                        foreach($reptto_array as $a)
                        {
                        array_push($reptto_id,$a);
                        }                        

                        //6
                        if($data->count() > 0){ 
                            $data = User::select("persno as user_id","name")
                            ->whereIn('reptto',$reptto_id)
                            ->get();
                        
                            $reptto_array = $data->pluck('user_id')->toarray();
                            foreach($reptto_array as $a)
                            {
                            array_push($reptto_id,$a);
                            }

                            //7
                            if($data->count() > 0){ 
                                $data = User::select("persno as user_id","name")
                                ->whereIn('reptto',$reptto_id)
                                ->get();
                            
                                $reptto_array = $data->pluck('user_id')->toarray();
                                foreach($reptto_array as $a)
                                {
                                array_push($reptto_id,$a);
                                }

                                //8
                                if($data->count() > 0){ 
                                    $data = User::select("persno as user_id","name")
                                    ->whereIn('reptto',$reptto_id)
                                    ->get();
                                
                                    $reptto_array = $data->pluck('user_id')->toarray();
                                    foreach($reptto_array as $a)
                                    {
                                    array_push($reptto_id,$a);
                                    }
                                }  
                            }  
                        }  
                    }      
                }            
            }            

        }

        return $reptto_id;
    }

    public function showall(Request $req){

      $sglist = ViewShiftGroup::all();
   
      return view('admin.shiftGroup',compact('sglist'));

      // return view('shiftplan.shift_group', [
      //   'sglist' => $sglist        
      // ]);
    }

}
