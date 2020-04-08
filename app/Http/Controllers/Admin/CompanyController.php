<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Shared\UserHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
  public function index()
  {
      $company = Company::all();

      return view('admin.company', ['companies' => $company]);
  }

  public function store(Request $req)
  {
    $check = Company::find($req->inputcomp);

    if(!$check){
      $company_var = new Company;
      $company_var->id = $req->inputcomp;
      $company_var->company_descr = $req->inputdescr;
      $company_var->source  = 'OT';
      $company_var->created_by  = $req->user()->id;
      $company_var->save();
      $execute = UserHelper::LogUserAct($req, "Company Management", "Create Company " .$req->inputcomp);
      $feedback_text = "Successfully created Company ".$req->inputdescr.".";
      $feedback_title = "Successfully Created";
  }
  else{$req->inputcomp .' already exist.';
      $a_type = "warning";
      $feedback_text = "Company code ".$req->inputcomp." already existed!";
      $feedback_title = "Failed";
      }
      return redirect(route('company.index', [], false))
      ->with([
        'feedback' => true,
        'feedback_text' => $feedback_text,
        'feedback_title' => $feedback_title
      ]);
    }

  public function update(Request $req)
    {
    //  dd($req->eid);
      $company_var = Company::find($req->eid);
      $old = $company_var->company_descr;
            $company_var->company_descr = $req->editdescr;
            $company_var->updated_by  = $req->user()->id;
            $company_var->source  = 'OT';
            $company_var->save();
            $execute = UserHelper::LogUserAct($req, "Company Management", "Update Company " .$req->eid);
            return redirect(route('company.index', [], false))->with([
              'feedback' => true,
              'feedback_text' => "Company " .$old. " has successfully been updated to ".$company_var->company_descr.".",
              'feedback_title' => "Successfully Updated"
            ]);

    }

    public function destroy(Request $req)
    {

      $cm = Company::find($req->inputid);
      $execute = UserHelper::LogUserAct($req, "Company Management", "Delete Company " .$req->inputid);
      $cm->save();
      $cm->delete();

      return redirect(route('company.index', [], false))->with([
        'feedback' => true,
        'feedback_text' => "Company ".$cm->company_descr." has successfully been deleted.",
        'feedback_title' => "Successfully Deleted"
    ]);
  }
  }
