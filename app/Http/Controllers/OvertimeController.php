<?php

namespace App\Http\Controllers;

use App\Shared\UserHelper;
use App\User;
use App\Overtime;
use App\OvertimeMonth;
use App\OvertimeDetail;
use App\OvertimeLog;
use Session;
use Illuminate\Http\Request;

class OvertimeController extends Controller{
    public function list(Request $req){
        $otlist = Overtime::where('user_id', $req->user()->id)->orderBy('status')->orderBy('date_expiry')->orderBy('date')->get();
        return view('staff.overtime', ['otlist' => $otlist]);
    }

    public function form(Request $req){
        if($req->session()->get('claim')!=null){
            $otlist = OvertimeDetail::where('ot_id', $req->session()->get('claim')->id)->get();
            $claimtime = OvertimeMonth::where('id', $req->session()->get('claim')->month_id)->first();
            $otlog = OvertimeLog::where('ot_id', $req->session()->get('claim')->id)->orderBy('created_at', 'desc')->get();
            $day = UserHelper::CheckDay($req->user()->id, $req->session()->get('claim')->date);
            return view('staff.otform', ['draft' =>[], 'claim' => $req->session()->get('claim'), 'claimtime' => $claimtime, 'otlist' => $otlist, 'otlog' => $otlog, 'dt' => $day]);
        }else if($req->session()->get('draft')!=null){
            $draftt = $req->session()->get('draft');
            $claimtime = OvertimeMonth::where('id', $draftt[8])->first();
            $day = UserHelper::CheckDay($req->user()->id, date('Y-m-d', strtotime($draftt[9])));
            return view('staff.otform', ['draft' => $req->session()->get('draft'), 'claimtime'=>$claimtime, 'dt' => $day]);
        }else{
            return view('staff.otform', []);
        }
    }

    public function save(Request $req){
        $claim = OvertimeDetail::where('ot_id', $req->inputid)->get();
        $updateclaim = Overtime::find($req->inputid);
        $updateclaim->charge_type = $req->chargetype;
        $updateclaim->justification = $req->inputremark;
        if(($req->chargetype!=null)&&($req->inputremark!=null)&&(count($claim)!=0)){
            $updateclaim->status = 'Draft (Complete)';
        }else{
            $updateclaim->status = 'Draft (Incomplete)';
        }
        $updateclaim->save();
        $claim = Overtime::where('id', $req->inputid)->first();
        Session::put(['claim' => $claim]);
        return redirect(route('ot.form',[],false));
    }
    
    public function store(Request $req){
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
        Session::put(['claim' => $claim]);
        return redirect(route('ot.form',[],false));
    }

    public function remove(Request $req){
        $output = "";
        if($req->multi=="yes"){
            $id = explode(" ", $req->deleteid);
        }else{
            $id[0] = $req->delid; 
        }
        for($i = 0; $i<count($id); $i++){
            $claim = Overtime::where('id', $id[$i])->first();
            $claimtime = OvertimeMonth::where('id', $claim->month_id)->first();
            $updatemonth = OvertimeMonth::find($claim->month_id);
            $updatemonth->hour = ((int)((($claim->total_hour*60+$claim->total_minute)+($claimtime->hour*60+$claimtime->minute))/60));
            $updatemonth->minute = ((($claim->total_hour*60+$claim->total_minute)+($claimtime->hour*60+$claimtime->minute))%60);
            $updatemonth->save();
            Overtime::find($id[$i])->delete();
            OvertimeLog::where('ot_id',$id[$i])->delete();
            if($i==(count($id)-1)){
                $output = $output.$claim->refno.".";
            }else{
                $output = $output.$claim->refno.", ";
            }
        }
        Session::put(['draft' => [], 'claim' => [], 'claimtime' => [], 'otlist' => []]);
        return redirect(route('ot.list',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Successfully deleted claim ".$output,
            'feedback_type' => "warning"
        ]);
    }

    public function formnew(Request $req){
        Session::put(['draft' => [], 'claim' => [], 'claimtime' => [], 'otlist' => []]);
        return redirect(route('ot.form',[],false));
    }

