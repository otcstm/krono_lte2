<?php

namespace App\Http\Controllers\Admin;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
  public function index(Request $req){

    // $projectlist = Project::all();
    $projectlist = [];

    if($req->filled('searching')){
      $projectlist = $this->fetch($req);

    }
    return view('admin.project', ['projectlists' => $projectlist]);

  }

  public function fetch(Request $req){
    // $fpno = explode(",", str_replace(' ','',$req->inputpno));
    $fpno = $req->inputpno;
    $fstatus = explode(",", str_replace(' ','',$req->inputStatus));
    $ftype = explode(",", str_replace(' ','',$req->inputType));
    $fcc = explode(",", str_replace(' ','',$req->inputcc));
    $fcocd = explode(",", str_replace(' ','',$req->inputcocd));
    $fNetheader = $req->inputNetheader;
    // $fNetheader = explode(",", str_replace(' ','',$req->inputNetheader));
    $fActno = $req->inputActno;
    // $fActno = explode(",", str_replace(' ','',$req->inputActno));
    $fapprover = explode(",", str_replace(' ','',$req->inputApprver));


    $projectlist = Project::query();
    if(isset($req->inputpno)){
      // $projectlist = $projectlist->whereIn('project_no',$fpno);
      $projectlist = $projectlist->where('project_no','LIKE','%' .$fpno. '%');
    }
    if(isset($req->inputStatus)){
      $projectlist = $projectlist->whereIn('status',$fstatus );
    }
    if(isset($req->inputType)){
      $projectlist = $projectlist->whereIn('type',$ftype);
    }
    if(isset($req->inputcc)){
      $projectlist = $projectlist->whereIn('cost_center',$fcc);
    }
    if(isset($req->inputcocd)){
      $projectlist = $projectlist->whereIn('company_code',$fcocd);
    }
    if(isset($req->inputNetheader)){
      $projectlist = $projectlist->where('network_header','LIKE','%' .$fNetheader. '%');
    }
    if(isset($req->inputActno)){
      $projectlist = $projectlist->where('network_act_no','LIKE','%' .$fActno. '%');
    }
    if(isset($req->inputApprver)){
      $projectlist = $projectlist->whereIn('approver_id',$fapprover);
    }
    $projectlist = $projectlist->get();
    return $projectlist;
  }


}
