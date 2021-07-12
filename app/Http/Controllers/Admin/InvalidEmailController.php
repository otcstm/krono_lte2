<?php

namespace App\Http\Controllers\Admin;
use App\InvalidEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvalidEmailController extends Controller
{
  public function index(Request $req){

    // $projectlist = Project::all();
    $email_list = [];

    if($req->filled('searching')){
      $email_list = $this->fetch($req);
      // dd($email_list);
    }
    return view('admin.invalidemail', ['email_lists' => $email_list]);

  }

  public function fetch(Request $req){

// dd($req);
    $fuserid = $req->inUserid;
    $fAppid = $req->inAppid;
    $fVerid = $req->inVerid;
    $fRefno = $req->inRefno;

    $email_list = InvalidEmail::query();
    if(isset($req->inUserid)){
      $email_list = $email_list->where('user_id',$fuserid);
    }
    if(isset($req->inAppid)){
      $email_list = $email_list->where('approver_id',$fAppid);
    }
    if(isset($req->inVerid)){
      $email_list = $email_list->where('verifier_id',$fVerid);
    }
    if(isset($req->inputpno)){
      // $projectlist = $projectlist->whereIn('project_no',$fpno);
      $email_list = $email_list->where('refno','LIKE','%' .$fRefno. '%');
    }
    $email_list = $email_list->get();
    return $email_list;
  }


}