    public function formdate(Request $req){
        Session::put(['draft' => []]);
        $claim = Overtime::where('user_id', $req->user()->id)->where('date', $req->inputdate)->first();
        if(empty($claim)){
            $claimdate = $req->inputdate;
            $claimmonth = date("m", strtotime($claimdate));
            $claimyear = date("y", strtotime($claimdate));
            $claimday = date("l", strtotime($claimdate));
            $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->first();
            if(empty($claimtime)){
                $newmonth = new OvertimeMonth;
                $newmonth->hour = 104;
                $newmonth->minute = 0;
                $newmonth->user_id = $req->user()->id;
                $newmonth->year = $claimyear;
                $newmonth->month = $claimmonth;
                $newmonth->save();
                $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', $claimyear)->where('month', $claimmonth)->first();
            }
            $refno = "OT".date("ymd", strtotime($claimdate))."-".sprintf("%08d", $req->user()->id)."-".rand(10000,99999);
            $verify = User::where('id', $req->user()->id)->first();
            $approve = User::where('id', $req->user()->reptto)->first();
            $draft = array($refno, date('Y-m-d', strtotime("+90 days")), "Draft (Incomplete)", $verify->name, $approve->name, "0.00", date("Y-m-d H:i:s"), "Created draft for ".$refno, $claimtime->id, $req->inputdate);
            Session::put(['draft' => $draft]);
            // dd($claim);
            // $newclaim = new Overtime;
            // $newclaim->refno = "OT".date("ymd", strtotime($claimdate))."-".sprintf("%08d", $req->user()->id)."-".rand(10000,99999);
            // $newclaim->user_id = $req->user()->id;
            // $newclaim->month_id = $claimtime->id;
            // $newclaim->date = $claimdate;   
            // $newclaim->date_created = date("Y-m-d");
            // $newclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));;
            // $newclaim->total_hour = 0;
            // $newclaim->total_minute = 0;
            // $newclaim->amount = 0;
            // $newclaim->status = 'Draft (Incomplete)';
            // $newclaim->approver_id = $req->user()->reptto; //temp
            // $newclaim->verifier_id =  $req->user()->id; //temp
            // $newclaim->charge_type = '';
            // $newclaim->save();   
            // $claim = Overtime::where('user_id', $req->user()->id)->where('date', $claimdate)->first();
            // $execute = UserHelper::LogOT($claim->id, $req->user()->id, "Created ".$claim->refno);        
        }else{
            Session::put(['draft' => []]);
        }
        Session::put(['claim' => $claim]);
        return redirect(route('ot.form',[],false));
    }
    
    public function formadd(Request $req){
        $dif = (strtotime($req->inputend) - strtotime($req->inputstart))/60;
        $hour = (int) ($dif/60);
        $minute = $dif%60;
        $dayclaim = Overtime::where('id', $req->inputid)->first();
        if($req->edit=="edit"){
            $availableclaim = OvertimeDetail::where('id', 'not like', $req->editid)->where('ot_id', $req->inputid)->where('start_time', '<', $dayclaim->date." ".$req->inputend.":00" )->where('end_time', '>', $dayclaim->date." ".$req->inputstart.":00" )->get();
        }else{
            $availableclaim = OvertimeDetail::where('ot_id', $req->inputid)->where('start_time', '<', $dayclaim->date." ".$req->inputend.":00" )->where('end_time', '>', $dayclaim->date." ".$req->inputstart.":00" )->get();
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
                $newclaim->ot_id = $req->inputid;
            }
            $newclaim->start_time = $dayclaim->date." ".$req->inputstart.":00";
            $newclaim->end_time = $dayclaim->date." ".$req->inputend.":00";
            $newclaim->hour = $hour;
            $newclaim->minute = $minute;
            $newclaim->justification = $req->inputremark;
            $updateclaim = Overtime::find($req->inputid);
            $updateclaim->total_hour = ((int)(($totaltime+$dif)/60));
            $updateclaim->total_minute = (($totaltime+$dif)%60);
            $pay = UserHelper::CalOT($req->user()->salary, ((int)(($totaltime+$dif)/60)), (($totaltime+$dif)%60));  
            $updateclaim->amount = $pay;
            $updatemonth = OvertimeMonth::find($claimtime->id);
            $updatemonth->hour = ((int)(($totalleft-$dif)/60));
            $updatemonth->minute = (($totalleft-$dif)%60);
            if(($req->claimcharge!=null)&&($req->claimremark!=null)){
                $updateclaim->status = 'Draft (Complete)';
            }
            $newclaim->save();
            $updateclaim->save();
            $updatemonth->save();
            $claim = Overtime::where('id', $req->inputid)->first();
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
            return redirect(route('ot.form ',[],false))->with([
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
        $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', 'Pending Verification')->orWhere('approver_id', $req->user()->id)->where('status', 'Pending Approval')->orderBy('date_expiry')->orderBy('date')->get();
        return view('staff.otquery', ['otlist' => $otlist]);
    }

    public function query (Request $req){
        $otlist = Overtime::where('verifier_id', $req->user()->id)->where('status', 'Pending Verification')->orWhere('approver_id', $req->user()->id)->where('status', 'Pending Approval')->orderBy('date_expiry')->orderBy('date')->get();
        for($i=0; $i<count($otlist); $i++){
            if($req->inputaction[$i]!=""){
                $updateclaim = Overtime::find($req->inputid[$i]);
                $updateclaim->status=$req->inputaction[$i];
                if($req->inputaction[$i]=="Pending Approval"){
                    $updateclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));
                    $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Verified ("'.$req->inputremark[$i].'")');  
                }else if($req->inputaction[$i]=="Approved"){
                    $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Approved ("'.$req->inputremark[$i].'")');  
                }else if($req->inputaction[$i]=="Query (Complete)"){
                    $execute = UserHelper::LogOT($req->inputid[$i], $req->user()->id, 'Query ("'.$req->inputremark[$i].'")');
                    $updateclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));  
                }
                $updateclaim->save();
            }
        }
        return redirect(route('ot.approval',[],false));
    }

    public function test(Request $req){
        dd("lol");
    }
}