<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserVerifierGroup;
use App\VerifierGroup;
use App\VerifierGroupMember;

class VerifierGroupController extends Controller
{
    public function index(Request $req)
    {	                
        $dataUser = User::where('id', '=', $req->user()->id)
        ->latest('updated_at')
        ->first();
        //$repttoUser = User::where('reptto', '=', $req->user()->id)->latest('updated_at')->first();
        $verifierGroups = VerifierGroup::where('approver_id', '=', $req->user()->id)
        ->get();
  
        return view('verifier.index')
        ->with([
            'userdata' => $dataUser, 
            'verifierGroups' => $verifierGroups
            ]);
    }

    public function search(Request $req)
    {	        
        //$data = User::where('reptto', '=', $req->user()->id)->get();
        //return view('verifier.index')->with('subordinate', $data);

        return view('verifier.search');
    }

    public function staffsearch(Request $req)
    {
        //    	        
        $data = [];
        if($req->has('q')){
            $search = $req->q;
            $data = User::select("id","name")
            		->where('name','LIKE',"%$search%")
                    ->get();
        }         
        return response()->json($data);
    }

    public function createGroup(Request $req)
    {	     
        //dd($req->all());           
        $userId = $req->user()->id;
        $verifierId = $req->verifierId;
        $verifierData = User::where('id', '=', $verifierId)->latest('updated_at')->first();

        //create new group
        $newgroup = new VerifierGroup;
        $newgroup->approver_id = $userId;
        $newgroup->verifier_id = $verifierId;
        $newgroup->group_name = $req->groupname;
        $newgroup->group_code = $req->groupcode;
        $newgroup->createdby_id = $userId;        
        $newgroup->save();

        //new id to redirect to view group
        $newgroupid = $newgroup->id;
    
        return redirect(route('verifier.viewGroup', ['gid' => $newgroupid], false))
        ->with([
            'sysmsg_type' => 'alert', 
            'sysmsg_class' => 'success',
            'sysmsg_icon' => 'glyphicon glyphicon-ok-circle', 
            'sysmsg_text' => 'Group created successfully'
            ]);
    }

    public function viewGroup(Request $req)
    {
        
        $groupData = VerifierGroup::find($req->gid);

        if(!$groupData){            
          return redirect(route('verifier.listGroup', [], false))
          ->with([
              'sysmsg_type' => 'alert', 
              'sysmsg_class' => 'warning',
              'sysmsg_icon' => 'glyphicon glyphicon-remove-circle', 
              'sysmsg_text' => 'Group not found or deleted!'
              ]);
        }

        $verifierData = User::where('id', '=', $groupData->verifier_id)->latest('updated_at')->first();

        $groupMember = VerifierGroupMember::where('user_verifier_groups_id', '=', $groupData->id)
        //->where('start_date', '>=' ,NOW())  
        //->where('end_date', '<' ,NOW())     
        ->get();

        $groupMemberList = VerifierGroupMember::where('user_verifier_groups_id', '=', $groupData->id)
        //->where('start_date', '>=' ,NOW())  
        //->where('end_date', '<' ,NOW())      
        ->distinct('user_id')
        ->pluck('user_id');     

        //if no member
        if( $groupMember->count() == 0)
        {
            $groupMemberList = [0]; 
        } 

        $freeMemberList = VerifierGroupMember::distinct('user_id')
        //->where('start_date', '>=' ,NOW())  
        //->where('end_date', '<' ,NOW())      
        ->pluck('user_id');  

        //if no free member
        if( $freeMemberList->count() == 0)
        {
            $freeMemberList = [0]; 
        }         

        $groupMember = User::whereIn('id', $groupMemberList)
        ->orderBy('name', 'asc')
        ->get();

        $reptto_list_id = $this->getListReptto($req);    

        $freeMember = User::whereIn('persno', $reptto_list_id)
        ->whereNotIn('persno', $groupMemberList)
        ->orderBy('name', 'asc')
        ->get();

        return view('verifier.viewGroup')
        ->with([
            'groupData' => $groupData,
            'groupMember' => $groupMember,
            'verifierData' => $verifierData,
            'freeMember' => $freeMember,
            'sysmsg_type' => 'alert', 
            'sysmsg_class' => 'success',
            'sysmsg_icon' => 'glyphicon glyphicon-ok-circle', 
            'sysmsg_text' => 'Group create successfully!'
            ]);

    }

