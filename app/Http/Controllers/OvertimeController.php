<?php

namespace App\Http\Controllers;

use App\Shared\UserHelper;
use App\User;
use App\StaffPunch;
use App\Overtime;
use App\OvertimeMonth;
use App\OvertimeDetail;
use App\OvertimeLog;
use App\OvertimePunch;
use Session;
use Illuminate\Http\Request;

class OvertimeController extends Controller{
    public function list(Request $req){
        $otlist = Overtime::where('user_id', $req->user()->id)->orderBy('status')->orderBy('date_expiry')->orderBy('date')->get();
        return view('staff.overtime', ['otlist' => $otlist]);
    }

    public function form(Request $req){
        // dd($req->session()->get('claim'));
        if($req->session()->get('claim')!=null){
            $day = UserHelper::CheckDay($req->user()->id, $req->session()->get('claim')->date);
            return view('staff.otform', ['draft' =>[], 'claim' => $req->session()->get('claim'), 'day' => $day, 'draftform' =>  $req->session()->get('draftform')]);
        }else if($req->session()->get('draft')!=null){
            $draft = $req->session()->get('draft');
            $day = UserHelper::CheckDay($req->user()->id, date('Y-m-d', strtotime($draft[6])));
            return view('staff.otform', ['draft' => $req->session()->get('draft'), 'day' => $day, 'draftform' =>  $req->session()->get('draftform')]);
        }else{
            return view('staff.otform', []);
        }
    }

    public function save(Request $req){
        $claim = Overtime::where('id', $req->inputid)->first();
        if(empty($claim)){
            $updateclaim = new Overtime;
            $updateclaim->refno = ($req->session()->get('draft'))[0];
            $updateclaim->user_id = $req->user()->id;
            $updateclaim->month_id = ($req->session()->get('draft'))[5];
            $updateclaim->date = ($req->session()->get('draft'))[6];
            $updateclaim->date_created = date("Y-m-d", strtotime(($req->session()->get('draft'))[5]));
            $updateclaim->date_expiry = ($req->session()->get('draft'))[1];
            $updateclaim->total_hour = 0;
            $updateclaim->total_minute = 0;
            $updateclaim->amount = 0;
            $updateclaim->approver_id = $req->user()->reptto;
            $updateclaim->verifier_id =  $req->user()->id; //temp
            $updateclaim->status = 'Draft (Incomplete)';
        }else{
            $claimdetail = OvertimeDetail::where('ot_id', $req->inputid)->get();
            $updateclaim = Overtime::find($req->inputid);
            if(($req->chargetype!=null)&&($req->inputremark!=null)&&(count($claimdetail)!=0)){
                if(($claim->status=="Query (Incomplete)")||($claim->status=="Query (Complete)")){
                    $updateclaim->status = 'Query (Complete)';
                }else{
                    $updateclaim->status = 'Draft (Complete)';
                }
            }else{
                if($claim->status=="Query (Complete)"){
                    $updateclaim->status = 'Query (Incomplete)';
                }else{
                    $updateclaim->status = 'Draft (Incomplete)';
                }
            }
        }
        $updateclaim->charge_type = $req->chargetype;
        $updateclaim->justification = $req->inputremark;
        $updateclaim->save();
        if(empty($claim)){
            $claim = Overtime::where('user_id', $req->user()->id)->where('date', ($req->session()->get('draft'))[6])->first();
            $execute = UserHelper::LogOT($claim->id, $req->user()->id, "Created draft ".$claim->refno);    
        }else{
            $claim = Overtime::where('id', $req->inputid)->first();
        }
        Session::put(['draft' => [], 'claim' => $claim]);
        return redirect(route('ot.form',[],false));
    }
    
