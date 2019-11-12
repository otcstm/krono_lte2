<?php

namespace App\Http\Controllers\Admin;

use App\PaymentSchedule;
use App\Shared\UserHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;


class PaymentScheduleController extends Controller
{
  public function index()
  {
      $ps = PaymentSchedule::all();

      return view('admin.paymentschedule', ['companies' => $company]);
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
      $a_text = 'Successfully created company '.$req->inputcomp;
      $a_type = "success";
  }
  else{
      $a_text = 'Company code '.$req->inputcomp .' already exist.';
      $a_type = "warning";
      }
      return redirect(route('company.index', [], false))
      ->with(['a_text' => $a_text,'a_type' => $a_type]);
    }

  public function update(Request $req)
    {
    //  dd($req->all());
      $company_var = Company::find($req->eid);
      if($company_var){
            $company_var->company_descr = $req->editdescr;
            $company_var->updated_by  = $req->user()->id;
            $company_var->source  = 'OT';
            $company_var->save();
            $execute = UserHelper::LogUserAct($req, "Company Management", "Update Company " .$req->eid);
            return redirect(route('company.index', [], false))->with(['a_text' => 'Successfully updated company '. $req->eid , 'a_type' => 'success']);

      } else {
        return redirect(route('company.index', [], false))
        ->with(['a_text' =>'Company' .$req->eid. ' not found.', 'a_type' => 'warning']);
      }
    }

    public function destroy(Request $req)
    {

      $cm = Company::find($req->inputid);
      if($cm){
        $execute = UserHelper::LogUserAct($req, "Company Management", "Delete Company " .$req->inputid);
        $cm->save();
        $cm->delete();

        return redirect(route('company.index', [], false))->with(['a_text' => 'Company '.$req ->inputid. ' deleted', 'a_type' => 'warning']);
      } else {
        return redirect(route('company.index', [], false))->with(['a_text' => 'Company '.$req ->inputid. ' not found', 'a_type' => 'danger']);
      }
    }
  }