    public function updateGroup(Request $req){

        $currgroup = VerifierGroup::find($req->gid);

        $checkApproverAuth = VerifierGroup::find($req->gid);
    
        if($checkApproverAuth->approver_id != $req->user()->id){
          return redirect()->back()->withInput()
          ->with([
              'sysmsg_type' => 'alert', 
              'sysmsg_class' => 'danger',
              'sysmsg_icon' => 'glyphicon glyphicon-ok-circle', 
              'sysmsg_text' => 'Only owner is allowed to edit the group!'
              ]);
        }
    
        if($currgroup){
          $currgroup->group_name = $req->groupname;
          $currgroup->verifier_id = $req->verifierId;
          $currgroup->save();
          return redirect(route('verifier.viewGroup', ['gid' => $req->gid], false))
          ->with([
            'sysmsg_type' => 'alert', 
            'sysmsg_class' => 'success',
            'sysmsg_icon' => 'glyphicon glyphicon-ok-circle', 
            'sysmsg_text' => 'Group update successfully!'
            ]);

        } else {
          return redirect(route('verifier.listGroup', [], false))
          ->with([
              'sysmsg_type' => 'alert', 
              'sysmsg_class' => 'warning',
              'sysmsg_icon' => 'glyphicon glyphicon-remove-circle', 
              'sysmsg_text' => 'Group not found or deleted!'
              ]);
        }
    }

    public function addUser(Request $req){
        // double check if this staff in any group
        $ingrp = VerifierGroupMember::where('user_id', $req->user_id)->first();
        if($ingrp){
          return redirect()->back()
          ->with([
              'sysmsg_type' => 'alert', 
              'sysmsg_class' => 'warning',
              'sysmsg_icon' => 'glyphicon glyphicon-remove-circle', 
              'sysmsg_text' => 'User already in group!'
              ]);
        }
        
        // add user to group
        $addusrgrpmbr = new VerifierGroupMember;
        $addusrgrpmbr->user_id = $req->user_id;
        $addusrgrpmbr->user_verifier_groups_id = $req->group_id;
        $addusrgrpmbr->createdby_id = $req->user()->id;        
        $addusrgrpmbr->save();
    
        return redirect(route('verifier.viewGroup', ['gid' => $req->group_id], false))
        ->with([
            'sysmsg_type' => 'alert', 
            'sysmsg_class' => 'success',
            'sysmsg_icon' => 'glyphicon glyphicon-ok-circle', 
            'sysmsg_text' => 'User add successfully!'
        ]);
    
      }
    
      public function removeUser(Request $req){
        $ingrp = VerifierGroupMember::where('user_id', $req->user_id)
                ->where('user_verifier_groups_id', $req->group_id)
                ->first();
    
        if($ingrp){
          $ingrp->delete();
    
          return redirect(route('verifier.viewGroup', ['gid' => $req->group_id], false))
          ->with([
              'sysmsg_type' => 'alert', 
              'sysmsg_class' => 'success',
              'sysmsg_icon' => 'glyphicon glyphicon-ok-circle', 
              'sysmsg_text' => 'User removed from group successfully!'
              ]);
        } else {
          return redirect(route('verifier.viewGroup', ['gid' => $req->group_id], false))
          ->with([
              'sysmsg_type' => 'warning', 
              'sysmsg_class' => 'success',
              'sysmsg_icon' => 'glyphicon glyphicon-ok-circle', 
              'sysmsg_text' => 'User not in this group!'
              ]);
        }
      }

      public function delGroup(Request $req){

        // return redirect()->back()->withInput()
        //   ->with([              
        //       'sysmsg_type' => 'alert', 
        //       'sysmsg_class' => 'danger',
        //       'sysmsg_icon' => 'glyphicon glyphicon-remove-circle', 
        //       'sysmsg_text' => 'Only owner is allowed to edit the group!'
        //   ]);

        $cgroup = VerifierGroup::find($req->id);
        $gname = $cgroup->group_code;
    
        if($cgroup->approver_id != $req->user()->id){
          return redirect()->back()->withInput()
          ->with([              
              'sysmsg_type' => 'alert', 
              'sysmsg_class' => 'danger',
              'sysmsg_icon' => 'glyphicon glyphicon-remove-circle', 
              'sysmsg_text' => 'Only owner is allowed to edit the group!'
          ]);
        }
    
        if($cgroup){
          // remove all member of this group first
          VerifierGroupMember::where('user_verifier_groups_id', $cgroup->id)->delete();
    
          // then only delete this group
          $cgroup->delete();
    
          return redirect(route('verifier.listGroup', [], false))
          ->with([
              'sysmsg_type' => 'alert', 
              'sysmsg_class' => 'warning',
              'sysmsg_icon' => 'glyphicon glyphicon-ok-circle', 
              'sysmsg_text' => 'Group ' . $gname . ' delete successfully'
          ]);
        } else {
          return redirect(route('verifier.listGroup', [], false))
          ->with([
              'sysmsg_type' => 'alert', 
              'sysmsg_class' => 'warning',
              'sysmsg_icon' => 'glyphicon glyphicon-ok-circle', 
              'sysmsg_text' => 'Group not found or already deleted!'
              ]);
        }
      }


    public function getListReptto(Request $req)
    {  
        //direct rept crawl 8 tier
        $reptto_id = [];

        //1
        $data = User::select("persno as user_id","name")
        ->where('reptto','=',$req->user()->id)
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
}

