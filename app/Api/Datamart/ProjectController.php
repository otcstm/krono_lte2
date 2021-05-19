<?php

namespace app\Api\Datamart;
//use App\Api\Datamart;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Project;

use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function insert(Request $req)
    {

      $exPrList = Project::where('project_no', $req->project_no)
      ->where('network_header',$req->network_header)
      ->where('network_act_no',$req->network_act_no)
      ->delete();

      $pr = new Project;
      $pr->project_no         = $req->project_no;
      $pr->descr              = $req->project_desc;
      $pr->status             = $req->project_status;
      $pr->type               = $req->project_type;
      $pr->cost_center        = $req->project_costcenter;
      $pr->company_code       = $req->project_comp_code;
      $pr->network_header     = $req->network_header;
      $pr->network_headerdescr= $req->network_header_desc;
      $pr->network_act_no     = $req->network_act_no;
      $pr->network_act_descr  = $req->network_act_desc;
      $pr->approver_id        = $req->otcs_approver;
      $pr->budget             = $req->proj_budget;
      $pr->upd_dm             = $req->last_upd_dt;


      $pr->save();


      return $pr->id;

    }

    public function returnMaxDate()
    {
        $upd_dm = Project::max('upd_dm');

        return $upd_dm;

    }




}
