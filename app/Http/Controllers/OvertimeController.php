<?php

namespace App\Http\Controllers;

use App\Shared\UserHelper;
use App\Shared\URHelper;
use App\User;
use App\UserRecord;
use App\StaffPunch;
use App\Overtime;
use App\OvertimeMonth;
use App\OvertimeDetail;
use App\OvertimeLog;
use App\OvertimePunch;
use App\OvertimeFile;
use App\OvertimeEligibility;
use App\OvertimeFormula;
use App\OvertimeExpiry;
use App\UserVerifier;
use App\UserShiftPattern;
use App\VerifierGroup;
use App\VerifierGroupMember;
use App\Psubarea;
use App\DayType;
use App\Costcenter;
use App\ShiftPlan;
use App\ShiftPattern;
use App\ShiftPlanStaffDay;
use App\Project;
use App\InternalOrder;
use App\MaintenanceOrder;
use App\OtIndicator;
use Exception;

use App\Notifications\OTSubmitted;
use App\Notifications\OTSubmittedNoti;
use App\Notifications\OTVerified;
use App\Notifications\OTVerifiedNoti;
use App\Notifications\OTApproved;
use App\Notifications\OTApprovedNoti;
use App\Notifications\OTVerifiedApplicant;
use App\Notifications\OTQueryVerify;
use App\Notifications\OTQueryVerifyNoti;
use App\Notifications\OTQueryApprove;
use App\Notifications\OTQueryApproveNoti;
use App\Notifications\OTQueryApproverVerify;
// use App\Notifications\OTSubmitted;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OvertimeController extends Controller
{
    //--------------------------------------------------show overtime list--------------------------------------------------
    public function list(Request $req)
    {
        $otlist = Overtime::where('user_id', $req->user()->id)->orderBy('status')->orderBy('date_expiry')->orderBy('date')->get();
        return view('staff.overtime', ['otlist' => $otlist]);
    }

    //--------------------------------------------------show overtime application form--------------------------------------------------
    public function form(Request $req)
    {
        $region = URHelper::getRegion($req->user()->perssubarea);
        $costc = null;
        $type = null;
        $compn = null;
        $orderno = null;
        $orderlist = null;
        $networkn = null;
        $appr = null;
        $data = null;
        $shift = null;
        $start = null;
        $end = null;
        $day = null;
        $wd = null;
        $sp = null;
        //if claim exist
        if ($req->session()->get('claim')!=null) {
            $day = UserHelper::CheckDay($req->user()->id, $req->session()->get('claim')->date);
            $ushiftp = UserHelper::GetUserShiftPatternSAP($req->user()->id, date('Y-m-d', strtotime($req->session()->get('claim')->date))." 00:00:00");
            $shiftpattern = ShiftPattern::where('code', $ushiftp)->first();
            // dd($day);
            if($shiftpattern->is_weekly != 1){
                
                $wd = ShiftPlanStaffDay::where('user_id', $req->user()->id)
                ->whereDate('work_date', $req->session()->get('claim')->date)->first();
                if($wd){
                    $sp = ShiftPlan::where("id", $wd->shift_plan_id)->first();
                    if($sp){
                        if($sp->status!="Approved"){
                            $wd = null;
                        }
                    }else{
                        $wd = null;
                    }
                }
            }
            if($wd){
                $shift = "Yes";
                $start = $day[8];
                $end = $day[5];
                // $start = $day[0];
                // $end = $day[0];
            }else{
                $shift = "No";
                $start = "00:00";
                $end = $day[5];
            }
            // dd($shift);
            // $eligiblehour = OvertimeEligibility::where('company_id', $req->user()->company_id)->where('region', $region->region)->where('start_date','<=', $req->session()->get('claim')->date)->where('end_date','>', $req->session()->get('claim')->date)->first();
            $eligiblehour = URHelper::getUserEligibility($req->user()->id, $req->session()->get('claim')->date);
            //if charge type is other cost center
            if ($req->session()->get('claim')->charge_type=="Other Cost Center") {
                $compn = Costcenter::groupBy('company_id')->get();
                $costc = Costcenter::where('company_id', $req->session()->get('claim')->company_id)->get(); //get cost center list
                if (count($costc)==0) {
                    $costc = null;
                }
                $appr = UserRecord::where('upd_sap', '<=', date('Y-m-d'))->where('company_id', $req->session()->get('claim')->company_id)->where('costcentr', $req->session()->get('claim')->other_costcenter)->where('user_id', '!=', $req->user()->id)->where('empsgroup', '!=', 'Non Executive')->get(); //get approcer list
                if (count($appr)==0) {
                    $appr = null;
                }

                //if charge type is project
            } elseif ($req->session()->get('claim')->charge_type=="Project") {
                $orderlist= Project::groupby('project_no')->get();
                if ($req->session()->get('claim')->project_no!=null) {
                    $data = Project::where('project_no', $req->session()->get('claim')->project_no)->first();
                    $networkn= Project::where('project_no', $req->session()->get('claim')->project_no)->get(); //get network no list
                    if (count($networkn)==0) {
                        $networkn = null;
                    }
                    if ($req->session()->get('claim')->network_act_no!=null) {
                        $data = Project::where('project_no', $req->session()->get('claim')->project_no)->where('network_act_no', $req->session()->get('claim')->network_act_no)->first();
                    }
                }

                //if charge type is internal order
            } elseif ($req->session()->get('claim')->charge_type=="Internal Order") {
                $orderno= InternalOrder::all();
                if ($req->session()->get('claim')->order_no!=null) {
                    $data=InternalOrder::where('id', $req->session()->get('claim')->order_no)->first();
                    if ($data!=null) {
                        if ($data->cost_center=="") {
                            $costc = Costcenter::where('company_id', $data->company_code)->get();
                            // dd($data->company_code);
                            if (count($costc)==0) {
                                $costc = null;
                            }
                        }
                        // dd($req->session()->get('claim')->other_costcenter);
                        $appr = UserRecord::where('upd_sap', '<=', date('Y-m-d'))->where('company_id', $req->session()->get('claim')->company_id)->where('costcentr', $req->session()->get('claim')->other_costcenter)->where('user_id', '!=', $req->user()->id)->get();
                        // dd($appr);
                        if (count($appr)==0) {
                            $appr = null;
                        }
                    }
                }

                //if charge type is maintenance order
            } elseif ($req->session()->get('claim')->charge_type=="Maintenance Order") {
                $orderno= MaintenanceOrder::all();
                if ($req->session()->get('claim')->order_no!=null) {
                    $data = MaintenanceOrder::where('id', $req->session()->get('claim')->order_no)->first();
                    // dd($data);
                }
            }

            // dd($start. " ". $end);
            return view('staff.otform', ['draft' =>[],
                                         'claim' => $req->session()->get('claim'),
                                         'day' => $day,
                                         'eligiblehour' => $eligiblehour->hourpermonth,
                                         'costc' => $costc,
                                         'compn' => $compn,
                                         'orderno' => $orderno,
                                         'orderlist' => $orderlist,
                                         'data' => $data,
                                         'networkn' => $networkn,
                                         'appr' => $appr,
                                         'shift' => $shift,
                                         'start' => $start,
                                         'end' => $end]);

        //if new claim after choose date
        } elseif ($req->session()->get('draft')!=null) {
            $draft = $req->session()->get('draft');
            $day = UserHelper::CheckDay($req->user()->id, date('Y-m-d', strtotime($draft[4])));
            $ushiftp = UserHelper::GetUserShiftPatternSAP($req->user()->id, date('Y-m-d', strtotime($draft[4]))." 00:00:00");
            $shiftpattern = ShiftPattern::where('code', $ushiftp)->first();
            if($shiftpattern->is_weekly != 1){
                $wd = ShiftPlanStaffDay::where('user_id', $req->user()->id)
                ->whereDate('work_date', date('Y-m-d', strtotime($draft[4])))->first();
                $sp = ShiftPlan::where("id", $wd->shift_plan_id)->first();
                if($wd){
                    $sp = ShiftPlan::where("id", $wd->shift_plan_id)->first();
                    if($sp){
                        if($sp->status!="Approved"){
                            $wd = null;
                        }
                    }else{
                        $wd = null;
                    }
                }
            }
            if($wd){
                $shift = "Yes";
                $start = $day[8];
                $end = $day[5];
                // $start = $day[0];
                // $end = $day[0];
            }else{
                $shift = "No";
                $start = "00:00";
                $end = $day[5];
            }
            
            // dd($day);
            // dd($start. " ". $end);
            // $eligiblehour = OvertimeEligibility::where('company_id', $req->user()->company_id)->where('region', $region->region)->where('start_date','<=', $draft[4])->where('end_date','>', $draft[4])->first();
            $eligiblehour = URHelper::getUserEligibility($req->user()->id, $draft[4]);
            // dd($req->session()->get('draft'));
            
            return view('staff.otform', ['draft' => $req->session()->get('draft'), 'day' => $day, 'eligiblehour' => $eligiblehour->hourpermonth, 'costc' => $costc, 'shift' => $shift, 'start' => $start,'end' => $end]);
            
        //if apply new claim
        } else {
            return view('staff.otform', []);
        }
    }

    //--------------------------------------------------show overtime form when click update--------------------------------------------------
    public function update(Request $req)
    {
        $claim = Overtime::where('id', $req->inputid)->first();
        Session::put(['draft' => [], 'claim' => $claim]);
        return redirect(route('ot.form', [], false));
    }

    //--------------------------------------------------show overtime form when click view--------------------------------------------------
    public function detail(Request $req)
    {
        $claim = Overtime::where('id', $req->detailid)->first();
        Session::put(['draft' => [], 'claim' => $claim]);
        Session::put(['back' => $req->type]);
        return view('staff.otdetail', ['claim' => $req->session()->get('claim')]);
    }//--------------------------------------------------show overtime form when click view--------------------------------------------------
    public function detailview(Request $req)
    {   
        $claim = Overtime::where('id', $req->detailid)->first();
        //dd($req, $claim);
        Session::put(['draft' => [], 'claim' => $claim]);
        Session::put(['back' => $req->type]);
        return view('staff.otdetailview', ['claim' => $claim]);
    }

    //--------------------------------------------------delete overtime claim--------------------------------------------------
    public function remove(Request $req)
    {
        $claim = Overtime::where('id', $req->delid)->first();
        $day= UserHelper::CheckDay($req->user()->id, $claim->date);
        $dt = DayType::where('id', $day[4])->first();
        $working_minutes = $dt->working_hour*60;
        $updatemonth = OvertimeMonth::find($claim->month_id);
        $worktime = UserHelper::CheckDay($req->user()->id, $claim->date);
        $time = (($claim->total_hour)*60)+$claim->total_minute;
        if($time >= $working_minutes){
            $time = $time - $working_minutes;
        }
        $totaltime = (($updatemonth->hour*60)+$updatemonth->minute)-($time);
        $updatemonth->hour = (int)($totaltime/60);
        $updatemonth->total_hour = (int)($totaltime/60);
        $updatemonth->minute = ($totaltime%60);
        $updatemonth->total_minute = ($totaltime%60);
        $updatemonth->save();

        //delete all relate punch in data
        if ($claim->punch_id!=null) {
            $delpunch = StaffPunch::whereDate('punch_in_time', $claim->date)->get();
            foreach ($delpunch as $delpunches) {
                $delpunches->apply_ot = null;
                $delpunches->save();
            }
        }
        OvertimeLog::where('ot_id', $req->delid)->delete();
        OvertimeDetail::where('ot_id', $req->delid)->delete();
        Overtime::find($req->delid)->delete();
        Session::put(['draft' => [], 'claim' => []]);
        return redirect(route('ot.list', [], false))->with([
            'feedback' => true,
            'feedback_text' => "Your claim application ".$claim->refno." has successfully deleted.",
            'feedback_title' => "Successfully Deleted"
        ]);
    }

    //--------------------------------------------------submit overtime claim from overtime list--------------------------------------------------
    public function submit(Request $req)
    {
        $cansubmit = true;
        $eligibitywarning = false;
        $id = explode(" ", $req->submitid);
        $region = URHelper::getRegion($req->user()->perssubarea);
       
        //checking on selected ot
        for ($i = 0; $i<count($id); $i++) {
            

            //check if checked ot date have leave
            $claim = Overtime::find($id[$i]);
            $leave = UserHelper::CheckLeave($req->user()->id, $claim->date);
            if ($leave) {
                if(!(($leave->leave_type!="HP1")||($leave->leave_type!="HP2"))){
                    if (($leave->opr == "INS")&&($leave->leave_status == "APPROVED"))  {
                        $cansubmit = false;
                    }
                }
            }
        }

        //check if can submit or not
        if ($cansubmit) {
            for ($i = 0; $i<count($id); $i++) {
                $updateclaim = Overtime::find($id[$i]);
                $claim = Overtime::find($id[$i]);
                $day= UserHelper::CheckDay($req->user()->id, $claim->date);
                $dt = DayType::where('id', $day[4])->first();
                $working_minutes = $dt->working_hour*60;
                $eligibility = OtIndicator::where('user_id', $req->user()->id)->where('upd_sap', '<=', date('Y-m-d', strtotime($claim->date)))->first();
                //check if exceeds eligible hours
                // dd($eligibility);
                if ($eligibility) {
                    if ($eligibility->ot_hour_exception!="Y") {
                        // $eligiblehour = URHelper::getUserEligibility($req->user()->company_id, $region->region, $claim->date);
                        $eligiblehour = URHelper::getUserEligibility($claim->user_id, $claim->date);
                        $month = OvertimeMonth::where('id', $claim->month_id)->first();
                        $time = (($claim->total_hour)*60)+$claim->total_minute;
                        if($time >= $working_minutes){
                            $time = $time - $working_minutes;
                        }
                        $totalsubmit = ($month->hour*60+$month->minute) + $time;
                        // dd($eligiblehour->hourpermonth*60);
                        // dd($totalsubmit);
                        
                        //if exceed, disable submition
                        if ($totalsubmit>($eligiblehour->hourpermonth*60)) {
                            $eligibitywarning = true;
                        }
                    }
                }
                
                // check if exceeds eligibility hour
                if ($eligibitywarning) {
                    return redirect(route('ot.list', [], false))->with([
                        'feedback' => true,
                        'feedback_text' => "Your total claim time has exceeded eligible claim time.",
                        'feedback_title' => "Submission failed!"
                    ]);
                } else {

                    // $updateclaim->approver_id = $req->user()->reptto;
                    $updateclaim->submitted_date = date("Y-m-d H:i:s");
                    $execute = UserHelper::LogOT($id[$i], $req->user()->id, "Submitted", "Submitted ".$updateclaim->refno);
                    //check if ot have verifier
                    if ($updateclaim->verifier_id==null) {
                        $updateclaim->status = 'PA';
                    } else {
                        $updateclaim->status = 'PV';
                    }
                    $expiry = OvertimeExpiry::where('company_id', $req->user()->company_id)->where('region', $region->region)->where('start_date', '<=', $claim->date)->where('end_date', '>', $claim->date)->first();
                    if ($expiry->status == "ACTIVE") {
                        if ((($expiry->based_date == "Submit to Approver Date")&&($updateclaim->status == 'PA'))||(($expiry->based_date == "Submit to Verifier Date")&&($updateclaim->status == 'PV'))) {
                            $draftclaim->date_expiry = date('Y-m-d', strtotime("+".$expiry->noofmonth." months"));
                        }
                    }
                    $updatemonth = OvertimeMonth::find($updateclaim->month_id);
                    $time = (($updateclaim->total_hour)*60)+$updateclaim->total_minute;
                    if($time >= $working_minutes){
                        $time = $time - $working_minutes;
                    }
                    $totalsubmit = (($updatemonth->total_hour*60)+$updatemonth->total_minute)+($time);
                    $updatemonth->total_hour = (int)($totalsubmit/60);
                    $updatemonth->total_minute = $totalsubmit%60;
                    $updatemonth->save();
                    $updateclaim->save();
                    //send notification to verifier/approver

                    $claim = Overtime::where('id', $id[$i])->first();
                    $user = $claim->verifier;
                    // $myot = \App\Overtime::where('verifier_id', $user->id)->first();
                    $ccuser = \App\User::orWhere('id', $claim->user_id)->orWhere('id', $claim->approver_id)->get();
                    if ($claim->verifier_id==null) {
                        $user = $claim->approver;
                        // $myot = \App\Overtime::where('approver_id', $user->id)->first();
                        $ccuser = \App\User::orWhere('id', $claim->user_id)->get();
                    }
                    $cc = $ccuser->pluck('email')->toArray();
                    $user->notify(new OTSubmitted($claim, $cc));
                }
            }
            
                    
                    
            return redirect(route('ot.list', [], false))->with([
                'feedback' => true,
                'feedback_text' => "Your overtime claim has successfully submitted.",
                'feedback_title' => "Successfully Submitted"
            ]);
        } else {
            return redirect(route('ot.list', [], false))->with([
                'feedback' => true,
                'feedback_text' => "You are on leave for selected overtime claim.",
                'feedback_title' => "Submission Failed!"
            ]);
        }
    }

    //--------------------------------------------------clear session when click on apply new overtime--------------------------------------------------
    public function formnew(Request $req)
    {
        Session::put(['draft' => [], 'claim' => []]);
        return redirect(route('ot.form', [], false));
    }

    //--------------------------------------------------when select overtime date--------------------------------------------------
    public function formdate(Request $req)
    {
        $otdate = date("Y-m-d", strtotime($req->inputdate));
        
        $shift = false;
        $gm = UserHelper::CheckGM(date("Y-m-d"), $otdate);
        $staffr = URHelper::getUserRecordByDate($req->user()->id, $otdate);
        // $staffr = UserRecord::where('user_id', $req->user()->id)->where('upd_sap','<=',date('Y-m-d'))->first();
        $region = URHelper::getRegion($req->user()->perssubarea);
        $day= UserHelper::CheckDay($req->user()->id, $otdate);
        // dd($day);
        $dy = DayType::where('id', $day[4])->first();
        // dd($day[4]);
        $day_type=$dy->day_type;
        // $elig = OvertimeEligibility::where('company_id', $staffr->company_id)->where('empgroup', $staffr->empgroup)->where('empsgroup', $staffr->empsgroup)->where('psgroup', $staffr->psgroup)->where('region', $staffr->region)->where('start_date','<=', $req->inputdate)->where('end_date','>', $req->inputdate)->first();
        $elig = URHelper::getUserEligibility($req->user()->id, $otdate);
       
        $wd = null;
        $ushiftp = UserHelper::GetUserShiftPatternSAP($req->user()->id, date('Y-m-d', strtotime($otdate))." 00:00:00");
        // dd($ushiftp);
        $shiftpattern = ShiftPattern::where('code', $ushiftp)->first();
            if($shiftpattern->is_weekly != 1){
            $wd = ShiftPlanStaffDay::where('user_id', $req->user()->id)
            ->whereDate('work_date', date('Y-m-d', strtotime($otdate)))->first();
            if($wd){

            }else{
                return redirect(route('ot.form', [], false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your shift planning for date ".date('d.m.Y', strtotime($otdate))." has not yet been created. Please contact you supervisor for shift planning.",
                    'feedback_title' => "Date select failed!"
                ]);
            }
            $shift = true;
            
            $sp = ShiftPlan::where("id", $wd->shift_plan_id)->first();
            // dd($sp);
            if($sp){
                if($sp->status=="Approved"){
                    $shift = true;
                }else{
                    return redirect(route('ot.form', [], false))->with([
                        'feedback' => true,
                        'feedback_text' => "Your shift planning for date ".date('d.m.Y', strtotime($otdate))." has not yet been approved. Please contact you supervisor for shift planning approval.",
                        'feedback_title' => "Date select failed!"
                    ]);
                }
            }else{
                return redirect(route('ot.form', [], false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your shift planning for date ".date('d.m.Y', strtotime($otdate))." has not yet been approved. Please contact you supervisor for shift planning approval.",
                    'feedback_title' => "Date select failed!"
                ]);
            }
        }
        
        // $wd = ShiftPlanStaffDay::where('user_id', $req->user()->id)
        // ->whereDate('work_date', date("Y-m-d", strtotime($req->inputdate)))->first();
        // // dd($wd);
        // $sp = ShiftPlan::where("id", $wd->shift_plan_id)->first();
        // if($sp->status=="Approved"){
            
        // }else{
        //     $wd = null;
        // }
        if($wd){
            $employtype = "Shift";
        }else{
            $employtype = "Normal";
        }
        if($staffr){
            if($staffr->ot_salary_exception == "Y"){
                $salexcep = 'Actual';
            }else{
                //check if overtime_eligibilty record 0
                if($elig){
                    $salexcep = "RM".$elig->salary_cap;
                } else {
                    //E101 - table overtime_eligiblity no record for this user
                    Session::put(['draft' => [], 'claim' => []]);
                    return redirect(route('ot.form', [], false))->with([
                        'feedback' => true,
                        'feedback_text' => "You are not eligible to apply overtime claim on this date! [E101]",
                        'feedback_title' => "Warning"
                    ]);
                }
            }
        }

        if ($elig) {
            Session::put(['draft' => []]);
            $claim = Overtime::where('user_id', $req->user()->id)->where('date', $otdate)->first();

            //check if selected ot date have data or not (if not exist exist)
            if (empty($claim)) {
                $claimdate = $otdate ;
                $claimmonth = date("m", strtotime($claimdate));
                $claimyear = date("y", strtotime($claimdate));
                $claimday = date("l", strtotime($claimdate));
                $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->first();

                //check if selected ot date's month have data or not, if empty create ot month
                if (empty($claimtime)) {
                    $newmonth = new OvertimeMonth;   
                    $newmonth->user_id = $req->user()->id;
                    $newmonth->year = $claimyear;
                    $newmonth->month = $claimmonth;
                    $newmonth->save();
                    $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->first();
                }
                // dd($day[0]);
                if($shift){
                    $punch = OvertimePunch::where('user_id', $req->user()->id)
                    // ->where('date', $otdate)->orWhere('date', date("Y-m-d", strtotime($otdate."+1 day")))
                    ->where('start_time',"<=", date("Y-m-d", strtotime($otdate."+1 day"))." ".$day[5].":00")
                    ->where('end_time',">=", $otdate." ".$day[0].":00")->get();
                }else{
                    $punch = OvertimePunch::where('user_id', $req->user()->id)->where('date', $otdate)->get();
                   
                }

                // dd($punch);
                //check if selected ot date's have punch in data or not, if empty create ot month
                if (count($punch)!=0) {
                    $totalhour = 0;
                    $totalminute = 0;
                    $wage = OvertimeFormula::where('company_id', $req->user()->company_id)->where('region', $region->region)->where('start_date', '<=', $claimdate)->where('end_date', '>', $claimdate)->first();   //temp
                    // $expiry = OvertimeExpiry::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', $claimdate)->where('end_date','>', $claimdate)->first();
                    $draftclaim = new Overtime;
                    $draftclaim->refno = "OT".date("Ymd", strtotime($claimdate))."-".sprintf("%08d", $req->user()->id);
                    $draftclaim->user_id = $req->user()->id;
                    $draftclaim->month_id = $claimtime->id;
                    $draftclaim->date = $otdate;
                    $draftclaim->employee_type = $employtype;
                    $draftclaim->salary_exception = $salexcep;
                    $draftclaim->date_created = date("Y-m-d");
                    // if($expiry->status == "ACTIVE"){
                    //     if($expiry->based_date == "Request Date"){
                    //         $draftclaim->date_expiry = date('Y-m-d', strtotime("+".$expiry->noofmonth." months"));
                    //     }elseif($expiry->based_date == "Overtime Date"){
                    //         $draftclaim->date_expiry = date('Y-m-d', strtotime("+".$expiry->noofmonth." months", strtotime($claimdate)));
                    //     }
                    // }

                    //check if ot is more than 3 months from system date
                    if ($gm) { //if more than 3 months
                        $draftclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($claimdate)));
                        $draftclaim->date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+1 months", strtotime(date("Y-m-d")))))));
                    } else {
                        $draftclaim->approver_id = $req->user()->reptto;

                        //check if user have default verifier or not
                        $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                        if ($vgm) {
                            $vg = VerifierGroup::where('id', $vgm->id)->first();
                            // dd($vgm);
                            if($vg->approver_id == $req->user()->reptto){
                                $draftclaim->verifier_id =  $vg->verifier_id;
                            }
                                
                        }
                        $draftclaim->date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+3 months", strtotime($req->inputdate))))));
                    }
                    $company_id_user = Psubarea::where('persarea',$staffr->persarea)->where('perssubarea',$staffr->perssubarea)->first();
                    // $draftclaim->state_id =  $req->user()->state_id;
                    $draftclaim->state_id =  $staffr->state_id;
                    $draftclaim->daytype_id =  $day[4];
                    $draftclaim->day_type_code =  $day_type;
                    $draftclaim->profile_id =  $staffr->id;
                    $draftclaim->company_id =  $staffr->company_id;
                    $draftclaim->company_id_user =  $company_id_user->id;
                    $draftclaim->persarea =  $staffr->persarea;
                    $draftclaim->perssubarea =  $staffr->perssubarea;
                    $draftclaim->punch_id =  $punch[0]->punch_id;
                    $draftclaim->region =  $region->region;
                    $draftclaim->charge_type =  "Own Cost Center";
                    $draftclaim->costcenter =  $staffr->costcentr;
                    $draftclaim->sal_exception =  $staffr->ot_salary_exception;
                    // $userrecid = URHelper::getUserRecordByDate($req->user()->persno, date('Y-m-d', strtotime($claimdate)));
                    $draftclaim->user_records_id =  $staffr->id;
                    $draftclaim->save();
                    $claim = Overtime::where('user_id', $req->user()->id)->where('date', $otdate)->first();

                    //register user clock in time if have clock in data;
                    foreach ($punch as $punches) {
                        $staffpunch = StaffPunch::find($punches->punch_id);
                        $staffpunch->apply_ot = "X";
                        $newclaim = new OvertimeDetail;
                        $newclaim->ot_id = $claim->id;
                        $newclaim->clock_in = $punches->start_time;
                        $newclaim->clock_out= $punches->end_time;
                        $newclaim->start_time = $punches->start_time;
                        $newclaim->end_time = $punches->end_time;
                        $newclaim->hour = $punches->hour;
                        $newclaim->minute = $punches->minute;
                        $newclaim->checked = "N";
                        $salary = $staffr->salary;
                        
                        //check user ot salary exception
                        if ($staffr->ot_salary_exception=="Y") {
                            // $salarycap = URHelper::getUserEligibility($staffr->company_id, $region->region, $claim->date);
                            $salarycap = URHelper::getUserEligibility($claim->user_id, $claim->date);
                            
                            $salary = $salarycap->salary_cap;
                        }
                        
                        $newclaim->justification = "";
                        $newclaim->in_latitude = $punches->in_latitude;
                        $newclaim->in_longitude = $punches->in_longitude;
                        $newclaim->out_latitude = $punches->out_latitude;
                        $newclaim->out_longitude = $punches->out_longitude;
                        $newclaim->save();
                        $staffpunch->save();
                        $updateclaim = OvertimeDetail::latest()->first(); 
                        $pay = UserHelper::CalOT($updateclaim->id);
                        // $pay = UserHelper::CalOT($salary, $punches->hour, $punches->minute);
                        $draftclaim->amount = $draftclaim->amount + $pay;
                        $newclaim->save();
                        $draftclaim->save();
                    }
                    $execute = UserHelper::LogOT($claim->id, $req->user()->id, "Created draft", "Created draft for ".$claim->refno);
                    $claim = Overtime::where('user_id', $req->user()->id)->where('date', $otdate)->first();
                    Session::put(['draft' => []]);
                }

                //if dont have OT Punch
                else {
                    // $expiry = URHelper::getUserExpiry($staffr->company_id, $region->region, $claimdate);
                    // $dt = DayType::where('id', $day_type)->first();
                    $date_expiry = null;
                    // if(($expiry->based_date = "Request Date")&&($expiry->status = "ACTIVE")){
                    //     $date_expiry = date('Y-m-d', strtotime("+90 days"));
                    // }
                    // if($expiry->status == "ACTIVE"){
                    //     if($expiry->based_date == "Request Date"){
                    //         $date_expiry = date('Y-m-d', strtotime("+".$expiry->noofmonth." months"));
                    //     }elseif($expiry->based_date == "Overtime Date"){
                    //         $date_expiry = date('Y-m-d', strtotime("+".$expiry->noofmonth." months", strtotime($claimdate)));
                    //     }
                    // }
                    // $verify = User::where('id', $req->user()->id)->first();
                    // $verify = null;

                    $verifyn = "N/A";
                    $verifyno = "";
                    $approver = "N/A";
                    //check if ot is more than 3 month from system date
                    if ($gm) {
                        $gmid = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($claimdate)));
                        if ($gmid) {
                            $approve = User::where('id', $gmid)->first();
                            $approver = $approve->name;
                        }
                        $verify = User::where('id', $req->user()->reptto)->first();
                        $verifyn = $verify->name;
                        $verifyno = $verify->staff_no;
                        $date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+1 months", strtotime(date("Y-m-d")))))));
                    } else {
                        $approve = User::where('id', $req->user()->reptto)->first();
                        $approver = $approve->name;
                        $approverno = $approve->staff_no;

                        //check if user have default verifier or not
                        $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                        if ($vgm) {
                            $vg = VerifierGroup::where('id', $vgm->user_verifier_groups_id)->first();
                            if($vg->approver_id == $req->user()->reptto){
                                $verify =  $vg->verifier_id;
                                if ($verify) {
                                    if ($verify!="") {
                                        $verifyn = $vg->name->name;
                                        $verifyno = $vg->name->staff_no;
                                    }
                                }
                            }
                        }
                        $date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+3 months", strtotime($otdate))))));
                    }
                    //get verifier name
                    // if($verify!=null){
                    //     if($verify!=""){
                    //         $verifyn = $vg->name->name;
                    //     }
                    // }
                    $state = UserRecord::where('user_id', $req->user()->persno)->where('upd_sap', '<=', $claimdate)->first();
                    $refno = "OT".date("Ymd", strtotime($claimdate))."-".sprintf("%08d", $req->user()->id);
                    $draft = array($refno,                          //[0] - refno
                                    $date_expiry,                   //[1] - expiry
                                    date("Y-m-d H:i:s"),            //[2] - datetime created
                                    $claimtime,                     //[3] - month
                                    $otdate,                //[4] - date
                                    $req->user()->name,             //[5] - user name
                                    // $state->state_id,               //[6] - stateid
                                    $staffr->state_id,
                                    $staffr->statet->state_descr,
                                    // $state->statet->state_descr,    //[7] - statedescr
                                    $day_type,                      //[8] - day type
                                    $verifyn,                       //[9] - verifier name
                                    $approver,                 //[10] - approver name
                                    $staffr->costcentr,        //[11] - cost center
                                    $verifyno,                 //[12] - approver name
                                    $approverno,            //[13] - cost center
                                    $employtype,            //[14] - employee type
                                    $salexcep);            //[15] - salary exception
                    Session::put(['draft' => $draft]);
                }
            } else {
                Session::put(['draft' => []]);
            }
            Session::put(['claim' => $claim]);      

            //item no 54
            //20201005 fix unstable update overtimemonth table. 
            //update every selection date
            if(isset($claim->id)){
                $execute_upd =  UserHelper::updOtMonthTotalHourMinute($claim->id);
            }

            return redirect(route('ot.form', [], false));
        } else {
            Session::put(['draft' => [], 'claim' => []]);
            return redirect(route('ot.form', [], false))->with([
                'feedback' => true,
                'feedback_text' => "You are not eligible to apply overtime claim on this date!",
                'feedback_title' => "Warning"
            ]);
        }
    }

    //--------------------------------------------------add new time/auto-save form/submit form--------------------------------------------------
    public function formsubmit(Request $req)
    {
        $status = true; //claim status
        $region = URHelper::getRegion($req->user()->perssubarea);
        $staffr = URHelper::getUserRecordByDate($req->user()->id, $req->inputdates);

        //check for existing claim
        $c =  Overtime::where("user_id", $req->user()->id)->where("date", date("Y-m-d", strtotime($req->inputdates)))->first();
        if($c==null){
            if (($req->inputid==null)) {
                
                // dd($req);
                $gm = UserHelper::CheckGM(date("Y-m-d"), date("Y-m-d", strtotime(($req->session()->get('draft'))[4])));
                // $wage = OvertimeFormula::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', ($req->session()->get('draft'))[4])->where('end_date','>', ($req->session()->get('draft'))[4])->first();   //temp
                $draftclaim = new Overtime;
                $draftclaim->refno = ($req->session()->get('draft'))[0];
                $draftclaim->user_id = $req->user()->id;
                $draftclaim->profile_id = $staffr->id;
                $draftclaim->month_id = ($req->session()->get('draft'))[3]->id;
                $draftclaim->date = ($req->session()->get('draft'))[4];
                $draftclaim->date_created = date("Y-m-d", strtotime(($req->session()->get('draft'))[2]));
                $draftclaim->date_expiry = ($req->session()->get('draft'))[1];
                $draftclaim->total_hour = 0;
                $draftclaim->total_minute = 0;
                $draftclaim->total_hours_minutes = 0;
                $draftclaim->amount = 0;
                $day= UserHelper::CheckDay($req->user()->id, $req->session()->get('draft')[4]);
                $day_type=$day[2];
                $dt = DayType::where('id', $day[4])->first();
                $day_typed=$dt->day_type;
                //check if ot date is more than 3 months from system date
                if ($gm) { //if more than 3 months
                    $draftclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime(($req->session()->get('draft'))[2])));
                    $draftclaim->verifier_id =  $req->user()->reptto;
                } else {
                    $draftclaim->approver_id = $req->user()->reptto;
                    $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                    if ($vgm) {
                        $vg = VerifierGroup::where('id', $vgm->user_verifier_groups_id)->first();
                        if ($vg) {
                            if($vg->approver_id == $req->user()->reptto){
                                if ($vg!="") {
                                    $draftclaim->verifier_id =  $vg->verifier_id;
                                }
                            }
                        }
                    }
                }
                $company_id_user = Psubarea::where('persarea',$staffr->persarea)->where('perssubarea',$staffr->perssubarea)->first();
                    
                $draftclaim->daytype_id =  $day[4];
                $draftclaim->day_type_code =  $day_typed;
                $draftclaim->state_id =  ($req->session()->get('draft'))[6];
                $draftclaim->company_id =  $staffr->company_id;
                $draftclaim->company_id_user =  $company_id_user->id;
                $draftclaim->employee_type =  ($req->session()->get('draft'))[14];
                $draftclaim->salary_exception =  ($req->session()->get('draft'))[15];
                $draftclaim->persarea =  $staffr->persarea;
                $draftclaim->perssubarea =  $staffr->perssubarea;
                $draftclaim->region =  $region->region;
                $draftclaim->costcenter =  $staffr->costcentr;
                $draftclaim->user_records_id =  $staffr->id;
                $draftclaim->sal_exception =  $staffr->ot_salary_exception;
                $draftclaim->status = 'D1';
                $draftclaim->save();
                $claim = Overtime::where('user_id', $req->user()->id)->where('date', ($req->session()->get('draft'))[4])->first();
                $id = $claim->id;
                $execute = UserHelper::LogOT($claim->id, $req->user()->id, "Created draft", "Created draft for ".$claim->refno);
                Session::put(['draft' => []]);
            }
        } else {
            if($req->inputid == null){
                $claim =  Overtime::where("user_id", $req->user()->id)->where("date", date("Y-m-d", strtotime($req->inputdates)))->first();
            }else{
                $claim = Overtime::where('id', $req->inputid)->first();
            }
            $id = $claim->id;
            $day= UserHelper::CheckDay($req->user()->id, $claim->date);
            $dt = DayType::where('id', $day[4])->first();
            $gm = UserHelper::CheckGM($claim->date_created, $claim->date);
        }
        $working_minutes = $dt->working_hour*60;
        //check user ot salary exception
        $salary = $staffr->salary;
        if ($staffr->ot_salary_exception=="Y") {
            $salarycap = URHelper::getUserEligibility($claim->user_id, $claim->date);
            $salary = $salarycap->salary_cap;
        }

        //if add new time
        if ($req->formtype=="add") {
            //check if existion 0:00~24:00
            $check = OvertimeDetail::where('ot_id', $claim->id)->get();
            // dd($check);
            foreach ($check as $checkies) {
                if (date("H:i:s", strtotime($checkies->start_time))==date("H:i:s", strtotime($checkies->end_time))) {
                    return redirect(route('ot.form', [], false))->with([
                        'feedback' => true,
                        'feedback_text' => "Time input cannot be within inserted time range!",
                        'feedback_title' => "Input time error"
                    ]);
                }
            }
            $check2 = true;
// dd($working_minutes);
            if($req->usertype=="Shift"){
                if(($req->inputdatenew == "")||($req->inputstartnew == "")||($req->inputendnew == "")||($req->inputremarknew == "")){
                    $check2 = false;
                }
            }else{
                if(($req->inputstartnew == "")||($req->inputendnew == "")||($req->inputremarknew == "")){
                    $check2 = false;
                }
            }
            if($check2){
                $inputendnew2 = $req->inputendnew;
                $dif = (strtotime($inputendnew2) - strtotime($req->inputstartnew))/60;
                $hour = (int) ($dif/60);
                $minute = $dif%60;
                $newdetail = new OvertimeDetail;
                $newdetail->ot_id = $claim->id;
                if($req->usertype=="Shift"){
                    $newdetail->start_time = date("Y-m-d", strtotime($req->inputdatenew))." ".$req->inputstartnew.":00";
                }else{
                    $newdetail->start_time = $claim->date." ".$req->inputstartnew.":00";
                }
                if ($inputendnew2=="24:00") {
                    $newdetail->end_time = date('Y-m-d', strtotime($claim->date . "+1 days"))." 00:00";
                } else {
                    if($req->usertype=="Shift"){
                        $newdetail->end_time = date("Y-m-d", strtotime($req->inputdatenew))." ".$req->inputendnew.":00";
                    }else{
                        $newdetail->end_time = $claim->date." ".$req->inputendnew.":00";
                    }
                }

                $time = ($hour*60)+$minute;
                $time2 = $time;
                $newdetail->hour = $hour;
                $newdetail->minute = $minute;
                $newdetail->checked = "Y";
                $newdetail->justification = $req->inputremarknew;
                $newdetail->is_manual = "X";
                $updateclaim = Overtime::find($claim->id);
                $totaltime = (($updateclaim->total_hour*60)+$updateclaim->total_minute)+(($hour*60)+$minute);
                $updateclaim->total_hour = (int)($totaltime/60);
                $updateclaim->total_minute = ($totaltime%60);
                $updateclaim->total_hours_minutes = $totaltime/60;
                // if($updateclaim->day_type_code=="PH"){
                $updateclaim->eligible_day = 0;
                $updateclaim->eligible_total_hours_minutes_code =  null;
                $updateclaim->eligible_total_hours_minutes = null;
                $updateclaim->eligible_total_hours_minutes_code =  null;
                $code = URHelper::getDayCode($updateclaim->id, $totaltime);
                if(($updateclaim->day_type_code=="N")||($updateclaim->day_type_code=="O")){
                    $updateclaim->eligible_total_hours_minutes = $totaltime/60;
                    if($totaltime/60!=0){
                        $updateclaim->eligible_total_hours_minutes_code =  $code[1];
                    }
                }else{
                    if($updateclaim->day_type_code=="PH"){
                        $wd = ShiftPlanStaffDay::where('user_id', $updateclaim->user_id)->whereDate('work_date', $updateclaim->date)->first();
                        if($wd){
                           $working_minutes = $wd->Day->working_hour*60; 
                        }
                    }
                    if($updateclaim->day_type_code=="PH"){
                        $wd = ShiftPlanStaffDay::where('user_id', $updateclaim->user_id)->whereDate('work_date', $updateclaim->date)->first();
                        if($wd){
                           $working_minutes = $wd->Day->working_hour*60; 
                        }
                    }
                    $updateclaim->eligible_day = 1;
                    $updateclaim->eligible_day_code = $code[0];
                    $totaltime = $totaltime - $working_minutes;
                    if($totaltime/60>0){
                        $updateclaim->eligible_total_hours_minutes = $totaltime/60;
                        $updateclaim->eligible_total_hours_minutes_code =  $code[1];
                    }else{                
                        $totaltime = 0;        
                        $updateclaim->eligible_total_hours_minutes = 0;
                        $updateclaim->eligible_total_hours_minutes_code = null;
                    }
                    // dd($updateclaim->eligible_total_hours_minutes, $updateclaim->eligible_total_hours_minutes_code);
                }
                // dd($working_minutes);
                // $updatemonth = OvertimeMonth::find($claim->month_id);
                // $time = ($claimdetail->hour*60)+$claimdetail->minute;
                // $totaltime = (($updatemonth->hour*60)+$updatemonth->minute)+($time);
                // $updatemonth->hour = (int)($totaltime/60);
                // $updatemonth->minute = ($totaltime%60);
                $newdetail->save();
                // $updatemonth->save();
                $updateclaim->save();

                // 20201009 add to sync updOTMonth table
                $updOTMonth = UserHelper::updOtMonthTotalHourMinute($claim->id);
            }
            // dd($newdetail);
        }

        $havecheckedclaim = false;   //check have checked claim or not
        
        //if (save form/submit form/ delete time/file)
        if (($req->formtype=="save")||($req->formtype=="submit")||($req->formtype=="delete")) {
            // dd($working_minutes);
            $claim = Overtime::where('id', $claim->id)->first();
            $claimdetail = OvertimeDetail::where('ot_id', $claim->id)->get();
            //operation for all claim hour details
            for ($i=0; $i<count($claimdetail); $i++) {

                //check claim hour detail form is complete
                if(($req->inputstart[$i])&&($req->inputend[$i])){
                    if (($req->inputstart[$i]!="")&&$req->inputend[$i]!="") {
                        $operation = null;

                        //check if draft/query status complete or not (D1/D2/Q1/Q2)
                        if (($req->inputremark[$i]=="")||($req->inputstart[$i]=="")||($req->inputend[$i]=="")) {
                            $status = false;
                        }
                        $dif = (strtotime($req->inputend[$i]) - strtotime($req->inputstart[$i]))/60;
                        $hour = (int) ($dif/60);
                        $minute = $dif%60;
                        // $pay = UserHelper::CalOT($salary, $hour, $minute);
                        $updatedetail = $claimdetail[$i];
                        $uphm = ($updatedetail->hour*60)+($updatedetail->minute);
                        $timebefore = ($updatedetail->hour*60)+$updatedetail->minute;

                        $updatedetail->hour = $hour;
                        $updatedetail->minute = $minute;
                        $updatedetail->save();

                        $updatedetail= $claimdetail[$i];
                        $pay = UserHelper::CalOT($updatedetail->id);
                        $updatedetail->start_time = date('Y-m-d', strtotime($updatedetail->start_time))." ".$req->inputstart[$i].":00";
                        
                        if ($req->inputend[$i]=="24:00") {
                            if(date('Y-m-d', strtotime($updatedetail->end_time))==$claim->date){
                                $updatedetail->end_time = date('Y-m-d', strtotime($updatedetail->end_time . "+1 days"))." 00:00:00";
                            }else{
                                $updatedetail->end_time = date('Y-m-d', strtotime($updatedetail->end_time))." 00:00:00";
                            }
                        } else {
                            
                            if(date('Y-m-d', strtotime($updatedetail->start_time))==$claim->date){
                                $updatedetail->end_time = date('Y-m-d', strtotime($claim->date))." ".$req->inputend[$i].":00";
                            }else{
                                $updatedetail->end_time = date('Y-m-d', strtotime($updatedetail->end_time))." ".$req->inputend[$i].":00";
                            }
                        }

                        //check if checkbox changed or not
                        if ($updatedetail->checked != $req->inputcheck[$i]) {
                            $updatedetail->checked = $req->inputcheck[$i];
                            $operation = $req->inputcheck[$i];
                        }
                        $updatedetail->justification = $req->inputremark[$i];
                        $updatemonth = OvertimeMonth::find($claim->month_id);
                        $updateclaim = Overtime::find($claim->id);

                        $time = ($hour*60)+$minute;
                        
                        $timeafter = ($updatedetail->hour*60)+$updatedetail->minute;
                        
                        //if checkbox changed
                        if ($operation=="Y") {
                            $totaltimem = (($updatemonth->hour*60)+$updatemonth->minute)+$time;
                            $totaltime = (($updateclaim->total_hour*60)+$updateclaim->total_minute)+(($hour*60)+$minute);
                            $updateclaim->amount = $updateclaim->amount + $pay;
                        } elseif ($operation=="N") {
                            $totaltimem = (($updatemonth->hour*60)+$updatemonth->minute)-$time;
                            // dd($totaltime);
                            $totaltime = (($updateclaim->total_hour*60)+$updateclaim->total_minute)-(($hour*60)+$minute);
                            $updateclaim->amount = $updateclaim->amount - $pay;
                        } else {  //if checkbox not changed
                            $totaltime = (($updateclaim->total_hour*60)+$updateclaim->total_minute)-$uphm+$time;
                            $totaltimebefore = ($updateclaim->total_hour*60)+$updateclaim->total_minute;
                            if($totaltime<$working_minutes){
                                if($totaltimebefore<$working_minutes){
                                    $addtime = 0;
                                }else{
                                    $addtime = $totaltimebefore - $working_minutes;
                                }
                            }else{
                                if($totaltimebefore<$working_minutes){
                                    $addtime = $totaltime - $working_minutes;
                                }else{
                                    $addtime = $totaltime - $totaltimebefore;
                                }
                            }
                            $totaltimem = (($updatemonth->hour*60)+$updatemonth->minute)+$addtime;
                            $updateclaim->amount = $updateclaim->amount - $updatedetail->amount + $pay;
                        }
                        $updateclaim->total_hour = (int)($totaltime/60);
                        $updateclaim->total_minute = ($totaltime%60);
                        $updateclaim->total_hours_minutes = $totaltime/60;
                        $code = URHelper::getDayCode($updateclaim->id, $totaltime);
                        if(($updateclaim->day_type_code=="N")||($updateclaim->day_type_code=="O")){
                            $updateclaim->eligible_total_hours_minutes = $totaltime/60;
                            if($totaltime/60!=0){
                                $updateclaim->eligible_total_hours_minutes_code =  $code[1];
                            }else{
                                $updateclaim->eligible_total_hours_minutes_code = null;
                            }
                        }else{
                            if($updateclaim->day_type_code=="PH"){
                                $wd = ShiftPlanStaffDay::where('user_id', $updateclaim->user_id)->whereDate('work_date', $updateclaim->date)->first();
                                if($wd){
                                   $working_minutes = $wd->Day->working_hour*60; 
                                }
                            }
                            $updateclaim->eligible_day = 1;
                            $updateclaim->eligible_day_code = $code[0];
                            if($totaltime >= $working_minutes){
                                $totaltime = $totaltime - $working_minutes;
                                if($totaltime/60>0){
                                    $updateclaim->eligible_total_hours_minutes = $totaltime/60;
                                    $updateclaim->eligible_total_hours_minutes_code =  $code[1];
                                }else{         
                                    $totaltime = 0;                 
                                    $updateclaim->eligible_total_hours_minutes = 0;
                                    $updateclaim->eligible_total_hours_minutes_code = null;
                                }
                            }else{
                                $updateclaim->eligible_total_hours_minutes = 0;
                                $updateclaim->eligible_total_hours_minutes_code =  null;
                            } 
                        }
                        $updatedetail->checked = $req->inputcheck[$i];                     
                        $updatemonth->hour = (int)($totaltimem/60);
                        $updatemonth->minute = ($totaltimem%60);
                        $updatedetail->save();
                        $updatemonth->save();
                        $updateclaim->save();

                        if ($updatedetail->checked=="Y") {
                            $havecheckedclaim = true;
                        
                        }
                    }
                    
                }
            }
        }
        $claimdetail = OvertimeDetail::where('ot_id', $claim->id)->get();

        //check claim charge type empty or claim hours exist
        if (($req->chargetype=="")||(count($claimdetail)==0)||($claim->approver_id==null)) {
            $status = false;
        }

        $updateclaim = Overtime::find($claim->id);
        
        //update claim status
        if ($status) {
            if ($updateclaim->status=="D1") {
                $updateclaim->status = 'D2';
            } elseif ($updateclaim->status=="Q1") {
                $updateclaim->status = 'Q2';
            }
        } else {
            if ($updateclaim->status=="D2") {
                $updateclaim->status = 'D1';
            } elseif ($updateclaim->status=="Q2") {
                $updateclaim->status = 'Q1';
            }
        }

        //if charge type changed
        $resetapprove = false;
        if (($updateclaim->charge_type!=$req->chargetype)) {
            if (in_array($req->chargetype, $array = array("Project", "Internal Order", "Maintenance Order", "Other Cost Center"))) {
                $updateclaim->company_id = null;
                $updateclaim->other_costcenter = null;
            } else {
                $updateclaim->company_id = $staffr->company_id;
                $updateclaim->other_costcenter = $staffr->costcentr;
            }
            $updateclaim->charge_type = null;
            $updateclaim->order_no = null;
            $req->orderno = null;
            $updateclaim->project_no = null;
            $updateclaim->project_type = null;
            $updateclaim->network_header = null;
            $updateclaim->network_act_no = null;
            $resetapprove = true;
        }

        // if company code changed
        if (($updateclaim->company_id!=$req->compn)) {
            $resetapprove = true;
        }

        // if cost center changed
        if ($updateclaim->other_costcenter!=$req->costc) {
            $resetapprove = true;
        }

        //reset claim approver and verifier
        if ($resetapprove) {

            //check if ot is more than 3 month from system date
            if ($gm) { //if more than 3 months
                $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                $updateclaim->verifier_id =  $req->user()->reptto;
            } else {
                $updateclaim->approver_id = $req->user()->reptto;

                //check if user have default verifier or not
                $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                if ($vgm) {
                    $vg = VerifierGroup::where('id', $vgm->user_verifier_groups_id)->first();
                    if($vg->approver_id == $req->user()->reptto){
                        $updateclaim->verifier_id =  $vg->verifier_id;
                    }
                }
            }
        }
        $updateclaim->charge_type = $req->chargetype;
        
        //do this if charge type is project/internal order/maintenace order/other cost center
        if (in_array($req->chargetype, $array = array("Project", "Internal Order", "Maintenance Order", "Other Cost Center"))) {
            
            //if charge type internal order/maintenance order
            if (in_array($req->chargetype, $array = array("Internal Order", "Maintenance Order"))) {
                $updateclaim->order_no = $req->orderno;
                $updateclaim->company_id = null;
                if ($req->orderno!=null) {

                    //if internal order
                    if ($req->chargetype == "Internal Order") {
                        $data=InternalOrder::where('id', $req->orderno)->first();
                        if ($data!=null) {
                            $updateclaim->project_type = $data->order_type;
                            if ($req->approvern!=null) {

                                //check if ot is more than 3 months from system date
                                if ($gm) { //if more than 3 months
                                    $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                                    $updateclaim->verifier_id =  $req->approvern;
                                } else {
                                    $updateclaim->approver_id = $req->approvern;
                                    
                                    //check if user have default verifier or not
                                    $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                                    if ($vgm) {
                                        $vg = VerifierGroup::where('id', $vgm->user_verifier_groups_id)->first();
                                        if($vg->approver_id == $req->user()->reptto){
                                            $updateclaim->verifier_id =  $vg->verifier_id;
                                        }
                                    }
                                }
                            }

                            //if cost center exist or not
                            if ($req->costc!=null) {
                                $updateclaim->other_costcenter = $req->costc;
                            } else {
                                $updateclaim->other_costcenter = $data->cost_center;
                            }
                            $updateclaim->company_id = $data->company_code;
                        }

                        //if charge type maintenance order
                    } else {
                        $data=MaintenanceOrder::where('id', $req->orderno)->first();
                        if ($data!=null) {
                            $updateclaim->project_type = $data->type;
                            if ($data->approver_id!="") {

                                //check if ot is more than 3 months from system date
                                if ($gm) {
                                    $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                                    $updateclaim->verifier_id = $data->approver_id;
                                } else {
                                    $updateclaim->approver_id = $data->approver_id;

                                    //check if user have default verifier or not
                                    $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                                    if ($vgm) {
                                        $vg = VerifierGroup::where('id', $vgm->user_verifier_groups_id)->first();
                                        if($vg->approver_id == $req->user()->reptto){
                                            $updateclaim->verifier_id =  $vg->verifier_id;
                                        }
                                    }
                                }
                            }
                            $updateclaim->other_costcenter = $data->cost_center;
                            $updateclaim->company_id = $data->company_code;
                        }
                    }
                }

                //if charge type project
            } elseif ($req->chargetype=="Project") {
                if ($req->orderno!=null) {
                    if ($updateclaim->project_no != $req->orderno) {
                        $updateclaim->project_no = $req->orderno;
                        $data = Project::where('project_no', $req->orderno)->first();
                        if ($data!=null) {
                            $updateclaim->project_type = $data->type;
                            $updateclaim->network_header = $data->network_header;
                            $updateclaim->network_act_no = null;
                        }
                    } else {
                        // dd("S");
                        $updateclaim->project_no = $req->orderno;
                        $data = Project::where('project_no', $req->orderno)->first();
                        if ($data!=null) {
                            $updateclaim->project_type = $data->type;
                        }

                        $updateclaim->network_header = $req->networkh;
                        $updateclaim->network_act_no = $req->networkn;
                        if ($req->networkn!=null) {
                            $data = Project::where('project_no', $req->orderno)->where('network_act_no', $req->networkn)->first();
                            if ($data!=null) {

                                
                                //check if ot is more than 3 months from system date
                                // if ($gm) { //if more than 3 months
                                //     $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                                //     $updateclaim->verifier_id = $data->approver_id;
                                //     if ($data->approve_id==0) {
                                //         $updateclaim->verifier_id = null;
                                //     }
                                // } else {
                                //     $updateclaim->approver_id = $data->approver_id;
                                //     if ($data->approve_id==0) {
                                //         $updateclaim->verifier_id = null;
                                //     }
                                //     $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                                //     if ($vgm) {
                                //         $vg = VerifierGroup::where('id', $vgm->user_verifier_groups_id)->first();
                                //         $updateclaim->verifier_id =  $vg->verifier_id;
                                //     }
                                // }
                                $updateclaim->company_id = $data->company_code;
                            }
                        }
                    }
                }

                //if charge type other cost center
            } elseif ($req->chargetype=="Other Cost Center") {
                $updateclaim->company_id = $req->compn;
                if ($req->costc!=null) {
                    $updateclaim->other_costcenter = $req->costc;
                }
                if ($req->approvern!=null) {
                    
                    //check if ot is more than 3 months from system date
                    if ($gm) { //if more than 3 months
                        $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                        $updateclaim->verifier_id =  $req->approvern;
                    } else {
                        $updateclaim->approver_id = $req->approvern;
                        $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                        if ($vgm) {
                            $vg = VerifierGroup::where('id', $vgm->user_verifier_groups_id)->first();
                            if($vg->approver_id == $req->user()->reptto){
                                $updateclaim->verifier_id =  $vg->verifier_id;
                            }
                        }
                    }
                }
            }
        }
        $updateclaim->save();
        $updateclaim = Overtime::find($claim->id);
        $wla = UserHelper::GetWageLegacyAmount($claim->id);
        $updateclaim->amount = $wla[1];
        
        $claimdetail = OvertimeDetail::where('ot_id', $claim->id)->get();
        $amount = 0;
        foreach($claimdetail as $dt){
            $updt = OvertimeDetail::find($dt->id);
            if ($updt->checked=="Y") {
                $updateclaim->amount = $amount+$updt->amount;
                $amount = $updateclaim->amount;
            
            }
        }


        $updateclaim->save();

        //check if delete time/file
        if (($req->inputfile!="")&&($req->formtype!="delete")) {  //if not, upload file if exist
            $file   =   $req->file('inputfile');
            if (in_array($file->getClientOriginalExtension(), $array = array("pdf", "jpeg", "jpg", "bmp", "png", "tiff"))) {
                $name = date("ymd", strtotime($updateclaim->date))."-".sprintf("%08d", $req->user()->id)."-".rand(10000, 99999)."-".$file->getClientOriginalName();
                $target_path    =   storage_path('/app/public/');
                $store = $file->storeAs('public', $name);
                if ($file->getClientOriginalExtension()=="pdf") {
                    $imagick = new \Imagick($file.'[0]');
                    $newname = str_replace(".pdf", "", $name);
                    $fileName = $newname . '.jpg';
                    $imagick->setImageFormat('jpg');
                    $imagick->writeImage($target_path.$fileName);
                }
                $claimfile = new OvertimeFile;
                $claimfile->ot_id = $claim->id;
                $claimfile->filename =  $name;
                if ($file->getClientOriginalExtension()=="pdf") {
                    $claimfile->thumbnail =  $fileName;
                } else {
                    $claimfile->thumbnail =  $name;
                }
                $claimfile->save();
            } else {
                return redirect(route('ot.form', [], false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your cannot upload files other than .pdf, .jpeg, .jpg, .bmp, .png, .tiff format!",
                    'feedback_title' => "Warning"
                ]);
            }
            

            //if delete time/file
        } elseif ($req->formtype=="delete") {
            $file = OvertimeFile::where('id', $req->filedel)->first();
            Storage::delete('public/'.$file->filename);
            Storage::delete('public/'.$file->thumbnail);
            OvertimeFile::find($req->filedel)->delete();
        }

        //update claim hours
        $claim = Overtime::where('id', $id)->first();
        $total_hour = OvertimeDetail::where('ot_id', $claim->id)->get();
        $total_hours = 0;
        $total_minutes = 0;
        foreach ($total_hour as $single) {
            $total_hours = $total_hours + ($single->hour*60);
            $total_minutes = $total_minutes + $single->minute;
        }
        $total_minutes = $total_hours+$total_minutes;
        Session::put(['claim' => $claim]);

        //if add new time
        if ($req->formtype=="add") {
            //if total ot hours exceed 12 hours
            if ($total_minutes>=720) {
                return redirect(route('ot.form', [], false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your claim for this date has reached/exceed 12 hours.",
                    'feedback_title' => "Warning"
                ]);
            } else {
                return redirect(route('ot.form', [], false));
            }
        }

        //if auto save form
        if ($req->formtype=="save") {

            //if total ot hours exceed 12 hours
            if ($total_minutes>=720) {
                return redirect(route('ot.form', [], false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your claim for this date has reached/exceed 12 hours.",
                    'feedback_title' => "Warning"
                ]);
            } else {
                return redirect(route('ot.form', [], false));
            }
        }

        //if delete form
        if ($req->formtype=="delete") {
            return redirect(route('ot.form', [], false))->with([
                'feedback' => true,
                'feedback_text' => "Successfully deleted file.",
                'feedback_title' => "Success"
            ]);
        }
        if ($req->formtype=="submit") { //if submit
            $cansubmit = true;  //can submit ot or not
            $haveapprover = true; //approve have or not
            $haveprojecttype = true;
            //check for leave
            $leave = UserHelper::CheckLeave($req->user()->id, $claim->date);
            if ($leave) {
                if(!(($leave->leave_type!="HP1")||($leave->leave_type!="HP2"))){
                    if (($leave->opr == "INS")&&($leave->leave_status == "APPROVED"))  {
                        $cansubmit = false;
                    }
                }
            }


            //check for approver
            if (($claim->approver_id==null)||($claim->approver_id==0)) {
                $haveapprover = false;
            }

            //check for project type
            if (in_array($claim->charge_type, $array = array("Project", "Internal Order", "Maintenance Order"))) {
                if($claim->project_type==null){
                    $haveprojecttype=false;
                }
            }

            if ($havecheckedclaim) {
                //if not on leave
                if (($cansubmit)&&($haveapprover)&&($haveprojecttype)) {
                    $month = OvertimeMonth::where('id', $claim->month_id)->first();
                    $time = ($claim->total_hour*60)+$claim->total_minute;
                    if($time >= $working_minutes){
                        $time = $time - $working_minutes;
                    }
                    $totalsubmit = $time+(($month->total_hour*60)+$month->total_minute);
                    $updatemonth = OvertimeMonth::find($month->id);
                    $updatemonth->total_hour = (int)($totalsubmit/60);
                    $updatemonth->total_minute = $totalsubmit%60;
                    $updatemonth->save();
                    $updateclaim = Overtime::find($claim->id);
                    $updateclaim->submitted_date = date("Y-m-d H:i:s");
                    $execute = UserHelper::LogOT($claim->id, $req->user()->id, "Submitted", "Submitted ".$updateclaim->refno);
                    $expiry = URHelper::getUserExpiry($staffr->company_id, $region->region, $claim->date);
                    if ($updateclaim->verifier_id==null) {
                        $updateclaim->status = 'PA';
                    } else {
                        $updateclaim->status = 'PV';
                    }

                    $updateclaim->save();
                    
                    //check eligibity
                    $eligibility = URHelper::getUserEligibility($claim->user_id, $claim->date);
                    
                    //send notification to verifier/approver
                    $claim = Overtime::where('id', $claim->id)->first();
                    $user = $claim->verifier;
                    $ccuser = \App\User::orWhere('id', $claim->user_id)->orWhere('id', $claim->approver_id)->get();
                    if ($claim->verifier_id==null) {
                        $user = $claim->approver;
                        $ccuser = \App\User::orWhere('id', $claim->user_id)->get();
                    }
                    $cc = $ccuser->pluck('email')->toArray();
                    $uc = $user->pluck('email')->toArray();

                    $checkemail = URHelper::isValidEmail($uc); 
                    $checkemailcc = URHelper::isValidEmail($cc);     
                    if(($checkemail)&&($checkemailcc)){
                        $user->notify(new OTSubmitted($claim, $cc));
                    }else{
                        $user->notify(new OTSubmittedNoti($claim, $cc));
                    }
                    if ($eligibility) {

                        //check if eligible for ot hour exception
                        if ($eligibility->ot_hour_exception!="Y") {   //if not
                            $reg = Psubarea::where('state_id', $req->user()->state_id)->first();
                            // $eligiblehour = OvertimeEligibility::where('company_id', $staffr->company_id)->where('empgroup', $staffr->empgroup)->where('empsgroup', $staffr->empsgroup)->where('psgroup', $staffr->psgroup)->where('region', $staffr->region)->where('start_date','<=', $claim->date)->where('end_date','>', $claim->date)->first();
                            // $eligiblehour = OvertimeEligibility::where('company_id', $req->user()->company_id)->where('region', $region->region)->where('start_date','<=', $claim->date)->where('end_date','>', $claim->date)->first();
                            $month = OvertimeMonth::where('id', $claim->month_id)->first();
                            $totalsubmit = (($claim->total_hour*60)+$claim->total_minute)+(($month->total_hour*60)+$month->total_minute);

                            //check if total claim time exceeds eligible ot time to claim
                            if ($totalsubmit>($eligibility->hourpermonth*60)) {
                                // if($totalsubmit>($eligiblehour->hourpermonth*60)){
                                return redirect(route('ot.list', [], false))->with([
                                    'feedback' => true,
                                    'feedback_text' => "Warning! Your overtime claim has exceeded eligible claim hours of ".$eligibility->hourpermonth." hours.",
                                    'feedback_title' => "Successfully Submitted"
                                ]);
                            } else {
                                return redirect(route('ot.list', [], false))->with([
                                    'feedback' => true,
                                    'feedback_text' => "Your overtime claim has successfully submitted.",
                                    'feedback_title' => "Successfully Submitted"
                                ]);
                            }
                        } else {
                            return redirect(route('ot.list', [], false))->with([
                                'feedback' => true,
                                'feedback_text' => "Your overtime claim has successfully submitted.",
                                'feedback_title' => "Successfully Submitted"
                            ]);
                        }
                    } else {
                        return redirect(route('ot.list', [], false))->with([
                            'feedback' => true,
                            'feedback_text' => "Your overtime claim has successfully submitted.",
                            'feedback_title' => "Successfully Submitted"
                        ]);
                    }
                
                    //if on leave
                } elseif (!($cansubmit)) {
                    return redirect(route('ot.form', [], false))->with([
                        'feedback' => true,
                        'feedback_text' => "You are on leave for this date.",
                        'feedback_title' => "Submission Failed!"
                    ]);
                } elseif (!($haveapprover)) {
                    return redirect(route('ot.form', [], false))->with([
                        'feedback' => true,
                        'feedback_text' => "OT approver for this project currently blank. Please contact your project manager to update OT approver for this project.",
                        'feedback_title' => "Submission Failed!"
                    ]);
                } elseif (!($haveprojecttype)) {
                    return redirect(route('ot.form', [], false))->with([
                        'feedback' => true,
                        'feedback_text' => "Project type currently blank. Please contact your project manager to update OT approver for this project.",
                        'feedback_title' => "Submission Failed!"
                    ]);
                }
            } else {
                return redirect(route('ot.form', [], false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your claim date must have a checked claim time.",
                    'feedback_title' => "Submission Failed!"
                ]);
            }
        }
    }

    //--------------------------------------------------get image thumbnail--------------------------------------------------
    public function getthumbnail(Request $req)
    {
        $file = OvertimeFile::find($req->tid);
        if ($file) {
            return Storage::download('public/'.$file->thumbnail);
        }
    }

    //--------------------------------------------------get image file--------------------------------------------------
    public function getfile(Request $req)
    {
        $file = OvertimeFile::find($req->tid);
        if ($file) {
            //ADD PERMISSION

            // return asset('public/'.$file->filename);
            // dd(asset('public/'.$file->filename));
            // return (Storage::download('public/'.$file->filename));
            return Storage::download('public/'.$file->filename, $file->filename, [
                'Content-Disposition' => 'inline'
            ]);
        }
    }

    //--------------------------------------------------delete ot detail time--------------------------------------------------
    public function formdelete(Request $req)
    {
        $claimdetail = OvertimeDetail::where('id', $req->delid)->first();
        $claim =  Overtime::where('id', $claimdetail->ot_id)->first();
        $day= UserHelper::CheckDay($req->user()->id, $claim->date);
        $dt = DayType::where('id', $day[4])->first();
        $working_minutes = $dt->working_hour*60;
        $start = $claimdetail->start_time;
        $end = $claimdetail->end_time;
        $updatemonth = OvertimeMonth::find($claim->month_id);
        
        $updateclaim = Overtime::find($claim->id);

        if($claimdetail->checked=="Y"){
            $totaltime = (($updateclaim->total_hour*60)+$updateclaim->total_minute)-((($claimdetail->hour)*60)+$claimdetail->minute);
            $updateclaim->total_hour = (int)($totaltime/60);
            $updateclaim->total_minute = ($totaltime%60);
            $updateclaim->total_hours_minutes = $totaltime/60;
            $updateclaim->amount = $updateclaim->amount - $claimdetail->amount;
        }else{
            $totaltime = 0;
        }
        OvertimeDetail::find($req->delid)->delete();
        if ($claimdetail->clock_in!=null) {
            $delotpunch = OvertimePunch::where('start_time', $claimdetail->clock_in)->delete();
        }
        $claimdetail = OvertimeDetail::where('ot_id', $claim->id)->get();
        if (count($claimdetail)==0) {
            $updateclaim->status = 'D1';
        }
        $code = URHelper::getDayCode($updateclaim->id, $totaltime);
        $time = ($updateclaim->total_hour*60)+$updateclaim->total_minute;
        if(($updateclaim->day_type_code=="N")||($updateclaim->day_type_code=="O")){
            $updateclaim->eligible_total_hours_minutes = $totaltime/60;
            if($totaltime/60!=0){
                $updateclaim->eligible_total_hours_minutes_code =  $code[1];
            }
        }else{
            if($updateclaim->day_type_code=="PH"){
                $wd = ShiftPlanStaffDay::where('user_id', $updateclaim->user_id)->whereDate('work_date', $updateclaim->date)->first();
                if($wd){
                   $working_minutes = $wd->Day->working_hour*60; 
                }
            }
            if($totaltime/60!=0){
                $updateclaim->eligible_day = 1;
                $updateclaim->eligible_day_code = $code[0];
            }else{
                $updateclaim->eligible_day = 0;
                $updateclaim->eligible_day_code = null;
            }
            if($totaltime >= $working_minutes){
                $totaltime = $totaltime - $working_minutes;
            } 
            if($totaltime/60>0){
                $updateclaim->eligible_total_hours_minutes = $totaltime/60;
                $updateclaim->eligible_total_hours_minutes_code =  $code[1];
            }else{                        
                $updateclaim->eligible_total_hours_minutes = 0;
                $updateclaim->eligible_total_hours_minutes_code = null;
            }
            if($time >= $working_minutes){
                $time = $time - $working_minutes;
            }
        }
        // dd($time);

        $updatemonth->hour = (int)($totaltime/60);
        $updatemonth->minute = ($totaltime%60);
        $wla = UserHelper::GetWageLegacyAmount($claim->id);
        $updateclaim->amount = $wla[1];

        // dd($totaltime, $updateclaim->eligible_total_hours_minutes, $updateclaim->total_hour, $updatemonth->hour);
        $updateclaim->save();
        $updatemonth->save();
        if($end=="00:00"){
            $end = "24:00";
        }
        $claim = Overtime::where('id', $claim->id)->first();
        Session::put(['claim' => $claim]);
        return redirect(route('ot.form', [], false))->with([
            'feedback' => true,
            'feedback_text' => "Your time ranged from ".date("Hi", strtotime($start))." to ".date("Hi", strtotime($end))." has been deleted.",
            'feedback_title' => "Successfully Deleted"
        ]);
    }

    //--------------------------------------------------show ot verify list--------------------------------------------------
    public function verify(Request $req)
    {
        $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', 'PV')->orderBy('date_expiry')->orderBy('date')->get();
        $view = "verifier";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    //--------------------------------------------------show ot verify report list--------------------------------------------------
    public function verifyrept(Request $req)
    {
        $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', '!=', 'D1')->where('status', '!=', 'D2')->orderBy('date_expiry')->orderBy('date')->get();
        $view = "verifierrept";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    //--------------------------------------------------show ot approval list--------------------------------------------------
    public function approval(Request $req)
    {
        $otlist = Overtime::where('approver_id', $req->user()->id)
        ->where(function ($q) {
            $q->where('status', 'PV')->orWhere('status', 'PA');
        })
        ->orderBy('submitted_date','asc')
        ->orderBy('date','asc')
        ->get();
        $view = "approver";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    //--------------------------------------------------show ot approval report list--------------------------------------------------
    public function approvalrept(Request $req)
    {
        $otlist = Overtime::where('approver_id', $req->user()->id)->where('status', '!=', 'D1')->where('status', '!=', 'D2')->orderBy('date_expiry')->orderBy('date')->get();
        $view = "approverrept";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    //--------------------------------------------------ot admin list set session--------------------------------------------------
    public function admin(Request $req)
    {
        if ($req->session()->get('otlist')==null) {
            $otlist = [];
        } else {
            $otlist = $req->session()->get('otlist');
        }
        $view = "admin";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    //--------------------------------------------------show ot admin view--------------------------------------------------
    public function adminview()
    {
        return redirect(route('ot.admin', [], false));
    }

    //--------------------------------------------------searc ot admin--------------------------------------------------
    public function adminsearch(Request $req)
    {
        $otlist = Overtime::query();
        if ($req->searchcomp!="") {
            $onecomp = explode(", ", $req->searchcomp);
            // foreach($onecomp as $one){
            //     if($one!=""){
            //         $otlist = $otlist->orWhere('company_id', 'LIKE', '%' .$one. '%');
            //     }
            // }
            $otlist = $otlist->whereIn('company_id', $onecomp);
            // whereIn('id', array(1, 2, 3))->get();
        }
        if ($req->searchpersno!="") {
            $onecomp = explode(", ", $req->searchpersno);
            // foreach($onecomp as $one){
            //     if($one!=""){
            //         $otlist = $otlist->orWhere('user_id', 'LIKE', '%' .$one. '%');
            //     }
            // }
            $otlist = $otlist->whereIn('user_id', $onecomp);
        }
        if ($req->searchpersarea!="") {
            $onecomp = explode(", ", $req->searchpersarea);
            // foreach($onecomp as $one){
            //     if($one!=""){
            //         $otlist = $otlist->orWhere('persarea', 'LIKE', '%' .$one. '%');
            //     }
            // }
            $otlist = $otlist->whereIn('persarea', $onecomp);
        }
        if ($req->searchperssarea!="") {
            $onecomp = explode(", ", $req->searchperssarea);
            // foreach($onecomp as $one){
            //     if($one!=""){
            //         $otlist = $otlist->orWhere('perssubarea', 'LIKE', '%' .$one. '%');
            //     }
            // }
            $otlist = $otlist->whereIn('perssubarea', $onecomp);
        }
        if (($req->searchdate1!="")&&($req->searchdate2!="")) {
            $add = date('Y-m-d', strtotime($req->searchdate2. ' +1 day'));
            // $otlist = $otlist->where('submitted_date', '>=', $req->searchdate1.' 00:00:00')->where('submitted_date', '<=', $req->searchdate2.' 00:00:00');
            // $otlist = $otlist->whereBetween('submitted_date', [$req->searchdate1.' 00:00:00', '2020-07-17 00:00:00']);
            // $otlist = $otlist->whereBetween('submitted_date', [$req->searchdate1.' 00:00:00', $req->searchdate2.' 00:00:00']);
            $otlist = $otlist->whereBetween('submitted_date', [$req->searchdate1.' 00:00:00', $add.' 00:00:00']);
        }
        if ($req->searchstatus!="") {
            $onecomp = explode(", ", $req->searchstatus);
            $status = array();
            foreach ($onecomp as $one) {
                if ($one!="") {
                    if ($one=="Pending Verification") {
                        $stat="PV";
                    } elseif ($one=="Pending Approval") {
                        $stat="PA";
                    } elseif ($one=="Approved") {
                        $stat="A";
                    }
                    array_push($status, $stat);
                    // $otlist = $otlist->where('status', $stat);
                }
            }

            // dd($status);
            $otlist = $otlist->whereIn('status', $status);
        }
        if ($req->searchotdate!="") {
            $onecomp = explode(", ", $req->searchotdate);
            // foreach($onecomp as $one){
            //     if($one!=""){
            //         $otlist = $otlist->orWhere('date', $one);
            //     }
            // }
            $otlist = $otlist->whereIn('date', $onecomp);
        }
        // $status = ['A','Q1','Q2','D1','D2'];
        // $otlist = $otlist->where(function($q) {
        //     $q->where('status', '!=', 'Q1')->where('status', '!=', 'Q2')->where('status', '!=', 'D1')->where('status', '!=', 'D2');
        // })->orderBy('date_expiry')->orderBy('date')->get();
        $otlist = $otlist->whereNotIn('status', ['Q1','Q2','D1','D2']);
        $otlist = $otlist->orderBy('date_expiry')->orderBy('date')->get();

        // dd($otlist);
        Session::put(['otlist' => $otlist]);
        return redirect(route('ot.admin', [], false));
    }

    //--------------------------------------------------ot query actions--------------------------------------------------
    public function query(Request $req)
    {
        // if(in_array($req->user()->staff_no, $array = array("B15589"))){
        //     dd($req);
        // }
        if ($req->typef=="verifier") {
            $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', 'PV')->orderBy('date_expiry')->orderBy('date')->get();
        } elseif ($req->typef=="approver") {
            $otlist = Overtime::where('approver_id', $req->user()->id)
            ->where(function ($q) {
                $q->where('status', 'PV')->orWhere('status', 'PA');
            })
            ->get();
        } elseif ($req->typef=="admin") {
            $otlist = $req->session()->get('otlist');
        }
        // dd($otlist);
        $yes = false;
        // dd($req);
        for ($i=0; $i<count($otlist); $i++) {
            try {
                if ($req->inputact[$i]!="") {
                    $reg = Psubarea::where('state_id', $otlist[$i]->name->stateid->id)->first();
                    $expiry = OvertimeExpiry::where('company_id', $otlist[$i]->name->company_id)->where('region', $reg->region)->where('start_date', '<=', $otlist[$i]->date)->where('end_date', '>', $otlist[$i]->date)->first();
                    // dd($expiry);
                    $claim = Overtime::where('id', $req->inputid[$i])->first();
                    $day= UserHelper::CheckDay($req->user()->id, $claim->date);
                    $dt = DayType::where('id', $day[4])->first();
                    $working_minutes = $dt->working_hour*60;
                    $updateclaim = Overtime::find($req->inputid[$i]);
                    if (($updateclaim->status=="PV")&&($updateclaim->verifier_id==null)) {
                        $updateclaim->status=="PA";
                    }

                    //verify
                    if ($req->inputact[$i]=="PA") {
                        // $updateclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));
                        //notification
                        $user = $claim->approver;                       
                        $myot = \App\Overtime::where('id', $req->inputid[$i])->first();
                        $ccuser = \App\User::orWhere('id', $claim->user_id)->orWhere('id', $claim->verifier_id)->get();
                        
                        $cc = $ccuser->pluck('email')->toArray();
                        $uc = $user->pluck('email')->toArray();

                        $checkemail = URHelper::isValidEmail($uc); 
                        $checkemailcc = URHelper::isValidEmail($cc);     
                        if(($checkemail)&&($checkemailcc)){
                            $user->notify(new OTVerified($myot, $cc));
                        }else{
                            $user->notify(new OTVerifiedNoti($myot, $cc));
                        }
                        $user = $claim->name;
                        $user->notify(new OTVerifiedApplicant($myot));
                        $updateclaim->verification_date = date("Y-m-d H:i:s");
                
                        $updateclaim->status=$req->inputact[$i];
                        $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Verified', 'Verified');
                    // dd($updateclaim->status);
                //approved
                    } elseif ($req->inputact[$i]=="A") {
                        $user = $claim->name;
                        //notification
                        $myot = \App\Overtime::where('id', $claim->id)->first();
                        $ccuser = \App\User::orWhere('id', $claim->approver_id)->get();
                        // dd($myot);
                        $cc = $ccuser->pluck('email')->toArray();
                        $uc = $user->pluck('email')->toArray();
                        $checkemail = URHelper::isValidEmail($uc); 
                        $checkemailcc = URHelper::isValidEmail($cc);     
                        if(($checkemail)&&($checkemailcc)){
                            $user->notify(new OTApproved($myot, $cc));
                        }else{
                            $user->notify(new OTApprovedNoti($myot, $cc));
                        }
                        $updateclaim->approved_date = date("Y-m-d H:i:s");
                
                        $updateclaim->status=$req->inputact[$i];
                        $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Approved', 'Approved');
                    //queried
                    } elseif ($req->inputact[$i]=="Q2") {
                        $updatemonth = OvertimeMonth::find($updateclaim->month_id);

                        $time = ($updateclaim->total_hour*60)+$updateclaim->total_minute;                        
                        $totaltime = (($updatemonth->total_hour*60)+$updatemonth->total_minute) - $time;

                        if(($updateclaim->day_type_code=="N")||($updateclaim->day_type_code=="O")){
                            
                        }else{
                            if($totaltime >= $working_minutes){
                                $totaltime = $totaltime - $working_minutes;
                            }
                        }

                        // $time = ($updateclaim->total_hour*60)+$updateclaim->total_minute;
                        // if($time >= $working_minutes){
                        //     $time = $time - $working_minutes;
                        // }
                        // $totaltime = (($updatemonth->total_hour*60)+$updatemonth->total_minute) - $time;
                        // $updatemonth->total_hour = (int)($totaltime/60);
                        // $updatemonth->total_minute = ($totaltime%60);
                        // $updatemonth->save();
                        // 20201009 add to sync updOTMonth table
                        $updOTMonth = UserHelper::updOtMonthTotalHourMinute($claim->id);

                        $updateclaim->date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+1 months", strtotime(date("Y-m-d")))))));
                        $updateclaim->queried_date = date("Y-m-d H:i:s");
                        // dd($updatemonth->total_hour);
                        // dd($req);
                        $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Queried', 'Queried with message: "'.$req->inputrem[$i].'"');
                        // $updateclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));
                    
                        //notification
                        $user = $claim->name;
                        // $myot = \App\Overtime::where('id', $claim->id)->first();
                        // dd($myot);
                        if ($claim->status=="PA") {
                            // standardkan semua link ke email guna yg ni supaya dia 'mark as read'
                            $ccuser = \App\User::orWhere('id', $claim->approver_id)->get();
                            $cc = $ccuser->pluck('email')->toArray();
                            $uc = $user->pluck('email')->toArray();
                            $checkemail = URHelper::isValidEmail($uc); 
                            $checkemailcc = URHelper::isValidEmail($cc);  
                            if(($checkemail)&&($checkemailcc)){
                                $user->notify(new OTQueryApprove($claim, $cc));
                            }else{
                                $user->notify(new OTQueryApproveNoti($claim, $cc));
                            }
                            if ($claim->verifier_id!=null) {
                                $user->notify(new OTQueryApproverVerify($claim));
                            }
                        } else {
                            $ccuser = \App\User::orWhere('id', $claim->verifier_id)->orWhere('id', $claim->approver_id)->get();
                            $cc = $ccuser->pluck('email')->toArray();
                            $uc = $user->pluck('email')->toArray();

                            $checkemail = URHelper::isValidEmail($uc); 
                            $checkemailcc = URHelper::isValidEmail($cc);     
                            if(($checkemail)&&($checkemailcc)){
                                $user->notify(new OTQueryVerify($claim, $cc));
                            }else{
                                $user->notify(new OTQueryVerifyNoti($claim, $cc));
                            }
                        }
                        $updateclaim->status=$req->inputact[$i];
                    // dd($updateclaim);
                    } elseif ($req->inputact[$i]=="Assign") {
                        $updateclaim->verifier_id=$req->inputver[$i];
                        $updateclaim->status="PV";
                        $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Assigned Verifier', 'Assigned Verifier with message: "'.$req->inputrem[$i].'"');
                        // dd($execute);
                    } elseif ($req->inputact[$i]=="Change") {
                        $updateclaim->approver_id=$req->inputapp[$i];
                        $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Changed Approver', 'Changer Approver with message: "'.$req->inputrem[$i].'"');
                    } elseif ($req->inputact[$i]=="Remove") {
                        $updateclaim->status="PA";
                        $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Removed Verifier', 'Removed Verifier');
                    }
                    if ($expiry) {
                        if ($expiry->status == "ACTIVE") {
                            if ((($expiry->based_date == "Submit to Approver Date")&&($updateclaim->status == 'PA'))||(($expiry->based_date == "Query Date")&&($updateclaim->status == 'Q2'))) {
                                $draftclaim->date_expiry = date('Y-m-d', strtotime("+".$expiry->noofmonth." months"));
                            }
                        }
                    }
                    // dd($req->inputaction[$i]);
                    $updateclaim->save();
                    $yes = true;
                }
            }
            catch(Exception $e){
                //report($e);
                error_log($e->getMessage());
                //report($e);
               // return false;
            }
        }
        // dd($req->inputaction);
        // return redirect(route('ot.approval',[],false));
        if ($yes) {
            if ($req->typef=="verifier") {
                return redirect(route('ot.verify', [], false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your pending overtime claim has been successfully submitted.",
                    'feedback_title' => "Successfully Submitted"
                ]);
            } elseif ($req->typef=="approver") {
                return redirect(route('ot.approval', [], false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your pending overtime claim has been successfully submitted.",
                    'feedback_title' => "Successfully Submitted"
                ]);
            } elseif ($req->typef=="admin") {
                return redirect(route('ot.adminsearch', [], false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your pending overtime claim has been successfully submitted.",
                    'feedback_title' => "Successfully Submitted"
                ]);
            }
        } else {
            return redirect(route('ot.approval', [], false))->with([
                'feedback' => true,
                    'feedback_text' => "Ohh man! The code is breakoff. Kindly drop us a postcard, we will roger you ASAP. xoxo!",
                    'feedback_title' => "Not Successfully Submitted"
            ]);
        }
    }

    //--------------------------------------------------search order no--------------------------------------------------
    public function searchorder(Request $req)
    {
        // dd($req->type);
        $arr = [];
        if ($req->type=="project") {
            $no = Project::where("project_no", 'LIKE', '%'.$req->order. '%')->groupBy("project_no")->get();
            foreach ($no as $o) {
                array_push($arr, [
                    'id'=>$o->project_no,
                    'descr'=>$o->descr,
                    'type'=>$o->type,
                    'costc'=>$o->cost_center,
                    'comp'=>$o->company_code,
                ]);
            }
        } elseif ($req->type=="internal") {
            $no = InternalOrder::where('id', 'LIKE', '%'.$req->order. '%')->get();
            foreach ($no as $o) {
                array_push($arr, [
                    'id'=>$o->id,
                    'descr'=>$o->descr,
                    'type'=>$o->order_type,
                    'costc'=>$o->cost_center,
                    'comp'=>$o->company_code,
                ]);
            }
        } else {
            $no = MaintenanceOrder::where("id", 'LIKE', '%'.$req->order. '%')->get();
            foreach ($no as $o) {
                array_push($arr, [
                    'id'=>$o->id,
                    'descr'=>$o->descr,
                    'type'=>$o->type,
                    'costc'=>$o->cost_center,
                    'comp'=>$o->company_code,
                ]);
            }
        }
        
        // dd($no);
        return $arr;
    }
    //--------------------------------------------------search verifier--------------------------------------------------
    public function search(Request $req)
    {
        $date = date('Y-m-d');
        $ot = Overtime::where("id", $req->otid)->first();

        // dd($ot);
        $costc = $ot->costcenter;
        $approver = $ot->approver_id;
        $verifier = $ot->verifier_id;
        if ($ot->other_costcenter!=null) {
            $costc = $ot->other_costcenter;
        }
        if ($req->type=="normal") {
            // $staff = UserRecord::where('name', 'LIKE', '%' .$req->name. '%')->where("costcentr", $costc)->where('id', '!=', $approver)->where('id', '!=', $verifier)->where('upd_sap', '<=', $date)->orderBy('name', 'ASC')->get();
        $staff = UserRecord::where('name', 'LIKE', '%' .$req->name. '%')->where('name', '!=', $req->user()->name)->where('upd_sap','<=',$date)->orderBy('name', 'ASC')->get();
        } else {
            $staff = UserRecord::query();
            if ($req->name!="") {
                $staff = $staff->orWhere('name', 'LIKE', '%' .$req->name. '%');
            }
            if ($req->staffno!="") {
                $staff = $staff->orWhere('staffno', 'LIKE', '%' .$req->staffno. '%');
            }
            // if($req->email!=""){
            //     $staff = $staff->orWhere('email', 'LIKE', '%' .$req->email. '%');
            // }
            if ($req->persno!="") {
                $staff = $staff->orWhere('user_id', $req->persno);
            }
            // if($req->mobile!=""){
            //     $staff = $staff->orWhere('name', 'LIKE', '%' .$req->mobile. '%');
            // }
            // if($req->office!=""){
            //     $staff = $staff->orWhere('name', 'LIKE', '%' .$req->office. '%');
            // }
            $staff = $staff->where('id', '!=', $approver)->where('id', '!=', $verifier)->where('upd_sap', '<=', $date)->orderBy('name', 'ASC')->get();
            // $staff = $staff->where("costcentr", $costc)->where('id', '!=', $approver)->where('id', '!=', $verifier)->where('upd_sap', '<=', $date)->orderBy('name', 'ASC')->get();
        }
        $arr = [];
        foreach ($staff as $s) {
            array_push($arr, [
                'name'=>$s->name,
                'persno'=>sprintf('%08d', $s->user_id),
                'persnoo'=> $s->user_id,
                'staffno'=>$s->staffno,
                'companycode'=>$s->companyid->company_descr,
                'costcenter'=>$s->costcentr,
                'persarea'=>$s->persarea,
                'empsubgroup'=>$s->empsgroup,
                'email'=>$s->email,
                'mobile'=>$s->name,
            ]);
        }
        return $arr;
        // return $date;
    }

    //--------------------------------------------------get verifier detail--------------------------------------------------
    public function getverifier(Request $req)
    {
        $date = date('Y-m-d');
        $staff = UserRecord::where('user_id', $req->id)->where('upd_sap', '<=', $date)->first();

        return ['name'=>$staff->name,
                'persno'=>sprintf('%08d', $staff->user_id),
                'staffno'=>$staff->staffno,
                'companycode'=>$staff->companyid->company_descr,
                'costcenter'=>$staff->costcentr,
                'persarea'=>$staff->persarea,
                'empsubgroup'=>$staff->empsgroup,
                'email'=>$staff->email,
                'mobile'=>$staff->name];
    }

    public static function getQueryAmount()
    {
        // $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', 'PV')->orWhere('approver_id', $req->user()->id)->where('status', 'PA')->orderBy('date_expiry')->orderBy('date')->get();
        // $count =  count($otlist);
        // return 5;
    }
}
