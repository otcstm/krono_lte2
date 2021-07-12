<?php
namespace App\Http\Controllers\Admin;
use App\Shared\UserHelper;
use App\UserLog;
use App\Payrollgroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\CompanyPayrollgroup;
use \Carbon\Carbon;

class PayrollgroupController extends Controller
{
  public function index()
  {
      $company = Company::all();
      $pygroup = Payrollgroup::all();
      $compygroup = CompanyPayrollgroup::all();

      return view('admin.payrollgroup', ['companies' => $company,'pygroups' => $pygroup, 'compygroups'=>$compygroup ]);
  }

  public function create(Request $req)
  {
      $company = Company::all();
      // $defdate = new Date('NOW');
      $defdate = Carbon::now()->format('Y-m-d');
      return view('admin.createpayrollgroup', ['companies' => $company,'dtVal'=>$defdate]);
  }

  public function store(Request $req)
  {
      $user   = $req->user();
      $enddt = '9999'.'-12'.'-31'; //new record
      $edt = Carbon::now()->format('Y-m-d'); //for existing
      $cekpyg = $req->descr;
      $cek = Payrollgroup::where('pygroup',trim($cekpyg))->where('deleted_at',null)->get();

      if(count($cek)==0){
        $pyg = new Payrollgroup;
        $pyg->pygroup = $req->descr;
        $pyg->created_by = $user->id;
        $pyg->save();
        $companies = Company::find($req->comp_selections);
        if($companies){
          foreach ($companies as $comp) {
              $cpyg = new CompanyPayrollgroup;
              $cpyg->payrollgroup_id = $pyg->id;
              $cpyg->company_id   = $comp->id;
              $cpyg->updated_by  = $user->id;
              $cpyg->start_date = $req->dt;
              $cpyg->end_date = $enddt;
              $cpyg->save();
              $a_text = "Successfully created payroll group " .$cekpyg.".";
              $a_type = "Success";
              // dd(trim($comp->id),$pyg->id);
              $oldrecords = CompanyPayrollgroup::where('company_id',$comp->id)
              ->where('payrollgroup_id','!=',$pyg->id)->where('end_date','9999-12-31')->get();
              // dd('die'); dd([$oldrecords]);
              if($oldrecords){
                foreach($oldrecords as $oldrecord){
                  $oldrecord->end_date  = $edt;
                  $oldrecord->save();
                }
              }
          }
        }
      }else{
          $a_text = "There is already a group named ".$cekpyg. ".";
          $a_type = "Failed to Create";
      }
      return redirect(route('pygroup.index',[],false))->with(['feedback' => true, 'feedback_text'=>$a_text,'feedback_title'=>$a_type]);
  }

  public function edit($id)
  {
      $pygroup = Payrollgroup::find($id);
      $company = Company::all();
      $effdt = CompanyPayrollgroup::where('payrollgroup_id',$id)->first('start_date');
      $active = CompanyPayrollgroup::where('payrollgroup_id',$id)->where('end_date','9999-12-31')->get();
      $effdt = $effdt->start_date;
      $effdt= $effdt->format('Y-m-d');
      $mindate = Carbon::now()->format('Y-m-d');

      return view('admin.editpayrollgroup', [
         'pygroup'=>$pygroup,'companies'=> $company,'actives'=> $active,'dtVal'=>$effdt,'mindate'=>$mindate]);
  }

  public function update(Request $req)
  {
      $pygroup = Payrollgroup::find($req->id);
      $user   = $req->user();
      //get comp id from db
      $currentCompanies = CompanyPayrollgroup::where('payrollgroup_id',$pygroup->id)
      ->wheredate('end_date','9999-12-31')->get('company_id');
      //get comp id from req
      $arr = $req->company_selections;
      $sdt =$req->dt;
      //set end date
      $edt = Carbon::now()->format('Y-m-d');
      // $edt =$sdt->subDay();
      echo json_encode($arr);
      echo('<br/>');
      //loop through DB data
      foreach ($currentCompanies as $currcomp) {
          echo('echoing currcomp');
          echo($currcomp->company_id);
          echo('end echoing currcomp <br/>');

          if (($key = array_search($currcomp->company_id, $arr)) !== false) {
              //if the comp already existed in selection array
              //and existed in db remove it from the selection array
              //only new addition would left
              unset($arr[$key]);
              $a_text = "Payroll Group $pygroup->pygroup updated!";
              $a_type = "Success";

              // dd('if');
          } else {
              //if the comp not existed in the selection
              //but exist in db remove the comp from DB
              $cpgDel = CompanyPayrollgroup::where('payrollgroup_id', $pygroup->id)
             ->where('company_id', $currcomp->company_id)
             ->wheredate('end_date', '9999-12-31')
             ->delete();
             $a_text = "Payroll Group $pygroup->pygroup updated!";
             $a_type = "Success";
             // dd('else');

          }
        }
      foreach ($arr as $newselectedCompany) {
        $ncp = new CompanyPayrollgroup;
        $ncp->payrollgroup_id = $pygroup->id;
        $ncp->company_id   = $newselectedCompany;
        $ncp->updated_by  = $user->id;
        $ncp->start_date = $sdt;
        $ncp->end_date = '9999-12-31';
        $ncp->save();
        $a_text = "Payroll Group $pygroup->pygroup updated!";
        $a_type = "Success";
        $oldrecords = CompanyPayrollgroup::where('company_id',$newselectedCompany)
        ->where('payrollgroup_id','!=',$pygroup->id)->wheredate('end_date','9999-12-31')->get();
        // dd([$oldrecords]);
        if($oldrecords){
          foreach($oldrecords as $oldrecord){
            $oldrecord->end_date  = $edt;
            $oldrecord->save();
          }
        }
      }
      //**check selectedcompany yang enddate 9999-12-31 dalam db, if exist update enddate = $sdt-1
      return redirect(route('pygroup.index',[],false))->with(['feedback' => true,'feedback_text'=>$a_text, 'feedback_title'=>$a_type]);
  }

  public function destroy(Request $req)
  {
    $pyg = Payrollgroup::find($req->inputid);
    if($pyg){
      $pyg->delete();
      $a_text = "Payroll Group $pyg->pygroup deleted!";
      $a_type = "Success";
    } else {
      $a_text = "Payroll Group $pyg->pygroup not found";
      $a_type = "Error";
    }
    return redirect(route('pygroup.index', [], false))->with(['feedback' => true, 'feedback_text'=>$a_text, 'feedback_title'=>$a_type]);
  }
}
