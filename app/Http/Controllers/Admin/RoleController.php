<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\Admin;

use App\Role;
// use App\User;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class RoleController extends Controller{

    public function __construct() {
        // dd(Gate::can('admin_m_roles'));
        // abort_if(Gate::denies('admin_roles'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function index(Request $req){
      dd($req->user()->can('admin_m_roles'));
      return "nom";
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
            $feedback_text = "Successfully created role " .$name. ".";
            $feedback_icon = "ok";
            $feedback_color = "#5CB85C";
        }else{
            $feedback_text = "There is already a role named " .$name. ".";
            $feedback_icon = "remove";;
            $feedback_color = "#D9534F";
        }
        
        return redirect(route('role.list',[],false))->with([
            'feedback' => $feedback,
            'feedback_text' => $feedback_text,
            'feedback_icon' => $feedback_icon,
            'feedback_color' => $feedback_color]
        );
    }

    public function show(Role $role){
        $role = Role::all();   
        // $role = Role::where('deleted_at', null)->orderBy('title', 'ASC')->get();   
        return view('admin.rolemgmt', ['roles' => $role]);
    }

    public function update(Request $req, Role $role){
        $update_role = Role::find($req->inputid);
        $update_role->title = $req->inputname;
        $update_role->updated_by = $req->user()->id;
        $update_role->save();
        $feedback = true;
        $feedback_text = "Successfully updated role " .$req->inputname. ".";
        $feedback_icon = "ok";
        $feedback_color = "#5CB85C";
        $role = Role::all(); 
        return redirect(route('role.list',[],false))->with([
            'role' => $role,
            'feedback' => $feedback,
            'feedback_text' => $feedback_text,
            'feedback_icon' => $feedback_icon,
            'feedback_color' => $feedback_color,e]
        );
    }

    public function destroy(Request $req){
        $delete_role = Role::find($req->inputid);
        $delete_role->deleted_by = $req->user()->id;
        $delete_role->save();
        Role::find($req->inputid)->delete();
        $feedback = true;
        $feedback_text = "Successfully deleted role " .$req->inputname. ".";
        $feedback_icon = "ok";
        $feedback_color = "#5CB85C";
        $role = Role::all(); 
        return redirect(route('role.list',[],false))->with([
            'role' => $role,
            'feedback' => $feedback,
            'feedback_text' => $feedback_text,
            'feedback_icon' => $feedback_icon,
            'feedback_color' => $feedback_color]
        );
    }
}