    public function submit(Request $req){
        if($req->multi=="yes"){
            $id = explode(" ", $req->submitid);
        }else{
            $id[0] = $req->inputid; 
        }
        for($i = 0; $i<count($id); $i++){
            $updateclaim = Overtime::find($id[$i]);
            $execute = UserHelper::LogOT($id[$i], $req->user()->id, "Submitted ".$updateclaim->refno);   
            if($updateclaim->verifier_id==null){
                $updateclaim->status = 'Pending Approval';
            }else{
                $updateclaim->status = 'Pending Verification';
            }
            $updateclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));
            $updateclaim->save();
        }
        return redirect(route('ot.list',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Successfully submitted claim!",
            'feedback_type' => "success"
        ]);
    }

    public function update(Request $req){
        $claim = Overtime::where('id', $req->inputid)->first();
        Session::put(['draft' => [], 'claim' => $claim]);
        return redirect(route('ot.form',[],false));
    }

    public function remove(Request $req){
        $claim = Overtime::where('id', $req->delid)->first();
        $claimtime = OvertimeMonth::where('id', $claim->month_id)->first();
        $updatemonth = OvertimeMonth::find($claim->month_id);
        $updatemonth->hour = ((int)((($claimtime->hour*60+$claimtime->minute)-($claim->total_hour*60+$claim->total_minute))/60));
        $updatemonth->minute = ((($claimtime->hour*60+$claimtime->minute)-($claim->total_hour*60+$claim->total_minute))%60);
        $updatemonth->save();
        OvertimeLog::where('ot_id',$req->delid)->delete();
        OvertimeDetail::where('ot_id',$req->delid)->delete();
        Overtime::find($req->delid)->delete();
        Session::put(['draft' => [], 'claim' => [], 'draftform' => []]);
        return redirect(route('ot.list',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Successfully deleted claim ".$claim->refno,
            'feedback_type' => "warning"
        ]);
    }

    public function formnew(Request $req){
        Session::put(['draft' => [], 'claim' => []]);
        return redirect(route('ot.form',[],false));
    }

    public function formdate(Request $req){
        Session::put(['draft' => [], 'draftform' => []]);  
        $claim = Overtime::where('user_id', $req->user()->id)->where('date', $req->inputdate)->first();
        if(empty($claim)){
            $claimdate = $req->inputdate;
            $claimmonth = date("m", strtotime($claimdate));
            $claimyear = date("y", strtotime($claimdate));
            $claimday = date("l", strtotime($claimdate));
            $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->first();
            if(empty($claimtime)){
                $newmonth = new OvertimeMonth;
                $newmonth->user_id = $req->user()->id;
                $newmonth->year = $claimyear;
                $newmonth->month = $claimmonth;
                $newmonth->save();
                $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->first();
            }
            $punch = OvertimePunch::where('user_id', $req->user()->id)->where('date', $req->inputdate)->get();
            if(count($punch)!=0){
                $totalhour = 0;
                $totalminute = 0;
                $draftclaim = new Overtime;
                $draftclaim->refno = "OT".date("ymd", strtotime($claimdate))."-".sprintf("%08d", $req->user()->id)."-".rand(10000,99999);
                $draftclaim->user_id = $req->user()->id;
                $draftclaim->month_id = $claimtime->id;
                $draftclaim->date = $req->inputdate;
                $draftclaim->date_created = date("Y-m-d");
                $draftclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));
                $draftclaim->approver_id = $req->user()->reptto;
                $draftclaim->verifier_id =  $req->user()->id; //temp
                $draftclaim->save();
                $claim = Overtime::where('user_id', $req->user()->id)->where('date', $req->inputdate)->first();
                foreach($punch as $punches){
                    $newclaim = new OvertimeDetail;
                    $newclaim->ot_id = $claim->id;
                    $newclaim->clock_in = $punches->start_time;
                    $newclaim->clock_out= $punches->end_time;
                    $newclaim->start_time = $punches->start_time;
                    $newclaim->end_time = $punches->end_time;
                    $newclaim->hour = $punches->hour;
                    $newclaim->minute = $punches->minute;
                    $newclaim->checked = "X";
                    $pay = UserHelper::CalOT($req->user()->salary, $punches->hour, $punches->minute); 
                    $newclaim->amount = $pay;
                    $newclaim->justification = "Punch In/Out";
                    $updatemonth = OvertimeMonth::find($claimtime->id);
                    $totaltime = (($updatemonth->hour*60)+$updatemonth->minute)+(($punches->hour*60)+$punches->minute);
                    $updatemonth->hour = (int)($totaltime/60);
                    $updatemonth->minute = ($totaltime%60);
                    $updateclaim = Overtime::find($claim->id);
                    $totaltime = (($updateclaim->total_hour*60)+$updateclaim->total_minute)+(($punches->hour*60)+$punches->minute);
                    $updateclaim->total_hour = (int)($totaltime/60);
                    $updateclaim->total_minute = ($totaltime%60);
                    $updateclaim->amount = $updateclaim->amount + $pay;
                    $newclaim->save();
                    $updatemonth->save();
                    $updateclaim->save();
                }
                $execute = UserHelper::LogOT($claim->id, $req->user()->id, "Created draft for ".$claim->refno);
                $claim = Overtime::where('user_id', $req->user()->id)->where('date', $req->inputdate)->first();
                Session::put(['draft' => []]);
            }else{
                $verify = User::where('id', $req->user()->id)->first();
                $approve = User::where('id', $req->user()->reptto)->first();
                $draft = array("OT".date("ymd", strtotime($claimdate))."-".sprintf("%08d", $req->user()->id)."-".rand(10000,99999), date('Y-m-d', strtotime("+90 days")), $verify->name, $approve->name, date("Y-m-d H:i:s"), $claimtime, $req->inputdate, $req->user()->name);
                //[0] - refno, [1] - expiry, [2] - verifier name, [3] - approver name, [4] - datetime created, [5] - month, [6] - date, [7] - user name
                Session::put(['draft' => $draft]); 
            }
        }else{
            Session::put(['draft' => []]);
        }
        Session::put(['claim' => $claim]);
        return redirect(route('ot.form',[],false));
    }
    
    public function formsubmit(Request $req){
        $claim = Overtime::where('id', $req->inputid)->first();
        if($req->formnew=="new"){
            if(($req->inputstartnew!="")&&($req->inputendnew!="")&&($req->inputremarknew!="")){
                $dif = (strtotime($req->inputendnew) - strtotime($req->inputstartnew))/60;
                $hour = (int) ($dif/60);
                $minute = $dif%60;
                $newdetail = new OvertimeDetail;
                $newdetail->ot_id = $req->inputid;
                $newdetail->start_time = $claim->date." ".$req->inputstartnew.":00";
                $newdetail->end_time = $claim->date." ".$req->inputendnew.":00";
                $newdetail->hour = $hour;
                $newdetail->minute = $minute;
                $newdetail->checked = "X";
                $pay = UserHelper::CalOT($req->user()->salary, $hour, $minute); 
                $newdetail->amount = $pay;
                $newdetail->justification = $req->inputremarknew;
                $updatemonth = OvertimeMonth::find($claim->month_id);
                $totaltime = (($updatemonth->hour*60)+$updatemonth->minute)+(($hour*60)+$minute);
                $updatemonth->hour = (int)($totaltime/60);
                $updatemonth->minute = ($totaltime%60);
                $updateclaim = Overtime::find($req->inputid);
                $totaltime = (($updateclaim->total_hour*60)+$updateclaim->total_minute)+(($hour*60)+$minute);
                $updateclaim->total_hour = (int)($totaltime/60);
                $updateclaim->total_minute = ($totaltime%60);
                $updateclaim->amount = $updateclaim->amount + $pay;
                $newdetail->save();
                $updatemonth->save();
                $updateclaim->save();
            }else{
                $draftform = [$req->inputstartnew, $req->inputendnew, $req->inputremarknew];
                Session::put(['draftform' => $draftform]);
            }
        }
        $claim = Overtime::where('id', $req->inputid)->first();
        Session::put(['claim' => $claim]);
        if($req->formadd){ //if add only
            return redirect(route('ot.form',[],false))->with([
                'feedback' => true,
                'feedback_text' => "Successfully added a new time!",
                'feedback_type' => "success"
            ]);
        }
    }

    public function formadd(Request $req){
        if($req->inputclock!="na"){
            $time = explode("/", $req->inputclock);
            $req->inputstart = date("H:i", strtotime($time[0]));
            $req->inputend = date("H:i", strtotime($time[1]));
            $req->inputremark = "Punch In/Out";
            // dd($time);
        }
        $dif = (strtotime($req->inputend) - strtotime($req->inputstart))/60;
        $hour = (int) ($dif/60);
        $minute = $dif%60;
        $dayclaim = Overtime::where('id', $req->inputid)->first();
        if(empty($dayclaim)){
            $draftclaim = new Overtime;
            $draftclaim->refno = ($req->session()->get('draft'))[0];
            $draftclaim->user_id = $req->user()->id;
            $draftclaim->month_id = ($req->session()->get('draft'))[5];
            $draftclaim->date = ($req->session()->get('draft'))[6];
            $draftclaim->date_created = date("Y-m-d", strtotime(($req->session()->get('draft'))[4]));
            $draftclaim->date_expiry = ($req->session()->get('draft'))[1];
            $draftclaim->total_hour = 0;
            $draftclaim->total_minute = 0;
            $draftclaim->amount = 0;
            $draftclaim->approver_id = $req->user()->reptto;
            $draftclaim->verifier_id =  $req->user()->id; //temp
            $draftclaim->status = 'Draft (Incomplete)';
            $draftclaim->save();
            $dayclaim = Overtime::where('user_id', $req->user()->id)->where('date', ($req->session()->get('draft'))[6])->first();
            $execute = UserHelper::LogOT($dayclaim->id, $req->user()->id, "Created draft ".$dayclaim->refno);    
            Session::put(['draft' => []]);
        }
        if($req->edit=="edit"){
            $availableclaim = OvertimeDetail::where('id', 'not like', $req->editid)->where('ot_id',$dayclaim->id)->where('start_time', '<', $dayclaim->date." ".$req->inputend.":00" )->where('end_time', '>', $dayclaim->date." ".$req->inputstart.":00" )->get();
        }else{
            $availableclaim = OvertimeDetail::where('ot_id', $dayclaim->id)->where('start_time', '<', $dayclaim->date." ".$req->inputend.":00" )->where('end_time', '>', $dayclaim->date." ".$req->inputstart.":00" )->get();
        }
        if(count($availableclaim)!=0){
            return redirect(route('ot.form',[],false))->with([
                'feedback' => true,
                'feedback_text' => "There is already a duplicate with your time range input!",
                'feedback_type' => "danger"
            ]);
            exit();
        }
        $claimtime = OvertimeMonth::where('id', $dayclaim->month_id)->first();
        if($req->edit=="edit"){
            $oldtime = OvertimeDetail::where('id', $req->editid)->first();
            $totalleft=(($claimtime->hour*60)+$claimtime->minute)+(($oldtime->hour*60)+$oldtime->minute);
            $totaltime=(($dayclaim->total_hour*60)+$dayclaim->total_minute)-(($oldtime->hour*60)+$oldtime->minute);
        }else{
            $totalleft=($claimtime->hour*60)+$claimtime->minute;
            $totaltime=($dayclaim->total_hour*60)+$dayclaim->total_minute;
        }
        if($totalleft>=$dif){
            if($req->edit=="edit"){
                $newclaim = OvertimeDetail::find($req->editid);
            }else{
                $newclaim = new OvertimeDetail;
                $newclaim->ot_id = $dayclaim->id;
            }
            if($req->inputclock!="na"){
                $newclaim->clock_in = $time[0];
                $newclaim->clock_out = $time[1];
            }else{
                $newclaim->clock_in = null;
                $newclaim->clock_out = null;
            }
            $newclaim->start_time = $dayclaim->date." ".$req->inputstart.":00";
            $newclaim->end_time = $dayclaim->date." ".$req->inputend.":00";
            $newclaim->hour = $hour;
            $newclaim->minute = $minute;
            $newclaim->justification = $req->inputremark;
            $updateclaim = Overtime::find($dayclaim->id);
            $updateclaim->total_hour = ((int)(($totaltime+$dif)/60));
            $updateclaim->total_minute = (($totaltime+$dif)%60);
            $pay = UserHelper::CalOT($req->user()->salary, ((int)(($totaltime+$dif)/60)), (($totaltime+$dif)%60));  
            $updateclaim->amount = $updateclaim->amount + $pay;
            $updatemonth = OvertimeMonth::find($claimtime->id);
            $updatemonth->hour = ((int)(($totalleft-$dif)/60));
            $updatemonth->minute = (($totalleft-$dif)%60);
            if(($req->claimcharge!=null)&&($req->claimremark!=null)){
                if(($dayclaim->status=="Query (Incomplete)")||($dayclaim->status=="Query (Complete)")){
                    $updateclaim->status = 'Query (Complete)';
                }else{
                    $updateclaim->status = 'Draft (Complete)';
                }
            }
            $newclaim->save();
            $updateclaim->save();
            $updatemonth->save();
            $claim = Overtime::where('id', $dayclaim->id)->first();
            Session::put(['claim' => $claim]);
            if($req->edit=="edit"){
                return redirect(route('ot.form',[],false))->with([
                    'feedback' => true,
                    'feedback_text' => "Successfully edited time!",
                    'feedback_type' => "success"
                ]);
            }else{    
                return redirect(route('ot.form',[],false))->with([
                    'feedback' => true,
                    'feedback_text' => "Successfully added a new time!",
                    'feedback_type' => "success"
                ]);
            }
        }else{
            return redirect(route('ot.form',[],false))->with([
                'feedback' => true,
                'feedback_text' => "Available time left to claim is not enough!",
                'feedback_type' => "danger"
            ]);
        }
    }

    public function formdelete(Request $req){
        $dif = (strtotime($req->inputend) - strtotime($req->inputstart))/60;
        $hour = (int) ($dif/60);
        $minute = $dif%60;
        $dayclaim = Overtime::where('id', $req->inputid)->first();
        $claimtime = OvertimeMonth::where('id', $dayclaim->month_id)->first();
        $totalleft=($claimtime->hour*60)+$claimtime->minute;
        $totaltime=($dayclaim->total_hour*60)+$dayclaim->total_minute;
        $updateclaim = Overtime::find($req->inputid);
        $updateclaim->total_hour = ((int)(($totaltime-$dif)/60));
        $updateclaim->total_minute = (($totaltime-$dif)%60);
        $updatemonth = OvertimeMonth::find($claimtime->id);
        $updatemonth->hour = ((int)(($totalleft+$dif)/60));
        $updatemonth->minute = (($totalleft+$dif)%60);
        OvertimeDetail::find($req->delid)->delete();
        $claimdetail = OvertimeDetail::where('ot_id', $req->inputid)->get();
        if(count($claimdetail)==0){
            $updateclaim->status = 'Draft (Incomplete)';
        }
        $updateclaim->save();
        $updatemonth->save();
        $claim = Overtime::where('id', $req->inputid)->first();
        Session::put(['claim' => $claim]);
        return redirect(route('ot.form',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Successfully deleted time ".$req->inputstart."-".$req->inputend.".",
            'feedback_type' => "warning"
        ]);
    }

    public function approval(Request $req){
        $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', 'PV')->orWhere('approver_id', $req->user()->id)->where('status', 'PA')->orderBy('date_expiry')->orderBy('date')->get();
        return view('staff.otquery', ['otlist' => $otlist]);
    }

    public function query (Request $req){
        $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', 'Pending Verification')->orWhere('approver_id', $req->user()->id)->where('status', 'Pending Approval')->orderBy('date_expiry')->orderBy('date')->get();
        for($i=0; $i<count($otlist); $i++){
            if($req->inputaction[$i]!=""){
                $updateclaim = Overtime::find($req->inputid[$i]);
                $updateclaim->status=$req->inputaction[$i];
                if($req->inputaction[$i]=="PA"){
                    $updateclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));
                    $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Verified ("'.$req->inputremark[$i].'")');  
                }else if($req->inputaction[$i]=="A"){
                    $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Approved ("'.$req->inputremark[$i].'")');  
                }else if($req->inputaction[$i]=="Q2"){
                    $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Queried with message: "'.$req->inputremark[$i].'")');
                    $updateclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));  
                }
                $updateclaim->save();
            }
        }
        return redirect(route('ot.approval',[],false));
    }
}