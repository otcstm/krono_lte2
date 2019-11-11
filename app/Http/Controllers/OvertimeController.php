<?php

namespace App\Http\Controllers;

use App\Shared\TimeHelper;
use App\Overtime;
use App\OvertimeMonth;
use App\OvertimeDetail;
use Session;
use Illuminate\Http\Request;

class OvertimeController extends Controller{
    public function list(Request $req){
        $otlist = Overtime::where('user_id', $req->user()->id)->orderBy('status')->orderBy('date_expiry')->orderBy('date')->get();
        return view('staff.overtime', ['otlist' => $otlist]);
    }

    public function form(Request $req){
        if($req->session()->get('show')){
            $otlist = OvertimeDetail::where('ot_id', $req->session()->get('claim')->id)->get();
            $claimtime = OvertimeMonth::where('id', $req->session()->get('claim')->month_id)->first();
            return view('staff.otform', ['show' => $req->session()->get('show'), 'claim' => $req->session()->get('claim'), 'claimtime' => $claimtime, 'otlist' => $otlist]);
        }else{
            return view('staff.otform', []);
        }
    }

    public function store(Request $req){
        $updateclaim = Overtime::find($req->inputid);
        $updateclaim->status = "Submitted";
        $updateclaim->save();
        return redirect(route('ot.list',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Successfully added a new claim!",
            'feedback_type' => "success"
        ]);
    }

    public function submit(Request $req){
        $id = explode(" ", $req->submitid);
        for($i = 0; $i<count($id); $i++){
            $updateclaim = Overtime::find($id[$i]);
            $updateclaim->status = 'Submitted';
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
        Session::put(['show' => true, 'claim' => $claim]);
        return redirect(route('ot.form',[],false));
    }

    public function remove(Request $req){
        $claim = Overtime::where('id', $req->delid)->first();
        $claimtime = OvertimeMonth::where('id', $claim->month_id)->first();
        $updatemonth = OvertimeMonth::find($claim->month_id);
        $updatemonth->hour = ((int)((($claim->total_hour*60+$claim->total_minute)+($claimtime->hour*60+$claimtime->minute))/60));
        $updatemonth->minute = ((($claim->total_hour*60+$claim->total_minute)+($claimtime->hour*60+$claimtime->minute))%60);
        $updatemonth->save();
        Overtime::find($req->delid)->delete();
        Session::put(['show' => false]);
        return redirect(route('ot.list',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Successfully deleted claim ".$claim->refno.".",
            'feedback_type' => "warning"
        ]);
    }

    public function newform(Request $req){
        Session::put(['show' => null, 'claim' => [], 'claimtime' => [], 'otlist' => []]);
        return redirect(route('ot.form',[],false));
    }

    public function formdate(Request $req){
        $claim = Overtime::where('user_id', $req->user()->id)->where('date', $req->inputdate)->first();
        $show = true;
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
            $newclaim = new Overtime;
            $newclaim->refno = "OT".date("ymd", strtotime($claimdate))."-".sprintf("%08d", $req->user()->id)."-".rand(10000,99999);
            $newclaim->user_id = $req->user()->id;
            $newclaim->month_id = $claimtime->id;
            $newclaim->date = $claimdate;   
            $newclaim->date_created = date("Y-m-d");
            $newclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));;
            $newclaim->total_hour = 0;
            $newclaim->total_minute = 0;
            $newclaim->status = 'Draft (Incomplete)';
            $newclaim->approver_id = 55326; //temp
            $newclaim->verifier_id =  55326; //temp
            $newclaim->charge_type = '';
            $newclaim->save();           
            $claim = Overtime::where('user_id', $req->user()->id)->where('date', $claimdate)->first();
        }else{
            $claimtime = OvertimeMonth::where('id', $claim->month_id)->first();
        }
        Session::put(['show' => true, 'claim' => $claim]);
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
        if($req->save=="save"){
            return redirect(route('ot.form',[],false));
        }else{
            return redirect(route('ot.list',[],false)); 
        }
    }

    public function test(Request $req){
        dd("lol");
    }
}