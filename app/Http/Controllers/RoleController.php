<?php

namespace App\Http\Controllers;

use App\Role;
// use App\User;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{

  public function __construct() {

    // dd(Gate::can('admin_m_roles'));


    // abort_if(Gate::denies('admin_roles'), Response::HTTP_FORBIDDEN, '403 Forbidden');
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
      dd($req->user()->can('admin_m_roles'));
      return "nom";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // $check = Role::find($req->inputname);
        $name = $req->inputname;
        $check = Role::where('title', trim($name))->where('deleted_at', null)->get(); 
        $feedback = true;
        if(count($check)==0){
            $new_role = new Role;
            $new_role->title = $name;
            $new_role->created_by = $req->user()->staff_no;
            $new_role->save();
            $feedback_text = "Successfully created role " .$name. ".";
            $feedback_icon = "ok";
            $feedback_title = "Success";
            $feedback_color = "#5CB85C";
        }else{
            $feedback_text = "There is already a role named " .$name. ".";
            $feedback_icon = "remove";
            $feedback_title = "Failed";
            $feedback_color = "#D9534F";
        }
        
        return redirect(route('role.list',[],false))->with([
            'feedback' => $feedback,
            'feedback_text' => $feedback_text,
            'feedback_icon' => $feedback_icon,
            'feedback_color' => $feedback_color,
            'feedback_title' => $feedback_title]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $role = Role::all();   
        // $role = Role::where('deleted_at', null)->orderBy('title', 'ASC')->get();   
        return view('admin.rolemgmt', ['roles' => $role]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, Role $role)
    {
        $update_role->title = $req->inputname;
        $update_role->updated_by = $req->user()->staff_no;
        $update_role->save();
        $feedback = true;
        $feedback_text = "Successfully updated role " .$req->inputname. ".";
        $feedback_icon = "ok";
        $feedback_title = "Success";
        $feedback_color = "#5CB85C";
        $role = Role::all(); 
        return redirect(route('role.list',[],false))->with([
            'role' => $role,
            'feedback' => $feedback,
            'feedback_text' => $feedback_text,
            'feedback_icon' => $feedback_icon,
            'feedback_color' => $feedback_color,
            'feedback_title' => $feedback_title]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        $delete_role = $req->inputid;
        Role::find($delete_role)->delete();
        $feedback = true;
        $feedback_text = "Successfully deleted role " .$req->inputname. ".";
        $feedback_icon = "ok";
        $feedback_title = "Success";
        $feedback_color = "#5CB85C";
        $role = Role::all(); 
        return redirect(route('role.list',[],false))->with([
            'role' => $role,
            'feedback' => $feedback,
            'feedback_text' => $feedback_text,
            'feedback_icon' => $feedback_icon,
            'feedback_color' => $feedback_color,
            'feedback_title' => $feedback_title]
        );
    }
}
