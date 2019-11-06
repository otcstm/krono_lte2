<?php

namespace App\Http\Controllers;

use App\Shared\TimeHelper;
use App\Overtime;
use App\OvertimeMonth;
use App\OvertimeDetail;
use Session;
use Illuminate\Http\Request;

class OvertimeController extends Controller{
    public function showOT(Request $req){
        $otlist = Overtime::where('user_id', $req->user()->id)->get();
        return view('staff.overtime', ['otlist' => $otlist]);
    }

    public function showDetails(Request $req){
        if($req->session()->has('claim')) {
            $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->
            where('year', date("y", strtotime($req->session()->get('claimdate'))))->where('month', date("m", strtotime($req->session()->get('claimdate'))))->first();
            $otlist = OvertimeDetail::where('ot_id', $req->session()->get('claim')->id)->orderBy('start_time')->get();
            return view('staff.otdetails', ['claimtime' => $claimtime, 'claimdate' => $req->session()->get('claimdate'), 'claimday' => $req->session()->get('claimday'), 'claim' => $req->session()->get('claim'), 'otlist' =>  $otlist]);
          }else{
            return redirect(route('ot.showOT',[],false));
          }
    }

    public function create(Request $req){
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
        }
        $claim = Overtime::where('user_id', $req->user()->id)->where('date', $claimdate)->first();
        if(empty($claim)){
            $ref = "OT".date("ymd", strtotime($claimdate))."-".sprintf("%08d", $req->user()->id)."-".rand(10000,99999);
            $newclaim = new Overtime;
            $newclaim->refno = $ref;
            $newclaim->user_id = $req->user()->id;
            $newclaim->date = $claimdate;
            $newclaim->date_created = date("Y-m-d");
            $newclaim->date_expiry = date('Y-m-d', strtotime("+90 days"));;
            $newclaim->total_hour = 0;
            $newclaim->total_minute = 0;
            $newclaim->status = 'Draft';
            $newclaim->save();
            $claim = Overtime::where('user_id', $req->user()->id)->where('date', $claimdate)->first();
        }
        Session::put(['claimdate' => $claimdate, 'claimday' => $claimday, 'claim' => $claim]);
        return redirect(route('ot.showDetails',[],false));
    }

    public function time(Request $req){
        $dif = (strtotime($req->inputend) - strtotime($req->inputstart))/60;
        $hour = (int) ($dif/60);
        $minute = $dif%60;
        if($req->edit=="edit"){
            $availableclaim = OvertimeDetail::where('id', 'not like', $req->editid)->where('ot_id', $req->inputid)->where('start_time', '<', $req->inputdate." ".$req->inputend.":00" )->where('end_time', '>', $req->inputdate." ".$req->inputstart.":00" )->get();
        }else{
            $availableclaim = OvertimeDetail::where('ot_id', $req->inputid)->where('start_time', '<', $req->inputdate." ".$req->inputend.":00" )->where('end_time', '>', $req->inputdate." ".$req->inputstart.":00" )->get();
        }
        if(count($availableclaim)!=0){
            return redirect(route('ot.showDetails',[],false))->with([
                'feedback' => true,
                'feedback_text' => "There is already a duplicate with your time range input!",
                'feedback_icon' => "remove",
                'feedback_color' => "#D9534F"]
            );
            exit();
        }
        $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', date("y", strtotime($req->inputdate)))->where('month', date("m", strtotime($req->inputdate)))->first();
        $dayclaim = Overtime::where('id', $req->inputid)->first();
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
            $newclaim->start_time = $req->inputdate." ".$req->inputstart.":00";
            $newclaim->end_time = $req->inputdate." ".$req->inputend.":00";
            $newclaim->hour = $hour;
            $newclaim->minute = $minute;
            $newclaim->justification = $req->inputremark;
            $updateclaim = Overtime::find($req->inputid);
            $updateclaim->total_hour = ((int)(($totaltime+$dif)/60));
            $updateclaim->total_minute = (($totaltime+$dif)%60);
            $updatemonth = OvertimeMonth::find($claimtime->id);
            $updatemonth->hour = ((int)(($totalleft-$dif)/60));
            $updatemonth->minute = (($totalleft-$dif)%60);
            $newclaim->save();
            $updateclaim->save();
            $updatemonth->save();
            return redirect(route('ot.showDetails',[],false));
        }else{
            return redirect(route('ot.showDetails',[],false))->with([
                'feedback' => true,
                'feedback_text' => "Available time left to claim is not enough!",
                'feedback_icon' => "remove",
                'feedback_color' => "#D9534F"]
            );
        }
    }

    public function deltime(Request $req){
        $dif = (strtotime($req->inputend) - strtotime($req->inputstart))/60;
        $hour = (int) ($dif/60);
        $minute = $dif%60;
        $claimtime = OvertimeMonth::where('user_id', $req->user()->id)->where('year', date("y", strtotime($req->inputdate)))->where('month', date("m", strtotime($req->inputdate)))->first();
        $dayclaim = Overtime::where('id', $req->inputid)->first();
        $totalleft=($claimtime->hour*60)+$claimtime->minute;
        $totaltime=($dayclaim->total_hour*60)+$dayclaim->total_minute;
        $updateclaim = Overtime::find($req->inputid);
        $updateclaim->total_hour = ((int)(($totaltime-$dif)/60));
        $updateclaim->total_minute = (($totaltime-$dif)%60);
        $updatemonth = OvertimeMonth::find($claimtime->id);
        $updatemonth->hour = ((int)(($totalleft+$dif)/60));
        $updatemonth->minute = (($totalleft+$dif)%60);
        $updateclaim->save();
        $updatemonth->save();
        OvertimeDetail::find($req->delid)->delete();
        return redirect(route('ot.showDetails',[],false));
    }

    public function store(Request $req){
        $updateclaim = Overtime::find($req->inputid);
        $updateclaim->status = "Submitted";
        $updateclaim->justification = $req->inputremark;
        $updateclaim->save();
        return redirect(route('ot.showOT',[],false));
    }

}
