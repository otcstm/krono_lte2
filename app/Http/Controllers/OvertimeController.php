<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class OvertimeController extends Controller
{
    public function show(Request $req){
        $hours = 104;

        $overtime = Role::all();
        $feedback = true;
        $role = Role::all();   
        $permission = Permission::all();   
        // $role = Role::where('deleted_at', null)->orderBy('title', 'ASC')->get();   
        if(count()){

        }
        return view('staff.overtime', ['roles' => $role, 'permissions' => $permission]);
    }
}
