<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    public function __construct() {
        // dd(Gate::can('admin_m_roles'));
        // abort_if(Gate::denies('admin_roles'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function listStaff(){
        $staff = User::all();
        return view('staff.liststaff', ['staffs' => $allusers]);
    }

    public function show(){
        $staff = User::all();
        $adminview = true;
        return view('admin.staff',['staffs' => $staff])->with([
            'admin'=>$adminview]
        );
    }
    
    public function search(Request $req){
        $input = $req->inputstaff;
        $message = null;
        $search = 1;
        $staff = [];
        if(!empty($input)){
          $staff = User::where('staff_no', trim($input))->get();
          if(count($staff)==0){
            $staff = User::where('name', 'LIKE', '%' .$input. '%')->orderBy('name', 'ASC')->get();
          }
          if(count($staff)==0){
            $message = 'No maching records found. Try to search again.';
          }
        }else{
          $message = 'Please enter staff no or staff name to search.';
        }
        return view('staff.searchStaff', ['staffs' => $staff,'search' => $search, 'message' => $message]);
    }
}
