<?php

namespace App\Http\Controllers\Admin;

use App\Shared\UserHelper;
use App\User;
use App\Role;
use App\Company;
use App\State;
use App\Psubarea;
use App\UserRecord;
use App\VerifierGroup;
use App\VerifierGroupMember;
use App\Salary;
use App\OtIndicator;
use App\WsrChangeReq;
use App\UserShiftPattern;
use App\ShiftPattern;
use Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    public function showStaff(Request $req)
    {
        if ($req->session()->has('staffs')) {
            // $staff = $req->session()->get('staffs');
            $staff = User::find($req->session()->get('staffs'));
            $role = Role::all();
            return view('admin.staff', [
            // 'staffs' => $req->session()->get('staffs')
            'staffs' => $staff,
            'roles' => $role
          ]);
        } else {
            $staff = [];
            $role = [];
            return view('admin.staff', ['staffs' => $staff, 'roles' => $role]);
        }
    }

    public function searchStaff(Request $req)
    {
        $input = $req->inputstaff;
        $auth = $req->auth;
        $mgmt = $req->mgmt;
        $staffr = [];
        $staff = User::where('staff_no', trim($input))->get();
        if (!empty($input)) {
            if (count($staff)==0) {
                $staff = User::where('name', 'LIKE', '%' .$input. '%')->orderBy('name', 'ASC')->get();
            }
            if (count($staff)==0) {
                $req->session()->flash('feedback', true);
                $req->session()->flash('feedback_text', "No maching records found. Try to search again.");
                $req->session()->flash('feedback_icon', "remove");
                $req->session()->flash('feedback_color', "#D9534F");
            } elseif (count($staff) > 500) {
                $req->session()->flash('feedback', true);
                $req->session()->flash('feedback_text', "Too many result. Please refine your search.");
                $req->session()->flash('feedback_icon', "remove");
                $req->session()->flash('feedback_color', "#D9534F");
            } else {
                $staffr = $staff->pluck('id');
            }
        }
        Session::put(['staffs'=>$staffr]);
        if (!empty($auth)) {
            return redirect(route('staff.list.auth', [], false));
        } elseif (!empty($mgmt)) {
            return redirect(route('staff.list.mgmt', [], false));
        } else {
            return redirect(route('staff.list', [], false));
        }
    }


    public function idxStaff(Request $req)
    {
        // if($req->session()->has('staffs')) {
        // if($req->filled('staffs')) {
        //   $staff = User::find($req->session()->get('staffs'));
        // }else{
        //   $staff = [];
        // }
        $staff = [];
        return view('admin.staffidx', ['staffs' => $staff]);
    }


    public function cariStaff(Request $req)
    {
        $averifier_detail = [];
        $staffr = [];
        // $ipersno = str_replace(' ','',$req->inputpersno);
        // dd($ipersno,$req->inputpersno);
        // $iStaffno = $req->inputStaffno;
        // $iPMIC = $req->inputPMIC;
        $iName = $req->inputName;
        $iEmail = $req->inputEmail;
        if (isset($req->inputpersno)) {
            $ipersno = explode(",", str_replace(' ', '', $req->inputpersno));//convert str to arry
      $iipersno = preg_replace('/[^A-Za-z0-9\-]/', '', $ipersno);//convert str to arry
      // dd($ipersno,$iipersno);
        }
        if (isset($req->inputStaffno)) {
            $iStaffno = explode(",", str_replace(' ', '', $req->inputStaffno));//convert str to arry
      $iiStaffno = preg_replace('/[^A-Za-z0-9\-]/', '', $iStaffno);//convert str to arry
        }
        if (isset($req->inputPMIC)) {
            $iPMIC = explode(",", str_replace(' ', '', $req->inputPMIC));//convert str to arry
      $iiPMIC = preg_replace('/[^A-Za-z0-9\-]/', '', $iPMIC);//convert str to arry
        }
        // dd($iStaffno,$iiStaffno,$req->inputStaffno);

        // $persno = explode(",", $req->inputStaffno);//convert str to arry

        if (!empty($ipersno) || !empty($iName) || !empty($iEmail) || !empty($iStaffno) || !empty($iPMIC)) {
            // dd('1');
            $staff = User::query();
            if (isset($iName)) {
                $staff = $staff->where('name', 'LIKE', '%' .$iName. '%');
            }
            if (isset($iEmail)) {
                $staff = $staff->where('email', trim($iEmail));
            }
            if (isset($ipersno)) {
                $staff = $staff->whereIn('persno', $iipersno);
            }
            if (isset($iStaffno)) {
                $staff = $staff->whereIn('staff_no', $iiStaffno);
            }
            if (isset($iPMIC)) {
                $staff = $staff->whereIn('new_ic', $iiPMIC);
            }
            $staff = $staff ->orderBy('name', 'ASC')->get();

            foreach ($staff as $key => $onestaff) {
                $staff_salary = Salary::where('user_id', '=', $onestaff->id)
          ->orderBy('upd_sap', 'desc')
          ->first();

                if ($staff_salary) {
                    $onestaff->gaji=$staff_salary->salary;
                } else {
                    $onestaff->gaji='N/A';
                }

                $OtIndicator = OtIndicator::where('user_id', '=', $onestaff->id)
          ->orderBy('upd_sap', 'desc')
          ->first();
                if ($OtIndicator) {
                    $onestaff->allowance=$OtIndicator->allowance;
                    $onestaff->ot_hour_exception=$OtIndicator->ot_hour_exception;
                    $onestaff->ot_salary_exception=$OtIndicator->ot_salary_exception;
                } else {
                    $onestaff->allowance='N/A';
                    $onestaff->ot_hour_exception='N/A';
                    $onestaff->ot_salary_exception='N/A';
                }


                $currwsr = WsrChangeReq::where('user_id', $onestaff->id)
            ->where('status', 'Approved')
            ->whereDate('start_date', '<=', NOW())
            ->whereDate('end_date', '>=', NOW())
            ->orderBy('action_date', 'desc')
            ->first();

                if ($currwsr) {
                    $onestaff->wccode=$currwsr->shiftpattern->code;
                    $onestaff->wcdesc=$currwsr->shiftpattern->description;
                } else {
                    // no approved change req for that date
                    // find the data from SAP
                    $currwsr = UserShiftPattern::where('user_id', $onestaff->id)
              ->whereDate('start_date', '<=', NOW())
              ->whereDate('end_date', '>=', NOW())
              ->orderBy('start_date', 'desc')
              ->first();

                    // dd($currwsr);
                    if ($currwsr) {
                        $onestaff->wccode=$currwsr->shiftpattern->code;
                        $onestaff->wcdesc=$currwsr->shiftpattern->description;
                    } else {
                        $onestaff->wccode='N/A';
                        $onestaff->wcdesc=''; //not found
                    }
                }


                $verifierGroupMember = VerifierGroupMember::where('user_id', $onestaff->id)
              // ->where('start_date', '>=' ,NOW())
              // ->where('end_date', '<' ,NOW())
              ->first();
                //dd($verifierGroupMember);

                if ($verifierGroupMember) {
                    $verifierGroup = VerifierGroup::find($verifierGroupMember->user_verifier_groups_id);
                    $verifier_detail = UserRecord::where('user_id', '=', $verifierGroup->verifier_id)
              ->orderBy('upd_sap', 'desc')
              ->first();

                    $onestaff->verid=$verifier_detail->user_id;
                    $onestaff->vername=$verifier_detail->name;
                    $onestaff->verstaffno=$verifier_detail->staffno;

                // $verifier_detail = $verifier_detail->pluck('id');
                } else {
                    $onestaff->verid='N/A';
                    $onestaff->vername='N/A';
                    $onestaff->verstaffno='N/A';
                    // $verifierGroup = [];
              // $verifier_detail = [];
                };

                // array_push($averifier_detail,$verifier_detail);
              // dd($currwsr);
              // array_push($acurrwsr,$currwsr);
              // array_push($anf,$nf);
              // array_push($atblwc,$tblwc);
            } //end foreach
        }//end if parameter not empty

        // dd($staffr,$averifier_detail,$acurrwsr,$anf,$atblwc );

        if (count($staff)==0) {
            $req->session()->flash('feedback', true);
            $req->session()->flash('feedback_text', "No maching records found. Try to search again.");
            $req->session()->flash('feedback_icon', "remove");
            $req->session()->flash('feedback_color', "#D9534F");
        } elseif (count($staff) > 500) {
            $req->session()->flash('feedback', true);
            $req->session()->flash('feedback_text', "Too many result. Please refine your search.");
            $req->session()->flash('feedback_icon', "remove");
            $req->session()->flash('feedback_color', "#D9534F");
        } else {
            // $staffr = $staff->pluck('id');
            $staffr = $staff;
        }

        // dd($staffr);
        // dd('2');
        // Session::put(['staffs'=>$staffr]);
        // return redirect(route('staff.idx',['staffs'=>$staffr,'verifier_detail'=>$averifier_detail,'currwsr'=>$acurrwsr,'twc'=>$tblwc,'nf'=>$anf],false));
        return view('admin.staffidx', ['staffs'=>$staffr]);
    }



    public function emptystaffauth()
    {
        Session::put(['staffs'=>[]]);
        return redirect(route('staff.list.auth', [], false));
    }


    public function showRole(Request $req)
    {
        $auth = true;
        $role = Role::all();
        if ($req->session()->has('staffs')) {
            // $staff = $req->session()->get('staffs');
            $staff = User::find($req->session()->get('staffs'));
            return view('admin.staff', [
          // 'staffs' => $req->session()->get('staffs'),
          'staffs' => $staff,
          'auth' => $auth,
          'roles' => $role,
          'feedback' => $req->session()->get('feedback'),
          'feedback_text' => $req->session()->get('feedback_text'),
          'feedback_icon' => $req->session()->get('feedback_icon'),
          'feedback_color' =>  $req->session()->get('feedback_color')
        ]);
        } else {
            $staff = [];
            return view('admin.staff', ['staffs' => $staff, 'roles' => $role, 'auth'=>$auth]);
        }
    }

    public function showMgmt(Request $req)
    {
        $mgmt = true;
        $company = Company::all();
        $state = State::all();
        if ($req->session()->has('staffs')) {
            // $staff = $req->session()->get('staffs');
            $staff = User::find($req->session()->get('staffs'));
            return view('admin.staff', [
          // 'staffs' => $req->session()->get('staffs'),
          'staffs' => $staff,
          'mgmt' => $mgmt,
          'companies' => $company,
          'states' => $state,
          'feedback' => $req->session()->get('feedback'),
          'feedback_text' => $req->session()->get('feedback_text'),
          'feedback_icon' => $req->session()->get('feedback_icon'),
          'feedback_color' =>  $req->session()->get('feedback_color')
        ]);
        } else {
            $staff = [];
            return view('admin.staff', ['staffs' => $staff, 'companies' => $company, 'states' => $state, 'mgmt'=>$mgmt]);
        }
    }

    public function updateRole(Request $req)
    {
        $role = $req->role;
        $update_staff = User::find($req->inputid);
        $update_staff->roles()->sync($role);
        $execute = UserHelper::LogUserAct($req, "User Management", "Update " .$req->inputname. " authorization");
        $feedback = true;
        $feedback_text = "Successfully updated " .$req->inputno. " roles for user ".$update_staff->staff_no.".";
        $feedback_title = "Successfully Updated";
        // $staff = User::all();
        return redirect(route('staff.list.auth', [], false))->with(
            [
          // 'staffs'=>$staff,
          'feedback' => $feedback,
          'feedback_text' => $feedback_text,
          'feedback_title' => $feedback_title]
        );
    }
    public function updateMgmt(Request $req)
    {
        $role = $req->role;
        $update_staff = User::find($req->inputid);
        $update_staff->company_id = $req->company;
        $update_staff->state_id = $req->state;
        $update_staff->save();
        $execute = UserHelper::LogUserAct($req, "User Management", "Update user " .$req->inputname);
        $feedback = true;
        $feedback_text = "Successfully updated " .$req->inputno. ".";
        $feedback_icon = "ok";
        $feedback_color = "#5CB85C";
        // $staff = User::all();
        return redirect(route('staff.list.mgmt', [], false))->with(
            [
          // 'staffs'=>$staff,
          'feedback' => $feedback,
          'feedback_text' => $feedback_text,
          'feedback_icon' => $feedback_icon,
          'feedback_color' => $feedback_color]
        );
    }

    public function showStaffProfile(Request $req)
    {

      // $user_logs->user_id = $req->user()->id;
        // $user_logs->session_id = $req->session()->getId();
        // $user_logs->ip_address = $req->ip();
        // $user_logs->user_agent = $req->userAgent();
        if (isset($req->getProfile)) {
            $staff = User::find($req->getProfile);
            $adm = 'admin';//display button return
        } else {
            $staff = User::find($req->user()->id);
            $adm = '';
        }

        $staff_detail = UserRecord::where('user_id', '=', $staff->id)
      ->orderBy('upd_sap', 'desc')
      ->first();

        $directreport = User::find($staff->reptto);
        $directreport_detail = UserRecord::where('user_id', '=', $staff->reptto)
      ->orderBy('upd_sap', 'desc')
      ->first();

        // $salary_info = Salary::where('user_id', '=', $staff->id)
        // ->orderBy('upd_sap', 'desc')
        // ->first();

        $verifierGroupMember = VerifierGroupMember::where('user_id', '=', $staff->id)
      ->where('start_date', '>=', NOW())
      ->where('end_date', '<', NOW())
      ->get();
        //dd($verifierGroupMember);

        if ($verifierGroupMember->count() > 0) {
            $verifierGroup = VerifierGroup::find($verifierGroupMember->user_verifier_groups_id);
            $verifier_detail = UserRecord::where('user_id', '=', $verifierGroup->verifier_id)
      ->orderBy('upd_sap', 'desc')
      ->first();
        } else {
            $verifierGroup = [];
            $verifier_detail = [];
        };

        $listsubord = User::where('reptto', '=', $staff->id)
      ->orderBy('name', 'asc')
      ->get();

        //$staff_comp = Company::find($staff->company_id);
        $staff_psubarea = Psubarea::where('persarea', '=', $staff->persarea)
      ->where('perssubarea', '=', $staff->perssubarea)
      ->first();

        $staff_salary = Salary::where('user_id', '=', $staff->id)
      ->orderBy('upd_sap', 'desc')
      ->first();

        $OtIndicator = OtIndicator::where('user_id', '=', $staff->id)
      ->orderBy('upd_sap', 'desc')
      ->first();
        if ($OtIndicator) {
        } else {
            $OtIndicator = [];
        }



        // $worksch = UserHelper::GetWorkSchedRule($staff->id, NOW());

        // dd($worksekedul);
        // first, check if there's any approved change req
        $nf = '';
        $currwsr = WsrChangeReq::where('user_id', $staff->id)
      ->where('status', 'Approved')
      ->whereDate('start_date', '<=', NOW())
      ->whereDate('end_date', '>=', NOW())
      ->orderBy('action_date', 'desc')
      ->first();

        if ($currwsr) {

      // $code = $currwsr->shiftpattern->code;
      // $des = $currwsr->shiftpattern->description;
      // dd($currwsr, $code, $des);
        } else {
            // no approved change req for that date
            // find the data from SAP
            $currwsr = UserShiftPattern::where('user_id', $staff->id)
      ->whereDate('start_date', '<=', NOW())
      ->whereDate('end_date', '>=', NOW())
      ->orderBy('start_date', 'desc')
      ->first();

            // dd($currwsr);
            if ($currwsr) {
            } else {
                // also not found. just return OFF1 as default
      // $currwsr = ShiftPattern::where('code', 'OFF1')->first();
      $nf = 'User shift pattern not found'; //not found


      // return $sptr;
            }
        }
        // dd($currwsr);

        // dd($staff_salary,$staff->id);

        //dd($staff_psubarea);
        return view(
            'staff.profile',
            [
      'usertbl' => $staff,
      'userrecord' => $staff_detail,
      'direct_report' => $directreport,
      'direct_report_detail' => $directreport_detail,
      'verifier_group' => $verifierGroup,
      'verifier_detail' => $verifier_detail,
      'staff_psubarea' => $staff_psubarea,
      'list_subord' => $listsubord,
      'gaji' => $staff_salary,
      'OtIndicator' => $OtIndicator,
      'skedul'=>$currwsr,
      'defaultWS' =>$nf,
      'adm' => $adm
      ]
        );
    }

    public function searchUser(Request $req)
    {
        //
        $data = [];
        if ($req->has('q')) {
            $search = $req->q;
            $data = User::select("id", "name", "staff_no")
                    //->where('reptto','=',$req->user()->id)
                    ->where('name', 'LIKE', "%$search%")
                    ->orWhere('staff_no', 'LIKE', "%$search%")
                    ->orWhere('persno', 'LIKE', "%$search%")
                    //->orWhere('id', $req->user()->id)
                    ->take(100)
                    ->get();
        }

        return response()->json($data);
    }


}
