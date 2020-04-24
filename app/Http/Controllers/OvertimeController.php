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
use App\VerifierGroup;
use App\VerifierGroupMember;
use App\Psubarea;
use App\DayType;
use App\Costcenter;
use App\Project;
use App\InternalOrder;
use App\MaintenanceOrder;
use App\OtIndicator;


use App\Notifications\OTSubmitted;
use App\Notifications\OTVerified;
use App\Notifications\OTQueryVerify;
use App\Notifications\OTQueryApprove;
// use App\Notifications\OTSubmitted;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OvertimeController extends Controller{
    //--------------------------------------------------show overtime list--------------------------------------------------
    public function list(Request $req){
        $otlist = Overtime::where('user_id', $req->user()->id)->orderBy('status')->orderBy('date_expiry')->orderBy('date')->get();
        return view('staff.overtime', ['otlist' => $otlist]);
    }

    //--------------------------------------------------show overtime application form--------------------------------------------------
     public function form(Request $req){
        $region = URHelper::getRegion($req->user()->perssubarea);
        $costc = null;
        $type = null;
        $compn = null;
        $orderno = null;
        $orderlist = null;
        $networkn = null;
        $appr = null;
        $data = null;

        //if claim exist
        if($req->session()->get('claim')!=null){
            $day = UserHelper::CheckDay($req->user()->id, $req->session()->get('claim')->date);
            $eligiblehour = OvertimeEligibility::where('company_id', $req->user()->company_id)->where('region', $region->region)->where('start_date','<=', $req->session()->get('claim')->date)->where('end_date','>', $req->session()->get('claim')->date)->first();
            
            //if charge type is other cost center
            if($req->session()->get('claim')->charge_type=="Other Cost Center"){
                $compn = Costcenter::groupBy('company_id')->get();
                $costc = Costcenter::where('company_id', $req->session()->get('claim')->company_id)->get(); //get cost center list
                if(count($costc)==0){
                    $costc = null;
                }
                $appr = UserRecord::where('upd_sap','<=',date('Y-m-d'))->where('company_id', $req->session()->get('claim')->company_id)->where('costcentr', $req->session()->get('claim')->other_costcenter)->where('user_id', '!=', $req->user()->id)->get(); //get approcer list
                if(count($appr)==0){
                    $appr = null;
                }

            //if charge type is project
            }else if($req->session()->get('claim')->charge_type=="Project"){
                $orderlist= Project::groupby('project_no')->get();
                if($req->session()->get('claim')->project_no!=null){
                    $data = Project::where('project_no',$req->session()->get('claim')->project_no)->first();
                    $networkn= Project::where('project_no',$req->session()->get('claim')->project_no)->get(); //get network no list
                    if(count($networkn)==0){
                        $networkn = null;
                    }
                    if($req->session()->get('claim')->network_act_no!=null){
                        $data = Project::where('project_no',$req->session()->get('claim')->project_no)->where('network_act_no',$req->session()->get('claim')->network_act_no)->first();
                    }
                }

            //if charge type is internal order
            }else if($req->session()->get('claim')->charge_type=="Internal Order"){
                $orderno= InternalOrder::all();
                if($req->session()->get('claim')->order_no!=null){
                    $data=InternalOrder::where('id',$req->session()->get('claim')->order_no)->first();
                    if($data!=null){
                        if($data->cost_center==""){
                            $costc = Costcenter::where('company_id', $data->company_code)->get();
                            // dd($data->company_code);
                            if(count($costc)==0){
                                $costc = null;
                            }
                        }
                        // dd($req->session()->get('claim')->other_costcenter);
                        $appr = UserRecord::where('upd_sap','<=',date('Y-m-d'))->where('company_id', $req->session()->get('claim')->company_id)->where('costcentr', $req->session()->get('claim')->other_costcenter)->where('user_id', '!=', $req->user()->id)->get();
                        // dd($appr);
                        if(count($appr)==0){
                            $appr = null;
                        }
                    }
                }

            //if charge type is maintenance order
            }else if($req->session()->get('claim')->charge_type=="Maintenance Order"){
                $orderno= MaintenanceOrder::all();
                if($req->session()->get('claim')->order_no!=null){
                    $data = MaintenanceOrder::where('id',$req->session()->get('claim')->order_no)->first();
                    // dd($data);
                }
            }

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
                                         'appr' => $appr]);

        //if new claim after choose date                                        
        }else if($req->session()->get('draft')!=null){
            $draft = $req->session()->get('draft');
            $day = UserHelper::CheckDay($req->user()->id, date('Y-m-d', strtotime($draft[4])));
            $eligiblehour = OvertimeEligibility::where('company_id', $req->user()->company_id)->where('region', $region->region)->where('start_date','<=', $draft[4])->where('end_date','>', $draft[4])->first();
            // dd($req->session()->get('draft'));
            return view('staff.otform', ['draft' => $req->session()->get('draft'), 'day' => $day, 'eligiblehour' => $eligiblehour->hourpermonth, 'costc' => $costc]);
            
        //if apply new claim
        }else{  
            return view('staff.otform', []);
        }
    }

    //--------------------------------------------------show overtime form when click update--------------------------------------------------
    public function update(Request $req){
        $claim = Overtime::where('id', $req->inputid)->first();
        Session::put(['draft' => [], 'claim' => $claim]);
        return redirect(route('ot.form',[],false));
    }

    //--------------------------------------------------show overtime form when click view--------------------------------------------------
    public function detail(Request $req){
        $claim = Overtime::where('id', $req->detailid)->first();
        Session::put(['draft' => [], 'claim' => $claim]);
        Session::put(['back' => $req->type]);
        return view('staff.otdetail', ['claim' => $req->session()->get('claim')]);
    }

    //--------------------------------------------------delete overtime claim--------------------------------------------------
    public function remove(Request $req){
        $claim = Overtime::where('id', $req->delid)->first();
        $updatemonth = OvertimeMonth::find($claim->month_id);
        $totaltime = (($updatemonth->hour*60)+$updatemonth->minute)-((($claim->total_hour)*60)+$claim->total_minute);
        $updatemonth->hour = (int)($totaltime/60);
        $updatemonth->minute = ($totaltime%60);
        $updatemonth->save();

        //delete all relate punch in data
        if($claim->punch_id!=null){
            $delpunch = StaffPunch::whereDate('punch_in_time', $claim->date)->get();
            foreach($delpunch as $delpunches){
                $delpunches->apply_ot = null;
                $delpunches->save();
            }
        }
        OvertimeLog::where('ot_id',$req->delid)->delete();
        OvertimeDetail::where('ot_id',$req->delid)->delete();
        Overtime::find($req->delid)->delete();
        Session::put(['draft' => [], 'claim' => []]);
        return redirect(route('ot.list',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Your claim application ".$claim->refno." has successfully deleted.",
            'feedback_title' => "Successfully Deleted"
        ]);
    }

    //--------------------------------------------------submit overtime claim from overtime list--------------------------------------------------
    public function submit(Request $req){
        $cansubmit = true;
        $eligibitywarning = false;
        $id = explode(" ", $req->submitid);
        $region = URHelper::getRegion($req->user()->perssubarea);
       
        //checking on selected ot
        for($i = 0; $i<count($id); $i++){
            $claim = Overtime::find($id[$i]);
            $eligibility = OtIndicator::where('user_id', $req->user()->id)->where('upd_sap','<=',date('Y-m-d',strtotime($claim->date)))->first();
            //check if exceeds eligible hours
            if($eligibility){
                if($eligibility->ot_hour_exception!="Y"){
                    $eligiblehour = URHelper::getUserEligibity($req->user()->company_id, $region->region, $claim->date);
                    $month = OvertimeMonth::where('id', $claim->month_id)->first();
                    $totalsubmit = ($month->hour*60+$month->minute) + ($claim->total_hour*60+$claim->total_minute);
                    //if exceed, disable submition
                    if($totalsubmit>($eligiblehour->hourpermonth*60)){
                        $eligibitywarning = true;
                    }
                }
            }

            //check if checked ot date have leave
            $claim = Overtime::find($id[$i]);
            $leave = UserHelper::CheckLeave($req->user()->id, $claim->date);
            if($leave){
                if($leave == "INS"){
                    $cansubmit = false;
                }
            }
        }

        //check if can submit or not
        if($cansubmit){
            for($i = 0; $i<count($id); $i++){
                $updateclaim = Overtime::find($id[$i]);
                $updateclaim->approver_id = $req->user()->reptto;
                $updateclaim->submitted_date = date("Y-m-d H:i:s");
                $execute = UserHelper::LogOT($id[$i], $req->user()->id, "Submitted", "Submitted ".$updateclaim->refno);
                //check if ot have verifier
                if($updateclaim->verifier_id==null){
                    $updateclaim->status = 'PA';
                }else{
                    $updateclaim->status = 'PV';
                }
                $expiry = OvertimeExpiry::where('company_id', $req->user()->company_id)->where('region', $region->region)->where('start_date','<=', $claim->date)->where('end_date','>', $claim->date)->first();
                if($expiry->status == "ACTIVE"){
                    if((($expiry->based_date == "Submit to Approver Date")&&($updateclaim->status == 'PA'))||(($expiry->based_date == "Submit to Verifier Date")&&($updateclaim->status == 'PV'))){
                        $draftclaim->date_expiry = date('Y-m-d', strtotime("+".$expiry->noofmonth." months"));
                    }
                }
                $updatemonth = OvertimeMonth::find($updateclaim->month_id);
                $totalsubmit = (($updatemonth->total_hour*60)+$updatemonth->total_minute)+(($updateclaim->total_hour*60)+$updateclaim->total_minute);
                $updatemonth->total_hour = (int)($totalsubmit/60);
                $updatemonth->total_minute = $totalsubmit%60;
                $updatemonth->save();
                $updateclaim->save();
                //send notification to verifier/approver

                $claim = Overtime::where('id', $id[$i])->first();
                $user = $claim->verifier;
                $myot = \App\Overtime::where('verifier_id', $user->id)->first();
                if($claim->verifier_id==NULL){
                    $user = $claim->approver;
                    $myot = \App\Overtime::where('approver_id', $user->id)->first();
                }
                $user->notify(new OTSubmitted($myot));
            }

            // check if exceeds eligibility hour
            if($eligibitywarning){
                return redirect(route('ot.list',[],false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your submitted claim time has exceeded eligible claim time.",
                    'feedback_title' => "Warning!"
                ]);
            }else{
                return redirect(route('ot.list',[],false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your overtime claim has successfully submitted.",
                    'feedback_title' => "Successfully Submitted"
                ]);
            }
        }else{
            return redirect(route('ot.list',[],false))->with([
                'feedback' => true,
                'feedback_text' => "You are on leave for selected overtime claim.",
                'feedback_title' => "Submission Failed!"
            ]);
        }
    }

    //--------------------------------------------------clear session when click on apply new overtime--------------------------------------------------
    public function formnew(Request $req){
        Session::put(['draft' => [], 'claim' => []]);
        return redirect(route('ot.form',[],false));
    }

    //--------------------------------------------------when select overtime date--------------------------------------------------
    public function formdate(Request $req){
        $gm = UserHelper::CheckGM(date("Y-m-d"), $req->inputdate);
        $staffr = URHelper::getUserRecordByDate($req->user()->id, $req->inputdate);
        // $staffr = UserRecord::where('user_id', $req->user()->id)->where('upd_sap','<=',date('Y-m-d'))->first();
        $region = URHelper::getRegion($req->user()->perssubarea);
        $day= UserHelper::CheckDay($req->user()->id, $req->inputdate);
        $day_type=$day[2];
        Session::put(['draft' => []]);
        $claim = Overtime::where('user_id', $req->user()->id)->where('date', $req->inputdate)->first();

        //check if selected ot date have data or not (if not exist exist)
        if(empty($claim)){ 
            $claimdate = $req->inputdate;
            $claimmonth = date("m", strtotime($claimdate));
            $claimyear = date("y", strtotime($claimdate));
            $claimday = date("l", strtotime($claimdate));
            $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->first();

            //check if selected ot date's month have data or not, if empty create ot month
            if(empty($claimtime)){ 
                $newmonth = new OvertimeMonth;
                $newmonth->user_id = $req->user()->id;
                $newmonth->year = $claimyear;
                $newmonth->month = $claimmonth;
                $newmonth->save();
                $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->first();
            }
            $punch = OvertimePunch::where('user_id', $req->user()->id)->where('date', $req->inputdate)->get();

            //check if selected ot date's have punch in data or not, if empty create ot month
            if(count($punch)!=0){
                $totalhour = 0;
                $totalminute = 0;
                $wage = OvertimeFormula::where('company_id', $req->user()->company_id)->where('region', $region->region)->where('start_date','<=', $claimdate)->where('end_date','>', $claimdate)->first();   //temp
                // $expiry = OvertimeExpiry::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', $claimdate)->where('end_date','>', $claimdate)->first();
                $draftclaim = new Overtime;
                $draftclaim->refno = "OT".date("Ymd", strtotime($claimdate))."-".sprintf("%08d", $req->user()->id);
                $draftclaim->user_id = $req->user()->id;
                $draftclaim->month_id = $claimtime->id;
                $draftclaim->date = $req->inputdate;
                $draftclaim->date_created = date("Y-m-d");
                // if($expiry->status == "ACTIVE"){
                //     if($expiry->based_date == "Request Date"){
                //         $draftclaim->date_expiry = date('Y-m-d', strtotime("+".$expiry->noofmonth." months"));
                //     }elseif($expiry->based_date == "Overtime Date"){
                //         $draftclaim->date_expiry = date('Y-m-d', strtotime("+".$expiry->noofmonth." months", strtotime($claimdate)));
                //     }
                // }

                //check if ot is more than 3 months from system date
                if($gm){ //if more than 3 months
                    $draftclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($claimdate)));
                    $draftclaim->date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+1 months", strtotime(date("Y-m-d")))))));

                }else{
                    $draftclaim->approver_id = $req->user()->reptto;

                    //check if user have default verifier or not
                    $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                    if($vgm){
                        $vg = VerifierGroup::where('id', $vgm->id)->first();
                        $draftclaim->verifier_id =  $vg->verifier_id;
                    }
                    $draftclaim->date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+3 months", strtotime($req->inputdate))))));
                }
                $draftclaim->state_id =  $req->user()->state_id;
                $draftclaim->daytype_id =  $day[4];
                $draftclaim->profile_id =  $staffr->id;
                $draftclaim->company_id =  $staffr->company_id;
                $draftclaim->persarea =  $staffr->persarea;
                $draftclaim->perssubarea =  $staffr->perssubarea;
                $draftclaim->punch_id =  $punch[0]->punch_id;
                $draftclaim->region =  $region->region;
                $draftclaim->charge_type =  "Own Cost Center";
                $draftclaim->costcenter =  $staffr->costcentr;
                $draftclaim->wage_type =  null; //temp

                // $draftclaim->wage_type =  $wage->legacy_codes; //temp
                $userrecid = URHelper::getUserRecordByDate($req->user()->persno, date('Y-m-d', strtotime($claimdate)));
                $draftclaim->user_records_id =  $userrecid->id;
                $draftclaim->save();
                $claim = Overtime::where('user_id', $req->user()->id)->where('date', $req->inputdate)->first();

                //register user clock in time if have clock in data;
                foreach($punch as $punches){
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
                    if($staffr->ot_salary_exception=="Y"){
                        $salarycap = URHelper::getUserEligibity($staffr->company_id, $region->region, $claim->date);
                        $salary = $salarycap->salary_cap;
                    }
                    $pay = UserHelper::CalOT($salary, $punches->hour, $punches->minute);
                    $newclaim->amount = $pay;
                    $newclaim->justification = "";
                    $newclaim->in_latitude = $punches->in_latitude;
                    $newclaim->in_longitude = $punches->in_longitude;
                    $newclaim->out_latitude = $punches->out_latitude;
                    $newclaim->out_longitude = $punches->out_longitude;
                    $newclaim->save();
                    $staffpunch->save();
                }
                $execute = UserHelper::LogOT($claim->id, $req->user()->id, "Created draft", "Created draft for ".$claim->refno);
                $claim = Overtime::where('user_id', $req->user()->id)->where('date', $req->inputdate)->first();
                Session::put(['draft' => []]);
            }

            //if dont have OT Punch
            else{
                $expiry = URHelper::getUserExpiry($staffr->company_id, $region->region, $claimdate);
                $dt = DayType::where('id', $day_type)->first();
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
                //check if ot is more than 3 month from system date
                if($gm){
                    $gmid = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($claimdate)));
                    $approve = User::where('id', $gmid)->first();
                    $verify = User::where('id', $req->user()->reptto)->first();
                    $verifyn = $verify->name;
                    $date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+1 months", strtotime(date("Y-m-d")))))));
                }else{
                    $approve = User::where('id', $req->user()->reptto)->first();

                    //check if user have default verifier or not
                    $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                    if($vgm){
                        $vg = VerifierGroup::where('id', $vgm->id)->first();
                        $verify =  $vg->verifier_id;
                        if($verify){
                            if($verify!=""){
                                $verifyn = $vg->name->name;
                            }
                        }
                    }
                    $date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+3 months", strtotime($req->inputdate))))));
                }
                //get verifier name
                // if($verify!=null){
                //     if($verify!=""){
                //         $verifyn = $vg->name->name;
                //     }
                // }
                $state = UserRecord::where('user_id',$req->user()->persno)->where('upd_sap','<=',$claimdate)->first();
                $refno = "OT".date("Ymd", strtotime($claimdate))."-".sprintf("%08d", $req->user()->id);
                $draft = array($refno,                          //[0] - refno
                                $date_expiry,                   //[1] - expiry
                                date("Y-m-d H:i:s"),            //[2] - datetime created
                                $claimtime,                     //[3] - month
                                $req->inputdate,                //[4] - date
                                $req->user()->name,             //[5] - user name
                                $state->state_id,               //[6] - stateid
                                $state->statet->state_descr,    //[7] - statedescr
                                $day_type,                      //[8] - day type
                                $verifyn,                       //[9] - verifier name
                                $approve->name,                 //[10] - approver name
                                $staffr->costcentr);            //[11] - cost center    
                Session::put(['draft' => $draft]);
            }
        }else{
            Session::put(['draft' => []]);
        }
        Session::put(['claim' => $claim]);
        return redirect(route('ot.form',[],false));
    }

    //--------------------------------------------------add new time/auto-save form/submit form--------------------------------------------------
    public function formsubmit(Request $req){
        $status = true; //claim status
        $region = URHelper::getRegion($req->user()->perssubarea);
        // dd($req->inputdates);
        $staffr = URHelper::getUserRecordByDate($req->user()->id, $req->inputdates);

        //check for existing claim
        if($req->inputid==""){  //if not
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
            $draftclaim->amount = 0;
            $day= UserHelper::CheckDay($req->user()->id, $req->session()->get('draft')[4]);
            $day_type=$day[2];
            
            //check if ot date is more than 3 months from system date
            if($gm){ //if more than 3 months
                $draftclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime(($req->session()->get('draft'))[2])));
                $draftclaim->verifier_id =  $req->user()->reptto;
            }else{
                $draftclaim->approver_id = $req->user()->reptto;
                $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                if($vgm){
                    $vg = VerifierGroup::where('id', $vgm->id)->first();
                    $draftclaim->verifier_id =  $vg->verifier_id;
                }
            }
            $draftclaim->daytype_id =  $day[4];
            $draftclaim->state_id =  ($req->session()->get('draft'))[6];
            $draftclaim->company_id =  $staffr->company_id;
            $draftclaim->persarea =  $staffr->persarea;
            $draftclaim->perssubarea =  $staffr->perssubarea;
            $draftclaim->region =  $region->region;
            $draftclaim->costcenter =  $staffr->costcentr;
            // $draftclaim->wage_type =  $wage->legacy_codes; //temp
            $userrecid = URHelper::getUserRecordByDate($req->user()->persno, date('Y-m-d', strtotime(($req->session()->get('draft'))[4])));
            $salexecpt = URHelper::getUserRecordByDate($req->user()->persno, date('Y-m-d', strtotime(($req->session()->get('draft'))[2])));
            $draftclaim->user_records_id =  $userrecid->id;
            $draftclaim->sal_exception =  $salexecpt->ot_salary_exception;
            $draftclaim->status = 'D1';
            $draftclaim->save();
            $claim = Overtime::where('user_id', $req->user()->id)->where('date', ($req->session()->get('draft'))[4])->first();
            $id = $claim->id;
            $execute = UserHelper::LogOT($claim->id, $req->user()->id, "Created draft", "Created draft for ".$claim->refno);
            Session::put(['draft' => []]);
        }else{
            $claim = Overtime::where('id', $req->inputid)->first();
            $id = $claim->id;
            $gm = UserHelper::CheckGM($claim->date_created, $claim->date);
        }

        //check user ot salary exception
        $salary = $staffr->salary;
        if($staffr->ot_salary_exception=="Y"){
            $salarycap = URHelper::getUserEligibity($staffr->company_id, $region->region, $claim->date);
            $salary = $salarycap->salary_cap;
        }

        //if adding new time
        if($req->formtype=="add"){
            $dif = (strtotime($req->inputendnew) - strtotime($req->inputstartnew))/60;
            $hour = (int) ($dif/60);
            $minute = $dif%60;
            $pay = UserHelper::CalOT($salary, $hour, $minute);
            $newdetail = new OvertimeDetail;
            $newdetail->ot_id = $claim->id;
            $newdetail->start_time = $claim->date." ".$req->inputstartnew.":00";
            $newdetail->end_time = $claim->date." ".$req->inputendnew.":00";
            $newdetail->hour = $hour;
            $newdetail->minute = $minute;
            $newdetail->checked = "Y";
            $newdetail->amount = $pay;
            $newdetail->justification = $req->inputremarknew;
            $newdetail->is_manual = "X";
            $updatemonth = OvertimeMonth::find($claim->month_id);
            $totaltime = (($updatemonth->hour*60)+$updatemonth->minute)+(($hour*60)+$minute);
            $updatemonth->hour = (int)($totaltime/60);
            $updatemonth->minute = ($totaltime%60);
            $updateclaim = Overtime::find($claim->id);
            $totaltime = (($updateclaim->total_hour*60)+$updateclaim->total_minute)+(($hour*60)+$minute);
            $updateclaim->total_hour = (int)($totaltime/60);
            $updateclaim->total_minute = ($totaltime%60);
            $updateclaim->amount = $updateclaim->amount + $pay;
            $newdetail->save();
            $updatemonth->save();
            $updateclaim->save();
        }

        //if (save form/submit form/ delete time/file)
        if(($req->formtype=="save")||($req->formtype=="submit")||($req->formtype=="delete")){
            $claim = Overtime::where('id', $claim->id)->first();
            $claimdetail = OvertimeDetail::where('ot_id', $claim->id)->get();

            //operation for all claim hour details
            for($i=0; $i<count($claimdetail); $i++){

                //check claim hour detail form is complet
                if(($req->inputstart[$i]!="")&&$req->inputend[$i]!=""){
                    $operation = null;

                    //check if draft/query status complete or not (D1/D2/Q1/Q2)
                    if(($req->inputremark[$i]=="")||($req->inputstart[$i]=="")||($req->inputend[$i]=="")){
                        $status = false;
                    }
                    $dif = (strtotime($req->inputend[$i]) - strtotime($req->inputstart[$i]))/60;
                    $hour = (int) ($dif/60);
                    $minute = $dif%60;
                    $pay = UserHelper::CalOT($salary, $hour, $minute);
                    $updatedetail = $claimdetail[$i];
                    $updatedetail->start_time = $claim->date." ".$req->inputstart[$i].":00";
                    $updatedetail->end_time = $claim->date." ".$req->inputend[$i].":00";

                    //check if checkbox changed or not
                    if($updatedetail->checked != $req->inputcheck[$i]){
                        $updatedetail->checked = $req->inputcheck[$i];
                        $operation = $req->inputcheck[$i];
                    }
                    $updatedetail->justification = $req->inputremark[$i];
                    $updatemonth = OvertimeMonth::find($claim->month_id);
                    $updateclaim = Overtime::find($claim->id);

                    //if checkbox changed
                    if($operation=="Y"){
                        $totaltime = (($updatemonth->hour*60)+$updatemonth->minute)+(($hour*60)+$minute);
                        $updatemonth->hour = (int)($totaltime/60);
                        $updatemonth->minute = ($totaltime%60);
                        $totaltime = (($updateclaim->total_hour*60)+$updateclaim->total_minute)+(($hour*60)+$minute);
                        $updateclaim->total_hour = (int)($totaltime/60);
                        $updateclaim->total_minute = ($totaltime%60);
                        $updateclaim->amount = $updateclaim->amount + $pay;
                    }elseif($operation=="N"){
                        $totaltime = (($updatemonth->hour*60)+$updatemonth->minute)-(($hour*60)+$minute);
                        $updatemonth->hour = (int)($totaltime/60);
                        $updatemonth->minute = ($totaltime%60);
                        $totaltime = (($updateclaim->total_hour*60)+$updateclaim->total_minute)-(($hour*60)+$minute);
                        $updateclaim->total_hour = (int)($totaltime/60);
                        $updateclaim->total_minute = ($totaltime%60);
                        $updateclaim->amount = $updateclaim->amount - $pay;
                    }else{  //if checkbox not changed
                        $totaltime = (($updatemonth->hour*60)+$updatemonth->minute)-(($updatedetail->hour*60)+$updatedetail->minute)+(($hour*60)+$minute);
                        $updatemonth->hour = (int)($totaltime/60);
                        $updatemonth->minute = ($totaltime%60);
                        $totaltime = (($updateclaim->total_hour*60)+$updateclaim->total_minute)-(($updatedetail->hour*60)+$updatedetail->minute)+(($hour*60)+$minute);
                        $updateclaim->total_hour = (int)($totaltime/60);
                        $updateclaim->total_minute = ($totaltime%60);
                        $updateclaim->amount = $updateclaim->amount - $updatedetail->amount + $pay;
                    }
                    $updatedetail->checked = $req->inputcheck[$i];
                    $updatedetail->amount = $pay;
                    $updatedetail->hour = $hour;
                    $updatedetail->minute = $minute;
                    $updatedetail->save();
                    $updatemonth->save();
                    $updateclaim->save();
                }
            }
        }
        $claimdetail = OvertimeDetail::where('ot_id', $claim->id)->get();

        //check claim charge type empty or claim hours exist
        if(($req->chargetype=="")||(count($claimdetail)==0)){
            $status = false;
        }

        $updateclaim = Overtime::find($claim->id);
        
        //update claim status
        if($status){
            if($updateclaim->status=="D1"){
                $updateclaim->status = 'D2';
            }elseif($updateclaim->status=="Q1"){
                $updateclaim->status = 'Q2';
            }
        }else{
            if($updateclaim->status=="D2"){
                $updateclaim->status = 'D1';
            }elseif($updateclaim->status=="Q2"){
                $updateclaim->status = 'Q1';
            }
        }

        //if charge type changed
        $resetapprove = false;
        if(($updateclaim->charge_type!=$req->chargetype)){
            if(in_array($req->chargetype, $array = array("Project", "Internal Order", "Maintenance Order", "Other Cost Center"))){
                $updateclaim->company_id = null;
                $updateclaim->other_costcenter = null;
            }else{
                $updateclaim->company_id = $staffr->company_id;
                $updateclaim->other_costcenter = $staffr->costcentr;
            }
            $updateclaim->charge_type = null;
            $updateclaim->order_no = null;
            $updateclaim->project_no = null;
            $updateclaim->project_type = null;
            $updateclaim->network_header = null;
            $updateclaim->network_act_no = null;
            $resetapprove = true;
        }

        // if company code changed
        if(($updateclaim->company_id!=$req->compn)){
            $resetapprove = true;
        }

        // if cost center changed
        if($updateclaim->other_costcenter!=$req->costc){
            $resetapprove = true;
        }

        //reset claim approver and verifier
        if($resetapprove){

            //check if ot is more than 3 month from system date
            if($gm){ //if more than 3 months
                $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                $updateclaim->verifier_id =  $req->user()->reptto;
            }else{
                $updateclaim->approver_id = $req->user()->reptto;

                //check if user have default verifier or not
                $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                if($vgm){
                    $vg = VerifierGroup::where('id', $vgm->id)->first();
                    $updateclaim->verifier_id =  $vg->verifier_id;
                }
            }
        }
        $updateclaim->charge_type = $req->chargetype;
        
        //do this if charge type is project/internal order/maintenace order/other cost center
        if(in_array($req->chargetype, $array = array("Project", "Internal Order", "Maintenance Order", "Other Cost Center"))){
            
            //if charge type internal order/maintenance order
            if(in_array($req->chargetype, $array = array("Internal Order", "Maintenance Order"))){
                $updateclaim->order_no = $req->orderno;
                $updateclaim->company_id = null;
                if($req->orderno!=null){

                    //if internal order
                    if($req->chargetype == "Internal Order"){
                        $data=InternalOrder::where('id', $req->orderno)->first();
                        if($data!=null){
                            if($req->approvern!=null){

                                //check if ot is more than 3 months from system date
                                if($gm){ //if more than 3 months
                                    $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                                    $updateclaim->verifier_id =  $req->approvern;
                                }else{
                                    $updateclaim->approver_id = $req->approvern;
                                    
                                    //check if user have default verifier or not
                                    $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                                    if($vgm){
                                        $vg = VerifierGroup::where('id', $vgm->id)->first();
                                        $updateclaim->verifier_id =  $vg->verifier_id;
                                    }
                                }
                            }

                            //if cost center exist or not
                            if($req->costc!=null){
                                $updateclaim->other_costcenter = $req->costc;
                            }else{
                                $updateclaim->other_costcenter = $data->cost_center;
                            }
                            $updateclaim->company_id = $data->company_code;
                        }

                    //if charge type maintenance order
                    }else{
                        $data=MaintenanceOrder::where('id', $req->orderno)->first();
                        if($data!=null){
                            if($data->approver_id!=""){

                                //check if ot is more than 3 months from system date
                                if($gm){
                                    $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                                    $updateclaim->verifier_id = $data->approver_id;
                                }else{
                                    $updateclaim->approver_id = $data->approver_id;

                                    //check if user have default verifier or not
                                    $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                                    if($vgm){
                                        $vg = VerifierGroup::where('id', $vgm->id)->first();
                                        $updateclaim->verifier_id =  $vg->verifier_id;
                                    }
                                }
                            }
                            $updateclaim->other_costcenter = $data->cost_center;
                            $updateclaim->company_id = $data->company_code;
                        }
                    }
                }

            //if charge type project
            }else if($req->chargetype=="Project"){
                if($req->orderno!=null){
                    $updateclaim->project_no = $req->orderno;
                    $updateclaim->network_header = $req->networkh;
                    $updateclaim->network_act_no = $req->networkn;
                    if($req->networkn!=null){
                        $data = Project::where('project_no', $req->orderno)->where('network_act_no', $req->networkn)->first();
                        if($data!=null){

                            
                            //check if ot is more than 3 months from system date
                            if($gm){ //if more than 3 months
                                $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                                $updateclaim->verifier_id = $data->approver_id;
                            }else{
                                $updateclaim->approver_id = $data->approver_id;
                                $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                                if($vgm){
                                    $vg = VerifierGroup::where('id', $vgm->id)->first();
                                    $updateclaim->verifier_id =  $vg->verifier_id;
                                }
                            }
                            $updateclaim->company_id = $data->company_code;
                        }
                    }
                }

            //if charge type other cost center
            }else if($req->chargetype=="Other Cost Center"){
                $updateclaim->company_id = $req->compn;
                if($req->costc!=null){
                    $updateclaim->other_costcenter = $req->costc;
                }
                if($req->approvern!=null){
                    
                    //check if ot is more than 3 months from system date
                    if($gm){ //if more than 3 months
                        $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                        $updateclaim->verifier_id =  $req->approvern;
                    }else{
                        $updateclaim->approver_id = $req->approvern;
                        $vgm = VerifierGroupMember::where('user_id', $req->user()->id)->first();
                        if($vgm){
                            $vg = VerifierGroup::where('id', $vgm->id)->first();
                            $updateclaim->verifier_id =  $vg->verifier_id;
                        }
                    }
                }
            }
        }
        $updateclaim->save();

        //check if delete time/file
        if(($req->inputfile!="")&&($req->formtype!="delete")){  //if not, upload file if exist
            $file   =   $req->file('inputfile');
            $name = date("ymd", strtotime($updateclaim->date))."-".sprintf("%08d", $req->user()->id)."-".rand(10000,99999)."-".$file->getClientOriginalName();
            $target_path    =   storage_path('/app/public/');
            $store = $file->storeAs('public', $name);
            if($file->getClientOriginalExtension()=="pdf"){
                $imagick = new \Imagick($file.'[0]');
                $newname = str_replace(".pdf","",$name);
                $fileName = $newname . '.jpg';
                $imagick->setImageFormat('jpg');
                $imagick->writeImage($target_path.$fileName);
            }
            $claimfile = new OvertimeFile;
            $claimfile->ot_id = $claim->id;
            $claimfile->filename =  $name;
            if($file->getClientOriginalExtension()=="pdf"){
                $claimfile->thumbnail =  $fileName;
            }else{
                $claimfile->thumbnail =  $name;
            }
            $claimfile->save();

        //if delete time/file
        }elseif($req->formtype=="delete"){ 
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
        foreach($total_hour as $single){
            $total_hours = ($total_hours + $single->hour)*60;
            $total_minutes = $total_minutes + $single->minute;
        }
        $total_minutes = $total_hours+$total_minutes;
        Session::put(['claim' => $claim]);

        //if add new time
        if($req->formtype=="add"){

            //if total ot hours exceed 12 hours
            if($total_minutes>=720){
                return redirect(route('ot.form',[],false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your claim for this date has reached/exceed 12 hours.",
                    'feedback_title' => "Warning"
                ]);
            }else{
                return redirect(route('ot.form',[],false));
            }
        }

        //if auto save form
        if($req->formtype=="save"){

            //if total ot hours exceed 12 hours
            if($total_minutes>=720){
                return redirect(route('ot.form',[],false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your claim for this date has reached/exceed 12 hours.",
                    'feedback_title' => "Warning"
                ]);
            }else{
                return redirect(route('ot.form',[],false));
            }
        }

        //if delete form
        if($req->formtype=="delete"){ 
            return redirect(route('ot.form',[],false))->with([
                'feedback' => true,
                'feedback_text' => "Successfully deleted file.",
                'feedback_title' => "Success"
            ]);
        }
        if($req->formtype=="submit"){ //if submit
            $cansubmit = true;  //can submit ot or not

            //check for leave
            $leave = UserHelper::CheckLeave($req->user()->id, $claim->date);
            if($leave){
                if($leave == "INS"){
                    $cansubmit = false;
                }
            }

            //if not on leave
            if($cansubmit){
                $month = OvertimeMonth::where('id', $claim->month_id)->first();
                $totalsubmit = (($claim->total_hour*60)+$claim->total_minute)+(($month->total_hour*60)+$month->total_minute);
                $updatemonth = OvertimeMonth::find($month->id);
                $updatemonth->total_hour = (int)($totalsubmit/60);
                $updatemonth->total_minute = $totalsubmit%60;
                $updatemonth->save();
                $updateclaim = Overtime::find($claim->id);
                $updateclaim->submitted_date = date("Y-m-d H:i:s");
                $execute = UserHelper::LogOT($claim->id, $req->user()->id, "Submitted", "Submitted ".$updateclaim->refno);
                $expiry = URHelper::getUserExpiry($staffr->company_id, $region->region, $claim->date);
                if($updateclaim->verifier_id==null){
                    $updateclaim->status = 'PA';
                }else{
                    $updateclaim->status = 'PV';
                }
                // if($expiry->status == "ACTIVE"){
                //     if((($expiry->based_date == "Submit to Approver Date")&&($updateclaim->status == 'PA'))||(($expiry->based_date == "Submit to Verifier Date")&&($updateclaim->status == 'PV'))){
                //         $draftclaim->date_expiry = date('Y-m-d', strtotime("+".$expiry->noofmonth." months"));
                //     }
                // }

                $updateclaim->save();
                
                //check eligibity
                $eligibility = URHelper::getUserEligibity($staffr->company_id, $region->region, $claim->date);

                //send notification to verifier/approver
                $claim = Overtime::where('id', $claim->id)->first();
                $user = $claim->verifier;
                $myot = \App\Overtime::where('verifier_id', $user->id)->first();
                if($claim->verifier_id==NULL){
                    $user = $claim->approver;
                    $myot = \App\Overtime::where('approver_id', $user->id)->first();
                }
                $user->notify(new OTSubmitted($myot));

                if($eligibility){

                    //check if eligible for ot hour exception
                    if($eligibility->ot_hour_exception!="Y"){   //if not
                        $reg = Psubarea::where('state_id', $req->user()->state_id)->first();
                        $eligiblehour = OvertimeEligibility::where('company_id', $req->user()->company_id)->where('region', $region->region)->where('start_date','<=', $claim->date)->where('end_date','>', $claim->date)->first();
                        $month = OvertimeMonth::where('id', $claim->month_id)->first();
                        $totalsubmit = (($claim->total_hour*60)+$claim->total_minute)+(($month->total_hour*60)+$month->total_minute);

                        //check if total claim time exceeds eligible ot time to claim
                        if($totalsubmit>($eligiblehour->hourpermonth*60)){
                            return redirect(route('ot.list',[],false))->with([
                                'feedback' => true,
                                'feedback_text' => "Warning! Your overtime claim has exceeded eligible claim hours of ".$eligiblehour->hourpermonth." hours.",
                                'feedback_title' => "Successfully Submitted"
                            ]);
                        }else{
                            return redirect(route('ot.list',[],false))->with([
                                'feedback' => true,
                                'feedback_text' => "Your overtime claim has successfully submitted.",
                                'feedback_title' => "Successfully Submitted"
                            ]);
                        }
                    }else{
                        return redirect(route('ot.list',[],false))->with([
                            'feedback' => true,
                            'feedback_text' => "Your overtime claim has successfully submitted.",
                            'feedback_title' => "Successfully Submitted"
                        ]);
                    }
                }else{
                    return redirect(route('ot.list',[],false))->with([
                        'feedback' => true,
                        'feedback_text' => "Your overtime claim has successfully submitted.",
                        'feedback_title' => "Successfully Submitted"
                    ]);
                }
            
            //if on leave
            }else{
                return redirect(route('ot.form',[],false))->with([
                    'feedback' => true,
                    'feedback_text' => "You are on leave for this date.",
                    'feedback_title' => "Submission Failed!"
                ]);
            }
        }
    }

    //--------------------------------------------------get image thumbnail--------------------------------------------------
    public function getthumbnail(Request $req){
        $file = OvertimeFile::find($req->tid);
        if($file){
            return Storage::download('public/'.$file->thumbnail);
        }
    }

    //--------------------------------------------------get image file--------------------------------------------------
    public function getfile(Request $req){
        $file = OvertimeFile::find($req->tid);
        if($file){
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
    public function formdelete(Request $req){
        $claimdetail = OvertimeDetail::where('id', $req->delid)->first();
        $claim =  Overtime::where('id', $claimdetail->ot_id)->first();
        $start = $claimdetail->start_time;
        $end = $claimdetail->end_time;
        $updatemonth = OvertimeMonth::find($claim->month_id);
        $updateclaim = Overtime::find($claim->id);
        $totaltime = (($updatemonth->hour*60)+$updatemonth->minute)-((($claimdetail->hour)*60)+$claimdetail->minute);
        $updatemonth->hour = (int)($totaltime/60);
        $updatemonth->minute = ($totaltime%60);
        $totaltime = (($updateclaim->total_hour*60)+$claimdetail->total_minute)-((($claimdetail->hour)*60)+$claim->minute);
        $updateclaim->total_hour = (int)($totaltime/60);
        $updateclaim->total_minute = ($totaltime%60);
        $updateclaim->amount = $updateclaim->amount - $claimdetail->amount;
        OvertimeDetail::find($req->delid)->delete();
        if($claimdetail->clock_in!=NULL){
            $delotpunch = OvertimePunch::where('start_time', $claimdetail->clock_in)->delete();
        }
        $claimdetail = OvertimeDetail::where('ot_id', $claim->id)->get();
        if(count($claimdetail)==0){
            $updateclaim->status = 'D1';
        }
        $updatemonth->save();
        $updateclaim->save();
        $claim = Overtime::where('id', $claim->id)->first();
        Session::put(['claim' => $claim]);
        return redirect(route('ot.form',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Your time ranged from ".date("Hi", strtotime($start))." to ".date("Hi", strtotime($end))." has deleted.",
            'feedback_title' => "Successfully Deleted"
        ]);
    }

    //--------------------------------------------------show ot verify list--------------------------------------------------
    public function verify(Request $req){
        $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', 'PV')->orderBy('date_expiry')->orderBy('date')->get();
        $view = "verifier";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    //--------------------------------------------------show ot verify report list--------------------------------------------------
    public function verifyrept(Request $req){
        $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', '!=' , 'D1')->where('status', '!=' , 'D2')->orderBy('date_expiry')->orderBy('date')->get();
        $view = "verifierrept";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    //--------------------------------------------------show ot approval list--------------------------------------------------
    public function approval(Request $req){
        $otlist = Overtime::where('approver_id', $req->user()->id)
        ->where(function($q) {
            $q->where('status', 'PV')->orWhere('status', 'PA');
        })
        ->get();
        $view = "approver";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    //--------------------------------------------------show ot approval report list--------------------------------------------------
    public function approvalrept(Request $req){
        $otlist = Overtime::where('approver_id', $req->user()->id)->where('status', '!=' , 'D1')->where('status', '!=' , 'D2')->orderBy('date_expiry')->orderBy('date')->get();
        $view = "approverrept";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    //--------------------------------------------------ot admin list set session--------------------------------------------------
    public function admin(Request $req){
        if($req->session()->get('otlist')==null){
            $otlist = [];
        }else{
            $otlist = $req->session()->get('otlist');
        }
        $view = "admin";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    //--------------------------------------------------show ot admin view--------------------------------------------------
    public function adminview(){
        Session::put(['otlist'=>[]]);
        return redirect(route('ot.admin',[],false));
    }

    //--------------------------------------------------searc ot admin--------------------------------------------------
    public function adminsearch(Request $req){
        $otlist = Overtime::query();
        if($req->searchcomp!=""){
            $onecomp = explode(", ", $req->searchcomp);
            foreach($onecomp as $one){
                if($one!=""){
                    $otlist = $otlist->orWhere('company_id', 'LIKE', '%' .$one. '%');
                }
            }
        }
        if($req->searchpersno!=""){
            $onecomp = explode(", ", $req->searchpersno);
            foreach($onecomp as $one){
                if($one!=""){
                    $otlist = $otlist->orWhere('user_id', 'LIKE', '%' .$one. '%');
                }
            }
        }
        if($req->searchpersarea!=""){
            $onecomp = explode(", ", $req->searchpersarea);
            foreach($onecomp as $one){
                if($one!=""){
                    $otlist = $otlist->orWhere('persarea', 'LIKE', '%' .$one. '%');
                }
            }
        }
        if($req->searchperssarea!=""){
            $onecomp = explode(", ", $req->searchperssarea);
            foreach($onecomp as $one){
                if($one!=""){
                    $otlist = $otlist->orWhere('perssubarea', 'LIKE', '%' .$one. '%');
                }
            }
        }
        if(($req->searchdate1!="")&&($req->searchdate12="")){
            $otlist = $otlist->orWhere('submitted_date', '>=', $req->searchdate1.' 00:00:00')->orWhere('submitted_date', '<=', $req->searchdate2.' 00:00:00');
        }
        if($req->searchstatus!=""){
            $onecomp = explode(", ", $req->searchstatus);
            foreach($onecomp as $one){
                if($one!=""){
                    if($one=="Pending Verification"){
                        $stat="PV";
                    }else if($one=="Pending Approval"){
                        $stat="PA";
                    }else if($one=="Approved"){
                        $stat="A";
                    }
                    $otlist = $otlist->orWhere('status', $stat);
                }
            }
        }
        if($req->searchotdate!=""){
            $onecomp = explode(", ", $req->searchotdate);
            foreach($onecomp as $one){
                if($one!=""){
                    $otlist = $otlist->orWhere('date', $one);
                }
            }
        }
        $otlist = $otlist->where(function($q) {
            $q->where('status', '!=', 'Q1')->where('status', '!=', 'Q2')->where('status', '!=', 'D1')->where('status', '!=', 'D2');
        })->orderBy('date_expiry')->orderBy('date')->get();
        Session::put(['otlist' => $otlist]);
        return redirect(route('ot.admin',[],false));
    }

    //--------------------------------------------------ot query actions--------------------------------------------------
    public function query (Request $req){
        if($req->typef=="verifier"){
            $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', 'PV')->orderBy('date_expiry')->orderBy('date')->get();
        }else if($req->typef=="approver"){
            $otlist = Overtime::where('approver_id', $req->user()->id)
            ->where(function($q) {
                $q->where('status', 'PV')->orWhere('status', 'PA');
            })
            ->get();
        }else if($req->typef=="admin"){
            $otlist = $req->session()->get('otlist');
        }
        $yes = false;
        // dd($otlist);
        for($i=0; $i<count($otlist); $i++){
            if($req->inputaction[$i]!=""){
                $reg = Psubarea::where('state_id', $otlist[$i]->name->stateid->id)->first();
                $expiry = OvertimeExpiry::where('company_id', $otlist[$i]->name->company_id)->where('region', $reg->region)->where('start_date','<=', $otlist[$i]->date)->where('end_date','>', $otlist[$i]->date)->first();
                
                $claim = Overtime::where('id', $req->inputid[$i])->first();
                $updateclaim = Overtime::find($req->inputid[$i]);
                if(($updateclaim->status=="PV")&&($updateclaim->verifier_id==null)){
                    $updateclaim->status=="PA";
                    
                }
                if($req->inputaction[$i]=="PA"){
                    // $updateclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));
                    $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Verified', 'Verified');
                    //notification
                    $user = $claim->approver;
                    $myot = \App\Overtime::where('id', $req->inputid[$i])->first();
                    $user->notify(new OTVerified($myot));

                    $updateclaim->verification_date = date("Y-m-d H:i:s");
                }else if($req->inputaction[$i]=="A"){
                    $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Approved', 'Approved');
                    $updateclaim->approved_date = date("Y-m-d H:i:s");
                }else if($req->inputaction[$i]=="Q2"){
                    $updatemonth = OvertimeMonth::find($updateclaim->month_id);
                    $totaltime = (($updatemonth->total_hour*60)+$updatemonth->total_minute) - (($updateclaim->total_hour*60)+$updateclaim->total_minute);
                    $updatemonth->total_hour = (int)($totaltime/60);
                    $updatemonth->total_minute = ($totaltime%60);
                    $updatemonth->save();
                    $updateclaim->date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+1 months", strtotime(date("Y-m-d")))))));
                    $updateclaim->queried_date = date("Y-m-d H:i:s");
                    // dd($updatemonth->total_hour);
                    $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Queried', 'Queried with message: "'.$req->inputremark[$i].'"');
                    // $updateclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));
                    
                    //notification
                    $user = $claim->name;
                    $myot = \App\Overtime::where('id', $claim->id)->first();
                    // dd($myot);
                    if($claim->status=="PA"){
                        $user->notify(new OTQueryApprove($myot));
                    }else{

                        $user->notify(new OTQueryVerify($myot));
                    }

                }else if($req->inputaction[$i]=="Assign"){
                    $updateclaim->status="PV";
                    $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Assigned Verifier', 'Assigned Verifier with message: "'.$req->inputremark[$i].'"');
                }else if($req->inputaction[$i]=="Remove"){
                    $updateclaim->status="PA";
                    $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Removed Verifier', 'Removed Verifier');
                }
                if($expiry->status == "ACTIVE"){
                    if((($expiry->based_date == "Submit to Approver Date")&&($updateclaim->status == 'PA'))||(($expiry->based_date == "Query Date")&&($updateclaim->status == 'Q2'))){
                        $draftclaim->date_expiry = date('Y-m-d', strtotime("+".$expiry->noofmonth." months"));
                    }
                } 
                $updateclaim->status=$req->inputaction[$i];
                $updateclaim->verifier_id=$req->verifier[$i];
                $updateclaim->save();
                $yes = true;
            }
        }
        // return redirect(route('ot.approval',[],false));
        if($yes){
            if($req->typef=="verifier"){
                return redirect(route('ot.verify',[],false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your pending overtime claim has successfully submitted.",
                    'feedback_title' => "Successfully Submitted"
                ]);
            }else if($req->typef=="approver"){
                return redirect(route('ot.approval',[],false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your pending overtime claim has successfully submitted.",
                    'feedback_title' => "Successfully Submitted"
                ]);
            }else if($req->typef=="admin"){
                return redirect(route('ot.admin',[],false))->with([
                    'feedback' => true,
                    'feedback_text' => "Your pending overtime claim has successfully submitted.",
                    'feedback_title' => "Successfully Submitted"
                ]);
            }
        }else{
            return redirect(route('ot.approval',[],false))->with([]);
        }
    }

    //--------------------------------------------------search verifier--------------------------------------------------
    public function search(Request $req){
        $date = date('Y-m-d');
        if($req->type=="normal"){
            $staff = UserRecord::where('name', 'LIKE', '%' .$req->name. '%')->where('name', '!=', $req->user()->name)->where('upd_sap','<=',$date)->orderBy('name', 'ASC')->get();
        }else{
            $staff = UserRecord::query();
            if($req->name!=""){
                $staff = $staff->orWhere('name', 'LIKE', '%' .$req->name. '%');
            }
            if($req->staffno!=""){
                $staff = $staff->orWhere('staffno', 'LIKE', '%' .$req->staffno. '%');
            }
            if($req->email!=""){
                $staff = $staff->orWhere('email', 'LIKE', '%' .$req->email. '%');
            }
            // if($req->mobile!=""){
            //     $staff = $staff->orWhere('name', 'LIKE', '%' .$req->mobile. '%');
            // }
            // if($req->office!=""){
            //     $staff = $staff->orWhere('name', 'LIKE', '%' .$req->office. '%');
            // }
            $staff = $staff->where('upd_sap','<=',$date)->orderBy('name', 'ASC')->get();
        }
        $arr = [];
        foreach($staff as $s){
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
  public function getverifier(Request $req){
        $date = date('Y-m-d');
        $staff = UserRecord::where('user_id', $req->id)->where('upd_sap','<=',$date)->first();

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

  public static function getQueryAmount(){
        // $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', 'PV')->orWhere('approver_id', $req->user()->id)->where('status', 'PA')->orderBy('date_expiry')->orderBy('date')->get();
        // $count =  count($otlist);
        // return 5;
    }
}
