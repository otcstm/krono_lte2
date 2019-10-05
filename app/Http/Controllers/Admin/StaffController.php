<?php

namespace App\Http\Controllers\Admin;
use App\Shared\UserHelper;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    public function listStaff(){
        $staff = User::all();
        return view('staff.liststaff', ['staffs' => $allusers]);
    }

    public function show(Request $req){
      $adminauth = true;
      $role = Role::all();
      if($req->session()->has('staffs')) {
        $staff = $req->session()->get('staffs');
        return view('admin.staff',[
          'staffs' => $req->session()->get('staffs'),
          'auth' => $adminauth,
          'roles' => $role,
          'feedback' => $req->session()->get('feedback'),
          'feedback_text' => $req->session()->get('feedback_text'),
          'feedback_icon' => $req->session()->get('feedback_icon'),
          'feedback_color' =>  $req->session()->get('feedback_color')
        ]);
      }else{
        $staff = User::all();
        return view('admin.staff',['staffs' => $staff, 'roles' => $role, 'admin'=>$adminview]);
      }
    }
    
    public function search(Request $req){
      $input = $req->inputstaff;
      $staff = [];
      $feedback = null;
      $feedback_text = null;
      $feedback_icon = null;
      $feedback_color = null;
      $staff = User::where('staff_no', trim($input))->get();
      if(!empty($input)){
        if(count($staff)==0){
          $staff = User::where('name', 'LIKE', '%' .$input. '%')->orderBy('name', 'ASC')->get();
        }
        if(count($staff)==0){
          $feedback = true;
          $feedback_text = "No maching records found. Try to search again.";
          $feedback_icon = "remove";
          $feedback_color = "#D9534F";
        }
      }else{
        $staff = User::all();
      }
      return redirect(route('staff.list.admin',[],false))->with([
        'staffs'=>$staff, 
        'feedback' => $feedback,
        'feedback_text' => $feedback_text,
        'feedback_icon' => $feedback_icon,
        'feedback_color' => $feedback_color]
      );
    }

    public function updateRole(Request $req){
      $role = $req->role;
      $update_staff = User::find($req->inputid);
      $update_staff->roles()->sync($role);
      $execute = UserHelper::LogUserAct($req, "User Management", "Update Role " .$req->inputname);
      $feedback = true;
      $feedback_text = "Successfully updated " .$req->inputno. " roles.";
      $feedback_icon = "ok";
      $feedback_color = "#5CB85C";
      $staff = User::all(); 
      return redirect(route('staff.list.admin',[],false))->with([
          'staffs'=>$staff,
          'feedback' => $feedback,
          'feedback_text' => $feedback_text,
          'feedback_icon' => $feedback_icon,
          'feedback_color' => $feedback_color]
      );
  }
}
