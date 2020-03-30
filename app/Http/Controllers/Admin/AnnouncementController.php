<?php

namespace App\Http\Controllers\Admin;

use App\Announcement;
use Illuminate\Http\Request;
use Session;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class AnnouncementController extends Controller
{
    public function show(){
        $an = Announcement::all();
        // $role = Role::where('deleted_at', null)->orderBy('title', 'ASC')->get();
        return view('admin.announcement', ['an' => $an]);
    }

    public function form(Request $req){
        
        if($req->session()->get('editan')==null){
            $an = Announcement::first();
            $sd = null;
            $ed = null;
            $edit = false;
            if($an!=null){
                $sd = date("Y-m-d", strtotime($an->start_date . '+1 day'));
                $ed = date("Y-m-d", strtotime($an->start_date . '+2 day'));
            }
        }else{
            $an = $req->session()->get('editan');
            $sd = $req->session()->get('editan')->start_date;
            $ed = $req->session()->get('editan')->end_date;
            $edit = true;
        }
        // $role = Role::where('deleted_at', null)->orderBy('title', 'ASC')->get();
        return view('admin.newannouncement', ['sd' => $sd, 'ed' => $ed, 'edit' => $edit, 'an' => $an]);
    }

    public function add(){
        Session::put(['editan' => []]);
        return redirect(route('announcement.form',[],false));
    }

    public function edit(Request $req){
        
        $an = Announcement::where('id', $req->inputid)->first();
        Session::put(['editan' => $an]);
        // dd(session()->all());
        // dd($req->session()->get('editan')->id);
        return redirect(route('announcement.form',[],false));
    }

    public function delete(Request $req){
        
        $an = Announcement::find( $req->inputid);
        $an->delete();
        return redirect(route('announcement.show',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Announcement has successfully deleted.",
            'feedback_title' => "Successfully Delete"
        ]);
    }
    
    public function create(Request $req){
        $new = new Announcement;
        $new->start_date = $req->sd;
        $new->end_date = $req->ed;
        $new->title = $req->title;
        $new->announcement = $req->announce;
        // $new->announcement = nl2br($req->announce);
        $new->created_by = $req->user()->staff_no;
        // dd($req->announce);
        $new->save();
        return redirect(route('announcement.show',[],false))->with([
            'feedback' => true,
            'feedback_text' => "New announcement has successfully created.",
            'feedback_title' => "Successfully Created"
        ]);
    }

    public function save(Request $req){
        $new = Announcement::where('id', $req->inputid)->first();
        $new->title = $req->title;
        $new->announcement = $req->announce;
        // $new->announcement = nl2br($req->announce);
        // $new->created_by = $req->user()->staff_no;
        // dd($req->announce);
        $new->save();
        return redirect(route('announcement.show',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Announcement has successfully updated.",
            'feedback_title' => "Successfully Updated"
        ]);
    }
}
