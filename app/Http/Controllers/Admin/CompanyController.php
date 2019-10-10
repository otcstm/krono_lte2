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
    public function __construct()
    {
        //abort_if(Gate::denies('admin_roles'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function index(Request $req)
    {
        $companies = $this->list();

        $alert = Session('alert') ? Session('alert') : 'rest';
        $ac = Session('ac') ? Session('ac') : 'info';
        return view('admin.companies',['companies' => $companies,'alert'=>$alert, 'ac'=>$ac]);
    }

    public function update(Request $req)
    {
        $company_var = Company::findOrFail($req->id);
        $company_var->company_descr = $req->company_descr;
        $company_var->updated_by  = $req->user()->id;
        $company_var->source  = 'OT';
    //    $LogUserAct = doUserLogs($req,'Company', __FUNCTION__);
        $company_var->save();
        $execute = UserHelper::LogUserAct($req, "Company Management", "Update Company " .$req->company_descr);
        $ac = 'info';
        $alert = 'Successfully updated company ' .$req->company_descr;
        $companies = $this->list();

        return redirect(route('company.index', [], false))->
        with([
          'alert' => $alert,
          'ac'=>$ac
        ]);
    }

    public function store(Request $req)
    {
        $company_check = Company::find($req->company_code);
        $alert = "string";
        $ac = 'info';

        if(!$company_check){
        $company_var = new Company;
        $company_var->id = $req->company_code;
        $company_var->company_descr = $req->company_descr;
        $company_var->source  = 'OT';
        $company_var->updated_by  = $req->user()->id;
        $company_var->created_by  = $req->user()->id;
        $company_var->save();
        $execute = UserHelper::LogUserAct($req, "Company Management", "Create Company " .$req->company_descr);

        $alert = 'Successfully created company '.$req->company_descr;
        }
        else{
            $ac = 'danger';
            $alert = $req->company_code .' already existed and will not be added';
        }
        return redirect(route('company.index', [], false))->
        with([
          'alert' => $alert,
          'ac'=>$ac
        ]);


    }


    public function destroy(Request $req)
    {
      //$companies = $this->list();
        $cm = $req->company_id;
        $company_log = Company::find($req->company_id);
        $company_log ->deleted_by  = $req->user()->id;
        $company_log ->save();
        Company::destroy($cm);
        $execute = UserHelper::LogUserAct($req, "Company Management", "Delete Company " .$company_log->company_descr);
        $ac = 'info';
        $alert ='Successfully deleted company ' .$company_log->company_descr ;
        return redirect(route('company.index', [], false))->
        with([
          'alert' => $alert,
          'ac'=>$ac
        ]);
    }

    public static function list()
    {
        $companies = Company::all();
        return $companies;
    }

}
