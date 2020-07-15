<?php

namespace App\Http\Controllers\Admin;
use App\Shared\UserHelper;
use App\User;
use App\Role;
use App\Company;
use App\State;
use App\Psubarea;
use App\UserRecord;
use App\VerifierGroup;
use App\VerifierGroupMember;
use Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    public function showStaff(Request $req){
        if($req->session()->has('staffs')) {
          // $staff = $req->session()->get('staffs');
          $staff = User::find($req->session()->get('staffs'));
          $role = Role::all();
          return view('admin.staff',[
            // 'staffs' => $req->session()->get('staffs')
            'staffs' => $staff,
            'roles' => $role
          ]);
        }else{
          $staff = [];
          $role = [];
          return view('admin.staff',['staffs' => $staff, 'roles' => $role]);
        }
    }

    public function searchStaff(Request $req){
      $input = $req->inputstaff;
      $auth = $req->auth;
      $mgmt = $req->mgmt;
      $staffr = [];
      $staff = User::where('staff_no', trim($input))->get();
      if(!empty($input)){
        if(count($staff)==0){
          $staff = User::where('name', 'LIKE', '%' .$input. '%')->orderBy('name', 'ASC')->get();
        }
        if(count($staff)==0){
          $req->session()->flash('feedback',true);
          $req->session()->flash('feedback_text',"No maching records found. Try to search again.");
          $req->session()->flash('feedback_icon',"remove");
          $req->session()->flash('feedback_color',"#D9534F");
        } elseif (count($staff) > 500) {
          $req->session()->flash('feedback',true);
          $req->session()->flash('feedback_text',"Too many result. Please refine your search.");
          $req->session()->flash('feedback_icon',"remove");
          $req->session()->flash('feedback_color',"#D9534F");
        } else {
          $staffr = $staff->pluck('id');
        }
      }
      Session::put(['staffs'=>$staffr]);
      if(!empty($auth)){
        return redirect(route('staff.list.auth',[],false));
      }else if(!empty($mgmt)){
        return redirect(route('staff.list.mgmt',[],false));
      }else{
        return redirect(route('staff.list',[],false));
      }
    }

    
    public function emptystaffauth(){
        Session::put(['staffs'=>[]]);
        return redirect(route('staff.list.auth',[],false));
    }


    public function showRole(Request $req){
      $auth = true;
      $role = Role::all();
      if($req->session()->has('staffs')) {
        // $staff = $req->session()->get('staffs');
        $staff = User::find($req->session()->get('staffs'));
        return view('admin.staff',[
          // 'staffs' => $req->session()->get('staffs'),
          'staffs' => $staff,
          'auth' => $auth,
          'roles' => $role,
          'feedback' => $req->session()->get('feedback'),
          'feedback_text' => $req->session()->get('feedback_text'),
          'feedback_icon' => $req->session()->get('feedback_icon'),
          'feedback_color' =>  $req->session()->get('feedback_color')
        ]);
      }else{
        $staff = [];
        return view('admin.staff',['staffs' => $staff, 'roles' => $role, 'auth'=>$auth]);
      }
    }

    public function showMgmt(Request $req){
      $mgmt = true;
      $company = Company::all();
      $state = State::all();
      if($req->session()->has('staffs')) {
        // $staff = $req->session()->get('staffs');
        $staff = User::find($req->session()->get('staffs'));
        return view('admin.staff',[
          // 'staffs' => $req->session()->get('staffs'),
          'staffs' => $staff,
          'mgmt' => $mgmt,
          'companies' => $company,
          'states' => $state,
          'feedback' => $req->session()->get('feedback'),
          'feedback_text' => $req->session()->get('feedback_text'),
          'feedback_icon' => $req->session()->get('feedback_icon'),
          'feedback_color' =>  $req->session()->get('feedback_color')
        ]);
      }else{
        $staff = [];
        return view('admin.staff',['staffs' => $staff, 'companies' => $company, 'states' => $state, 'mgmt'=>$mgmt]);
      }
    }

    public function updateRole(Request $req){
      $role = $req->role;
      $update_staff = User::find($req->inputid);
      $update_staff->roles()->sync($role);
      $execute = UserHelper::LogUserAct($req, "User Management", "Update " .$req->inputname. " authorization");
      $feedback = true;
      $feedback_text = "Successfully updated " .$req->inputno. " roles for user ".$update_staff->staff_no.".";
      $feedback_title = "Successfully Updated";
      // $staff = User::all();
      return redirect(route('staff.list.auth',[],false))->with([
          // 'staffs'=>$staff,
          'feedback' => $feedback,
          'feedback_text' => $feedback_text,
          'feedback_title' => $feedback_title]
      );
  }
    public function updateMgmt(Request $req){
      $role = $req->role;
      $update_staff = User::find($req->inputid);
      $update_staff->company_id = $req->company;
      $update_staff->state_id = $req->state;
      $update_staff->save();
      $execute = UserHelper::LogUserAct($req, "User Management", "Update user " .$req->inputname);
      $feedback = true;
      $feedback_text = "Successfully updated " .$req->inputno. ".";
      $feedback_icon = "ok";
      $feedback_color = "#5CB85C";
      // $staff = User::all();
      return redirect(route('staff.list.mgmt',[],false))->with([
          // 'staffs'=>$staff,
          'feedback' => $feedback,
          'feedback_text' => $feedback_text,
          'feedback_icon' => $feedback_icon,
          'feedback_color' => $feedback_color]
      );
  }

  public function showStaffProfile(Request $req){
    
    // $user_logs->user_id = $req->user()->id;
    // $user_logs->session_id = $req->session()->getId();
    // $user_logs->ip_address = $req->ip();
    // $user_logs->user_agent = $req->userAgent();
    if(isset($req->getProfile)){
      $staff = User::find($req->getProfile);
    }
    else {
      $staff = User::find($req->user()->id);
    }
    
      $staff_detail = UserRecord::where('user_id', '=', $staff->id)
      ->orderBy('upd_sap', 'desc')
      ->first();

      $directreport = User::find($staff->reptto);
      $directreport_detail = UserRecord::where('user_id', '=', $staff->reptto)
      ->orderBy('upd_sap', 'desc')
      ->first();

      $verifierGroupMember = VerifierGroupMember::where('user_id', '=', $staff->id)
      ->where('start_date', '>=' ,NOW())  
      ->where('end_date', '<' ,NOW())     
      ->get();
      //dd($verifierGroupMember);

      if($verifierGroupMember->count() > 0)
      {
      $verifierGroup = VerifierGroup::find($verifierGroupMember->user_verifier_groups_id);
      $verifier_detail = UserRecord::where('user_id', '=', $verifierGroup->verifier_id)
      ->orderBy('updated_at', 'desc')
      ->first();
      }
      else
      {
        $verifierGroup = [];
        $verifier_detail = [];
      };    

      $listsubord = User::where('reptto','=',$staff->id)
      ->orderBy('name', 'asc')
      ->get();
      
      //$staff_comp = Company::find($staff->company_id);
      $staff_psubarea = Psubarea::where('persarea', '=', $staff->persarea)
      ->where( 'perssubarea', '=', $staff->perssubarea)
      ->first();
      
      //dd($staff_psubarea);
      return view('staff.profile',
      [
      'staff_basic' => $staff, 
      'staff_detail' => $staff_detail, 
      'direct_report' => $directreport,
      'direct_report_detail' => $directreport_detail,
      'verifier_group' => $verifierGroup,
      'verifier_detail' => $verifier_detail,
      'staff_psubarea' => $staff_psubarea,
      'list_subord' => $listsubord
      ]
      );
}
  
}
