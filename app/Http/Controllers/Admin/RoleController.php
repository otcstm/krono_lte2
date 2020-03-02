<?php

namespace App\Http\Controllers\Admin;

use App\Shared\UserHelper;
use App\Role;
use App\Permission;
use App\UserLog;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class RoleController extends Controller{
    public function show(){
        $role = Role::all();   
        $permission = Permission::all();   
        // $role = Role::where('deleted_at', null)->orderBy('title', 'ASC')->get();   
        return view('admin.rolemgmt', ['roles' => $role, 'permissions' => $permission]);
    }

    public function store(Request $req){
        $name = $req->inputname;
        $permission = $req->permission;
        $check = Role::where('title', trim($name))->where('deleted_at', null)->get(); 
        $feedback = true;
        if(count($check)==0){
            $new_role = new Role;
            $new_role->title = $name;
            $new_role->created_by = $req->user()->id;
            $new_role->save();
            $new_role->permissions()->sync($permission);
            $execute = UserHelper::LogUserAct($req, "Role Management", "Create Role " .$name);
            $feedback_text = "Successfully created role " .$name. ".";
            $feedback_title = "Successfully Created";
        }else{
            $feedback_text = "There is already a role named " .$name. ".";
            $feedback_title = "Failed";
        }
        return redirect(route('role.list',[],false))->with([
            'feedback' => true,
            'feedback_text' => $feedback_text,
            'feedback_title' => $feedback_title]
        );
    }

    public function update(Request $req){
        $permission = $req->permission;
        $update_role = Role::find($req->inputid);
        $update_role->title = $req->inputname;
        $update_role->updated_by = $req->user()->id;
        $update_role->save();
        $update_role->permissions()->sync($permission);
        $execute = UserHelper::LogUserAct($req, "Role Management", "Update Role " .$req->inputname);
        $feedback = true;
        $feedback_text = "Successfully updated role " .$req->inputname. ".";
        $role = Role::all(); 
        return redirect(route('role.list',[],false))->with([
            'role' => $role,
            'feedback' => $feedback,
            'feedback_text' => $feedback_text,
            'feedback_title' => "Successfully Updated"]
        );
    }

    public function destroy(Request $req){
        $delete_role = Role::find($req->inputid);
        $delete_role->deleted_by = $req->user()->id;
        $delete_role->save();
        Role::find($req->inputid)->delete();
        $execute = UserHelper::LogUserAct($req, "Role Management", "Delete Role " .$delete_role->title);
        $feedback = true;
        $feedback_text = "Successfully deleted role " .$delete_role->title. ".";
        $role = Role::all(); 
        return redirect(route('role.list',[],false))->with([
            'role' => $role,
            'feedback' => $feedback,
            'feedback_text' => $feedback_text,
            'feedback_title' => "Successfully Deleted"]
        );
    }
}
