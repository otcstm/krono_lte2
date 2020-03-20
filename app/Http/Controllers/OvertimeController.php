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
use App\Psubarea;
use App\DayType;
use App\Costcenter;
use App\Project;
use App\InternalOrder;
use App\MaintenanceOrder;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OvertimeController extends Controller{
    public function list(Request $req){
        $otlist = Overtime::where('user_id', $req->user()->id)->orderBy('status')->orderBy('date_expiry')->orderBy('date')->get();
        return view('staff.overtime', ['otlist' => $otlist]);
    }

    public function form(Request $req){
        $reg = Psubarea::where('state_id', $req->user()->state_id)->first();
        // $costc = Costcenter::where('company_id', $req->user()->company_id)->get();
        // $costc = Costcenter::get();
        $costc = null;
        $type = null;
        $compn = null;
        $orderno = null;
        $networkh = null;
        $networkn = null;
        $appr = null;
        // dd($cc);
        // $total = 
        // dd($reg->region);
       if($req->session()->get('claim')!=null){
            $day = UserHelper::CheckDay($req->user()->id, $req->session()->get('claim')->date);
            $eligiblehour = OvertimeEligibility::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', $req->session()->get('claim')->date)->where('end_date','>', $req->session()->get('claim')->date)->first();
            
            // dd($req->session()->get('claim')->charge_type);

            if($req->session()->get('claim')->charge_type=="Other Cost Center"){
                $compn = Costcenter::groupBy('company_id')->get();
                $costc = Costcenter::where('company_id', $req->session()->get('claim')->company_id)->get();
                if(count($costc)==0){
                    $costc = null;
                }
                $appr = UserRecord::where('upd_sap','<=',date('Y-m-d'))->where('company_id', $req->session()->get('claim')->company_id)->where('costcentr', $req->session()->get('claim')->other_costcenter)->where('user_id', '!=', $req->user()->id)->get();
                if(count($appr)==0){
                    $appr = null;
                }
            }else if($req->session()->get('claim')->charge_type=="Project"){
                $compn = Project::groupBy('company_code')->get();
                $costc = Project::where('company_code', $req->session()->get('claim')->company_id)->groupBy('cost_center')->get();
                if(count($costc)==0){
                    $costc = null;
                }
                $type= Project::where('company_code', $req->user()->company_id)->where('cost_center', $req->session()->get('claim')->other_costcenter)->groupBy('type')->get();
                if(count($type)==0){
                    $type = null;
                }
                $orderno= Project::where('company_code', $req->user()->company_id)->where('cost_center', $req->session()->get('claim')->other_costcenter)->where('type', $req->session()->get('claim')->project_type)->get();
                if(count($orderno)==0){
                    $orderno = null;
                }
                $networkh= Project::where('company_code', $req->user()->company_id)->where('cost_center', $req->session()->get('claim')->other_costcenter)->where('type', $req->session()->get('claim')->project_type)->where('project_no', $req->session()->get('claim')->project_no)->get();
                if(count($networkh)==0){
                    $networkh = null;
                }
                $networkn= Project::where('company_code', $req->user()->company_id)->where('cost_center', $req->session()->get('claim')->other_costcenter)->where('type', $req->session()->get('claim')->project_type)->where('project_no', $req->session()->get('claim')->project_no)->where('network_header', $req->session()->get('claim')->network_header)->get();
                if(count($networkn)==0){
                    $networkn = null;
                }
            }else if($req->session()->get('claim')->charge_type=="Internal Order"){
                $compn = InternalOrder::groupBy('company_code')->get();
                $costc = InternalOrder::where('company_code', $req->session()->get('claim')->company_id)->groupBy('cost_center')->get();
                if(count($costc)==0){
                    $costc = null;
                }
                if($req->session()->get('claim')->other_costcenter=="No Cost Center"){
                    $type= InternalOrder::where('company_code', $req->user()->company_id)->where('cost_center', '')->groupBy('order_type')->get();
                }else{
                    $type= InternalOrder::where('company_code', $req->user()->company_id)->where('cost_center', $req->session()->get('claim')->other_costcenter)->groupBy('order_type')->get();
                }
                if(count($type)==0){
                    $type = null;
                }
                if($req->session()->get('claim')->other_costcenter=="No Cost Center"){
                    $orderno= InternalOrder::where('company_code', $req->user()->company_id)->where('cost_center', '')->where('order_type', $req->session()->get('claim')->project_type)->get();
                }else{
                    $orderno= InternalOrder::where('company_code', $req->user()->company_id)->where('cost_center', $req->session()->get('claim')->other_costcenter)->where('order_type', $req->session()->get('claim')->project_type)->get();
                }
                if(count($orderno)==0){
                    $orderno = null;
                }
                if(($req->session()->get('claim')->other_costcenter=="No Cost Center")&&($cost!=null)&&($type!=null)&&($orderno!=null)){
                    // $orderno= InternalOrder::where('company_code', $req->user()->company_id)->where('cost_center', '')->where('order_type', $req->session()->get('claim')->project_type)->get();
                }else{
                    $appr = UserRecord::where('upd_sap','<=',date('Y-m-d'))->where('company_id', $req->session()->get('claim')->company_id)->where('costcentr', $req->session()->get('claim')->other_costcenter)->where('user_id', '!=', $req->user()->id)->get();
                
                }
                if(count($appr)==0){
                    $appr = null;
                }
            }else if($req->session()->get('claim')->charge_type=="Maintenance Order"){
                $compn = MaintenanceOrder::groupBy('company_code')->get();
                $costc = MaintenanceOrder::where('company_code', $req->session()->get('claim')->company_id)->groupBy('cost_center')->get();
                if(count($costc)==0){
                    $costc = null;
                }
                // if($req->session()->get('claim')->project_type!=null){
                    $type= MaintenanceOrder::where('company_code', $req->user()->company_id)->where('cost_center', $req->session()->get('claim')->other_costcenter)->groupBy('type')->get();
                // }
                if(count($type)==0){
                    $type = null;
                }
                $orderno= MaintenanceOrder::where('company_code', $req->user()->company_id)->where('cost_center', $req->session()->get('claim')->other_costcenter)->where('type', $req->session()->get('claim')->project_type)->get();
                if(count($orderno)==0){
                    $orderno = null;
                }
            }


            return view('staff.otform', ['draft' =>[], 'claim' => $req->session()->get('claim'), 'day' => $day, 'eligiblehour' => $eligiblehour->hourpermonth, 'costc' => $costc, 'compn' => $compn, 'type' => $type, 'orderno' => $orderno, 'networkh' => $networkh, 'networkn' => $networkn, 'appr' => $appr]);
        }else if($req->session()->get('draft')!=null){
            $draft = $req->session()->get('draft');
            $day = UserHelper::CheckDay($req->user()->id, date('Y-m-d', strtotime($draft[4])));
            $eligiblehour = OvertimeEligibility::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', $draft[4])->where('end_date','>', $draft[4])->first();
            return view('staff.otform', ['draft' => $req->session()->get('draft'), 'day' => $day, 'eligiblehour' => $eligiblehour->hourpermonth, 'costc' => $costc]);
        }else{
            return view('staff.otform', []);
        }
    }

    public function update(Request $req){
        $claim = Overtime::where('id', $req->inputid)->first();
        Session::put(['draft' => [], 'claim' => $claim]);
        return redirect(route('ot.form',[],false));
    }

    public function detail(Request $req){
        $claim = Overtime::where('id', $req->detailid)->first();
        Session::put(['draft' => [], 'claim' => $claim]);
        Session::put(['back' => $req->type]); 
        return view('staff.otdetail', ['claim' => $req->session()->get('claim')]);
    }

    public function remove(Request $req){
        $claim = Overtime::where('id', $req->delid)->first();
        $updatemonth = OvertimeMonth::find($claim->month_id);
        $totaltime = (($updatemonth->hour*60)+$updatemonth->minute)-((($claim->total_hour)*60)+$claim->total_minute);
        $updatemonth->hour = (int)($totaltime/60);
        $updatemonth->minute = ($totaltime%60);
        $updatemonth->save();
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

    public function submit(Request $req){
        $submit = true;
        $id = explode(" ", $req->submitid);
        $reg = Psubarea::where('state_id', $req->user()->state_id)->first();
        if($req->user()->ot_hour_exception!="X"){
            for($i = 0; $i<count($id); $i++){
                $claim = Overtime::find($id[$i]);
                $eligiblehour = OvertimeEligibility::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', $claim->date)->where('end_date','>', $claim->date)->first();
                $month = OvertimeMonth::where('id', $claim->month_id)->first();
                $totalsubmit = ($month->hour*60+$month->minute) + ($claim->total_hour*60+$claim->total_minute);
                // dd($totalsubmit);
                if($totalsubmit>($eligiblehour->hourpermonth*60)){
                    $submit = false;
                }
            }
        }
        if($submit){
            for($i = 0; $i<count($id); $i++){
                $updateclaim = Overtime::find($id[$i]);
                $updateclaim->approver_id = $req->user()->reptto;
                $updateclaim->submitted_date = date("Y-m-d H:i:s");
                // $updateclaim->verifier_id =  $req->user()->id; //temp 
                // $updateclaim->verifier_id =  "55323"; //temp 
                $execute = UserHelper::LogOT($id[$i], $req->user()->id, "Submitted", "Submitted ".$updateclaim->refno);
                if($updateclaim->verifier_id==null){
                    $updateclaim->status = 'PA';
                }else{
                    $updateclaim->status = 'PV';
                }
                $expiry = OvertimeExpiry::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', $claim->date)->where('end_date','>', $claim->date)->first();
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
            }

            return redirect(route('ot.list',[],false))->with([
                'feedback' => true,
                'feedback_text' => "Your overtime claim has successfully submitted.",
                'feedback_title' => "Successfully Submitted"
            ]);
        }else{
            return redirect(route('ot.list',[],false))->with([
                'feedback' => true,
                'feedback_text' => "Your submitted claim time has exceeded eligible claim time.",
                'feedback_title' => "Failed to submit!"
            ]);
        }
    }

    public function formnew(Request $req){
        Session::put(['draft' => [], 'claim' => []]);
        return redirect(route('ot.form',[],false));
    }

    public function formdate(Request $req){
        $gm = UserHelper::CheckGM(date("Y-m-d"), $req->inputdate);
        $staffr = UserRecord::where('user_id', $req->user()->id)->where('upd_sap','<=',date('Y-m-d'))->first();
         // temp=====================================================
        $day = date('N', strtotime($req->inputdate));
        if($day==5){
            $day_type = 4;
        }elseif($day>6){
            $day_type = 5;
        }else{
            $day_type = 2;
        }
        // temp=====================================================
        Session::put(['draft' => []]);
        $claim = Overtime::where('user_id', $req->user()->id)->where('date', $req->inputdate)->first();
        if(empty($claim)){ //check got data for ot month or not
            $claimdate = $req->inputdate;
            $claimmonth = date("m", strtotime($claimdate));
            $claimyear = date("y", strtotime($claimdate));
            $claimday = date("l", strtotime($claimdate));
            $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->first();
            if(empty($claimtime)){ //if empty create ot month
                $newmonth = new OvertimeMonth;
                $newmonth->user_id = $req->user()->id;
                $newmonth->year = $claimyear;
                $newmonth->month = $claimmonth;
                $newmonth->save();
                $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->first();
            }
            $punch = OvertimePunch::where('user_id', $req->user()->id)->where('date', $req->inputdate)->get();

            //if got ot punch
            if(count($punch)!=0){ 
                $totalhour = 0;
                $totalminute = 0;
                $reg = Psubarea::where('state_id', $req->user()->state_id)->first();
                $wage = OvertimeFormula::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', $claimdate)->where('end_date','>', $claimdate)->first();   //temp
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
                if($gm){
                    $draftclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($claimdate)));
                    $draftclaim->date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+1 months", strtotime(date("Y-m-d")))))));
                
                }else{
                    $draftclaim->approver_id = $req->user()->reptto;
                    $draftclaim->date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+3 months", strtotime($req->inputdate))))));
                }
                $draftclaim->state_id =  $req->user()->state_id;
                $draftclaim->daytype_id =  $day_type;
                $draftclaim->profile_id =  $staffr->id;
                $draftclaim->company_id =  $req->user()->company_id;
                $draftclaim->persarea =  $req->user()->persarea;
                $draftclaim->perssubarea =  $req->user()->perssubarea;
                $draftclaim->punch_id =  $punch[0]->punch_id;
                $draftclaim->region =  $reg->region;
                $draftclaim->charge_type =  "Own Cost Center";
                $draftclaim->costcenter =  $staffr->costcentr;
                $draftclaim->wage_type =  $wage->legacy_codes; //temp
                $userrecid = URHelper::getUserRecordByDate($req->user()->persno, date('Y-m-d', strtotime($claimdate)));
                $draftclaim->user_records_id =  $userrecid->id;
                $draftclaim->save();
                $claim = Overtime::where('user_id', $req->user()->id)->where('date', $req->inputdate)->first();
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
                    $salary = $req->user()->salary;
                    if($req->user()->ot_salary_exception=="X"){
                        $salarycap = OvertimeEligibility::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', $claim->date)->where('end_date','>', $claim->date)->first();
                        $salary = $salarycap->salary_cap;
                    }
                    $pay = UserHelper::CalOT($req->user()->salary, $punches->hour, $punches->minute);
                    $newclaim->amount = $pay;
                    $newclaim->justification = "";
                    $newclaim->in_latitude = $punches->in_latitude;
                    $newclaim->in_longitude = $punches->in_longitude;
                    $newclaim->out_latitude = $punches->out_latitude;
                    $newclaim->out_longitude = $punches->out_longitude;
                    // $updatemonth = OvertimeMonth::find($claimtime->id);
                    // $totaltime = (($updatemonth->hour*60)+$updatemonth->minute)+(($punches->hour*60)+$punches->minute);
                    // $updatemonth->hour = (int)($totaltime/60);
                    // $updatemonth->minute = ($totaltime%60);
                    // $updateclaim = Overtime::find($claim->id);
                    // $totaltime = (($updateclaim->total_hour*60)+$updateclaim->total_minute)+(($punches->hour*60)+$punches->minute);
                    // $updateclaim->total_hour = (int)($totaltime/60);
                    // $updateclaim->total_minute = ($totaltime%60);
                    // $updateclaim->amount = $updateclaim->amount + $pay;
                    $newclaim->save();
                    // $updatemonth->save();
                    // $updateclaim->save();
                    
                    $staffpunch->save();
                }
                $execute = UserHelper::LogOT($claim->id, $req->user()->id, "Created draft", "Created draft for ".$claim->refno);
                $claim = Overtime::where('user_id', $req->user()->id)->where('date', $req->inputdate)->first();
                Session::put(['draft' => []]);
            }
            
            //if dont have OT Punch
            else{
                $reg = Psubarea::where('state_id', $req->user()->state_id)->first();
                $expiry = OvertimeExpiry::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', $claimdate)->where('end_date','>', $claimdate)->first();
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
                if($gm){
                    $gmid = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($claimdate)));
                    $approve = User::where('id', $gmid)->first();
                    $verify = User::where('id', $req->user()->reptto)->first();
                    // $date_expiry = "";
                    
                    $date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+1 months", strtotime(date("Y-m-d")))))));
                }else{
                    $approve = User::where('id', $req->user()->reptto)->first();
                    $verify = "";
                    $date_expiry = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d', strtotime("+3 months", strtotime($req->inputdate))))));
                }
                if($verify!=""){
                    $verifyn = $verify->name;
                }else{
                    $verifyn = "N/A";
                }
                $state = UserRecord::where('upd_sap','<=',$claimdate)->first();
                $draft = array("OT".date("Ymd", strtotime($claimdate))."-".sprintf("%08d", $req->user()->id), $date_expiry, date("Y-m-d H:i:s"), $claimtime, $req->inputdate, $req->user()->name, $state->state_id, $state->statet->state_descr, $dt->description, $verifyn, $approve->name, $staffr->costcentr);
                //[0] - refno, [1] - expiry, [2] - datetime created, [3] - month, [4] - date, [5] - user name, [6] - stateid, [7] - statedescr, [8] - day type, [9] - verifier name, [10] - approver name, [11] - cost center
                Session::put(['draft' => $draft]);
                // dd($req->session());
            }
        }else{
            Session::put(['draft' => []]);
        }
        Session::put(['claim' => $claim]);
        return redirect(route('ot.form',[],false));
    }

 // =============================================================================================================
    public function formsubmit(Request $req){
        $status = true; 
    //    dd($req->formtype);
        $reg = Psubarea::where('state_id', $req->user()->state_id)->first();
        
        if($req->inputid==""){
            // $difdatem = date('m') - date('m',strtotime($req->inputdate));
            // $difdated = date('d') - date('d',strtotime($req->inputdate));
            // if($difdatem<0){
            //     $difdatem=$difdatem+12;
            // }
            // $gm = true;
            // if(($difdatem<4)){
            //     $gm = false;
            //     if($difdatem==3){
            //         if($difdated>=0){
            //         $gm = true;
            //         }
            //     }
            // }
            // dd(date("Y-m-d", strtotime(($req->session()->get('draft'))[2])));
            $gm = UserHelper::CheckGM(date("Y-m-d"), date("Y-m-d", strtotime(($req->session()->get('draft'))[4])));
            $staffr = UserRecord::where('user_id', $req->user()->id)->where('upd_sap','<=',date('Y-m-d'))->first();
            $wage = OvertimeFormula::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', ($req->session()->get('draft'))[4])->where('end_date','>', ($req->session()->get('draft'))[4])->first();   //temp
            $draftclaim = new Overtime;
            $draftclaim->refno = ($req->session()->get('draft'))[0];
            $draftclaim->user_id = $req->user()->id;
            $draftclaim->profile_id = $staffr->id;
            $draftclaim->month_id = ($req->session()->get('draft'))[3]->id;
            $draftclaim->date = ($req->session()->get('draft'))[4];
            $draftclaim->date_created = date("Y-m-d", strtotime(($req->session()->get('draft'))[2]));
            // if(($req->session()->get('draft'))[1]!=""){
                $draftclaim->date_expiry = ($req->session()->get('draft'))[1];
            // }
            $draftclaim->total_hour = 0;
            $draftclaim->total_minute = 0;
            $draftclaim->amount = 0; 
            // temp=====================================================
            $day = date('N', strtotime(($req->session()->get('draft'))[4]));
            if($day==5){
                $day_type = 4;
            }elseif($day>6){
                $day_type = 5;
            }else{
                $day_type = 2;
            }
            // $draftclaim->verifier_id =  $req->user()->id; //temp 
            // $draftclaim->verifier_id =  55323; //temp 
            // temp=====================================================
            if($gm){
                $draftclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime(($req->session()->get('draft'))[2])));
                $draftclaim->verifier_id =  $req->user()->reptto;
            }else{
                $draftclaim->approver_id = $req->user()->reptto;
            }
            $draftclaim->daytype_id =  $day_type;
            $draftclaim->state_id =  ($req->session()->get('draft'))[6];
            $draftclaim->company_id =  $req->user()->company_id;
            $draftclaim->persarea =  $req->user()->persarea;
            $draftclaim->perssubarea =  $req->user()->perssubarea;
            $draftclaim->region =  $reg->region;
            $draftclaim->costcenter =  $staffr->costcentr;
            $draftclaim->wage_type =  $wage->legacy_codes; //temp
            $userrecid = URHelper::getUserRecordByDate($req->user()->persno, date('Y-m-d', strtotime(($req->session()->get('draft'))[4])));   
            $draftclaim->user_records_id =  $userrecid->id; 
            $draftclaim->status = 'D1';
            $draftclaim->save();
            $claim = Overtime::where('user_id', $req->user()->id)->where('date', ($req->session()->get('draft'))[4])->first();
            $execute = UserHelper::LogOT($claim->id, $req->user()->id, "Created draft", "Created draft for ".$claim->refno);    
            Session::put(['draft' => []]);
        }else{

            $claim = Overtime::where('id', $req->inputid)->first();
            // $difdatem = date('m',strtotime($claim->date_created)) - date('m',strtotime($claim->date));
            // $difdated = date('m',strtotime($claim->date_created)) - date('d',strtotime($claim->date));
            // if($difdatem<0){
            //     $difdatem=$difdatem+12;
            // }
            // $gm = true;
            // if(($difdatem<4)){
            //     $gm = false;
            //     if($difdatem==3){
            //         if($difdated>=0){
            //         $gm = true;
            //         }
            //     }
            // }
            
            $gm = UserHelper::CheckGM($claim->date_created, $claim->date);
            // dd($difdated);
        }
        $salary = $req->user()->salary;
        if($req->user()->ot_salary_exception=="X"){
            $salarycap = OvertimeEligibility::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', $claim->date)->where('end_date','>', $claim->date)->first();
            $salary = $salarycap->salary_cap;
        }
        if($req->formtype=="add"){
            // dd($req->inputstartnew);
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
            // $newdetail->justification = $req->formtype;
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
        if(($req->formtype=="save")||($req->formtype=="submit")||($req->formtype=="delete")){
            $claim = Overtime::where('id', $claim->id)->first();
            $claimdetail = OvertimeDetail::where('ot_id', $claim->id)->get();

            for($i=0; $i<count($claimdetail); $i++){
                if(($req->inputstart[$i]!="")&&$req->inputend[$i]!=""){

                    $operation = null;
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
                    if($updatedetail->checked != $req->inputcheck[$i]){
                        $updatedetail->checked = $req->inputcheck[$i];
                        $operation = $req->inputcheck[$i];
                    }
                    $updatedetail->justification = $req->inputremark[$i];
                    $updatemonth = OvertimeMonth::find($claim->month_id);
                    $updateclaim = Overtime::find($claim->id);
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
                    }else{
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
        if(($req->chargetype=="")||(count($claimdetail)==0)){
            $status = false;
        }

        // dd($status);
        $updateclaim = Overtime::find($claim->id);
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

        //change charge type
        $changecompany = true;
        if(($updateclaim->charge_type!=$req->chargetype)){
            $updateclaim->other_costcenter = null;
            if(in_array($req->chargetype, $array = array("Project", "Internal Order", "Maintenance Order", "Other Cost Center"))){
                $updateclaim->company_id = null;
            }else{
                $updateclaim->company_id = $req->user()->company_id;
            }
            $updateclaim->charge_type = null;
            $updateclaim->order_no = null;
            $updateclaim->project_no = null;
            $updateclaim->project_type = null;
            $updateclaim->network_header = null;
            $updateclaim->network_act_no = null;
            $changecompany = false;
            // if(!(($updateclaim->status=="Q1")||($updateclaim->status=="Q2"))){
                if($gm){
                    $updateclaim->verifier_id =  null;
                }else{
                    $updateclaim->verifier_id =  null;
                    $updateclaim->approver_id = null;
                }
                // if($gm){
                //     $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                //     $updateclaim->verifier_id =  $req->user()->reptto;
                // }else{
                //     $updateclaim->approver_id = $req->user()->reptto;
                // }
            // }
        }
        // dd($req->compn);
        $updateclaim->charge_type = $req->chargetype;
        if(in_array($claim->charge_type, $array = array("Project", "Internal Order", "Maintenance Order", "Other Cost Center"))){
            if($changecompany){
                $updateclaim->company_id = $req->compn;
            }
            $updateclaim->other_costcenter = $req->costc;
            if(in_array($claim->charge_type, $array = array("Project", "Internal Order", "Maintenance Order"))){
                $updateclaim->project_type = $req->type;
            }
            if(in_array($claim->charge_type, $array = array("Internal Order", "Maintenance Order"))){
                $updateclaim->order_no = $req->orderno;
            }else if(in_array($claim->charge_type, $array = array("Project"))){
                $updateclaim->project_no = $req->orderno;
                $updateclaim->network_header = $req->networkh;
                $updateclaim->network_act_no = $req->networkn;
            }
        }


        //change approver
        if(in_array($req->chargetype, $array = array("Project", "Internal Order", "Maintenance Order", "Other Cost Center"))){
            if($req->chargetype=="Project"){
                // $updateclaim->approver_id = 16926;
                // if(!(($updateclaim->status=="Q1")||($updateclaim->status=="Q2"))){
                // $updateclaim->verifier_id =  null;
                // }
                $projecta= Project::where('company_code', $req->user()->company_id)->where('cost_center', $updateclaim->other_costcenter)->where('type', $updateclaim->project_type)->where('project_no', $updateclaim->project_no)->where('network_header', $updateclaim->network_header)->where('network_act_no',$updateclaim->network_act_no )->first();
                if($projecta!=null){
                    if($gm){
                        $updateclaim->verifier_id = $projecta->approver_id;
                    }else{
                        $updateclaim->approver_id = $projecta->approver_id;
                    }
                }
            }else if($req->chargetype=="Internal Order"){
                $ordern= InternalOrder::where('company_code', $req->user()->company_id)->where('cost_center', $updateclaim->other_costcenter)->where('order_type', $updateclaim->project_type)->where('id', $updateclaim->order_no)->first();
                if($ordern!=null){
                    if($ordern->pers_responsible!=""){
                        if($gm){
                            $updateclaim->verifier_id = $ordern->pers_responsible;
                        }else{
                            $updateclaim->approver_id = $ordern->pers_responsible;
                        }
                    }
                }
            }else if($req->chargetype=="Maintenance Order"){
                $ordern= MaintenanceOrder::where('company_code', $req->user()->company_id)->where('cost_center', $updateclaim->other_costcenter)->where('type', $updateclaim->project_type)->where('id', $updateclaim->order_no)->first();
                if($ordern!=null){
                    if($gm){
                        $updateclaim->verifier_id = $ordern->approver_id;
                    }else{
                        $updateclaim->approver_id = $ordern->approver_id;
                    }
                }
            }else if($req->chargetype=="Other Cost Center"){
            // if(!(($updateclaim->status=="Q1")||($updateclaim->status=="Q2"))){
                if($gm){
                    $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                    $updateclaim->verifier_id =  $req->approvern;
                }else{
                    $updateclaim->approver_id = $req->approvern;
                }
            }
            // dd($req->approvern);
        }else{
            // if(!(($updateclaim->status=="Q1")||($updateclaim->status=="Q2"))){
                if($gm){
                    $updateclaim->approver_id = URHelper::getGM($req->user()->persno, date('Y-m-d', strtotime($updateclaim->date)));
                    $updateclaim->verifier_id =  $req->user()->reptto;
                }else{
                    $updateclaim->approver_id = $req->user()->reptto;
                }
            // }
        }

        $updateclaim->save();

        if(($req->inputfile!="")&&($req->formtype!="delete")){
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
        }elseif($req->formtype=="delete"){
            $file = OvertimeFile::where('id', $req->filedel)->first();
            Storage::delete('public/'.$file->filename);
            Storage::delete('public/'.$file->thumbnail);
            OvertimeFile::find($req->filedel)->delete();
        }

        $claim = Overtime::where('id', $claim->id)->first();
        Session::put(['claim' => $claim]);
        if($req->formtype=="add"){ //if add only
            return redirect(route('ot.form',[],false));
            // return redirect(route('ot.form',[],false))->with([
            //     'feedback' => true,
            //     'feedback_text' => "New overtime has successfully added.",
            //     'feedback_title' => "Successfully Added Time"
            // ]);
        }
        if($req->formtype=="save"){ //if save only
            return redirect(route('ot.form',[],false));
            // return redirect(route('ot.form',[],false))->with([
            //     'feedback' => true,
            //     'feedback_text' => "Successfully saved claim!",
            //     'feedback_type' => "success"
            // ]);
        }

        if($req->formtype=="delete"){ //if save only
            // return redirect(route('ot.form',[],false));
            return redirect(route('ot.form',[],false))->with([
                'feedback' => true,
                'feedback_text' => "Successfully deleted file.",
                'feedback_title' => "Success"
            ]);
        }
        if($req->formtype=="submit"){ //if submit
            $reg = Psubarea::where('state_id', $req->user()->state_id)->first();
            $month = OvertimeMonth::where('id', $claim->month_id)->first();
            $totalsubmit = (($claim->total_hour*60)+$claim->total_minute)+(($month->total_hour*60)+$month->total_minute);
            if($req->user()->ot_hour_exception!="X"){
                $eligiblehour = OvertimeEligibility::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', $claim->date)->where('end_date','>', $claim->date)->first();
                if($totalsubmit>($eligiblehour->hourpermonth*60)){
                    return redirect(route('ot.form',[],false))->with([
                        'feedback' => true,
                    'feedback_text' => "Your submitted claim time has exceeded eligible claim time.",
                    'feedback_title' => "Failed to submit!"
                    ]);
                }
            }
            // else{
            $updatemonth = OvertimeMonth::find($month->id);
            $updatemonth->total_hour = (int)($totalsubmit/60);
            $updatemonth->total_minute = $totalsubmit%60;
            $updatemonth->save();
            $updateclaim = Overtime::find($claim->id);


            


            // $updateclaim->approver_id = $req->user()->reptto;
            $updateclaim->submitted_date = date("Y-m-d H:i:s");
            // $updateclaim->verifier_id =  $req->user()->id; //temp 
            // $updateclaim->verifier_id =  "55323"; //temp 
            $execute = UserHelper::LogOT($claim->id, $req->user()->id, "Submitted", "Submitted ".$updateclaim->refno);   
            $expiry = OvertimeExpiry::where('company_id', $req->user()->company_id)->where('region', $reg->region)->where('start_date','<=', $claim->date)->where('end_date','>', $claim->date)->first();               
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
            return redirect(route('ot.list',[],false))->with([
                'feedback' => true,
                'feedback_text' => "Your overtime claim has successfully submitted.",
                'feedback_title' => "Successfully Submitted"
            ]);
            // }
        }
    }

// =============================================================================================================
    public function getthumbnail(Request $req){
        $file = OvertimeFile::find($req->tid);
        if($file){
            return Storage::download('public/'.$file->thumbnail);
        }
    }
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

    public function verify(Request $req){
        $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', 'PV')->orderBy('date_expiry')->orderBy('date')->get();
        $view = "verifier";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    public function verifyrept(Request $req){
        $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', '!=' , 'D1')->where('status', '!=' , 'D2')->orderBy('date_expiry')->orderBy('date')->get();
        $view = "verifierrept";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    public function approval(Request $req){
        $otlist = Overtime::where('approver_id', $req->user()->id)
        ->where(function($q) {
            $q->where('status', 'PV')->orWhere('status', 'PA');
        })
        ->get();
        $view = "approver";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    public function approvalrept(Request $req){
        $otlist = Overtime::where('approver_id', $req->user()->id)->where('status', '!=' , 'D1')->where('status', '!=' , 'D2')->orderBy('date_expiry')->orderBy('date')->get();
        $view = "approverrept";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    public function admin(Request $req){
        if($req->session()->get('otlist')==null){
            $otlist = [];
        }else{
            $otlist = $req->session()->get('otlist');
        }
        $view = "admin";
        return view('staff.otquery', ['otlist' => $otlist, 'view' => $view]);
    }

    public function adminview(){
        Session::put(['otlist'=>[]]);
        return redirect(route('ot.admin',[],false));
    }

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
        for($i=0; $i<count($otlist); $i++){
            if($req->inputaction[$i]!=""){
                $reg = Psubarea::where('state_id', $otlist[$i]->name->stateid->id)->first();
                $expiry = OvertimeExpiry::where('company_id', $otlist[$i]->name->company_id)->where('region', $reg->region)->where('start_date','<=', $otlist[$i]->date)->where('end_date','>', $otlist[$i]->date)->first();               
                $updateclaim = Overtime::find($req->inputid[$i]);
                $updateclaim->status=$req->inputaction[$i];
                if(($updateclaim->status=="PV")&&($updateclaim->verifier_id==null)){
                    $updateclaim->status=="PA";
                }
                if($req->inputaction[$i]=="PA"){
                    // $updateclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));
                    $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Verified', 'Verified');
                    
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
