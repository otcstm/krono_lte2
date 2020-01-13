<?php

namespace App\Http\Controllers\Admin;

use App\OvertimeConfig;
use App\OvertimeExpiry;
use App\Company;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Session;

class OvertimeMgmtController extends Controller
{

    public function show(Request $req){  
        // dd($req->session()->get('type'));
        if($req->session()->get('type')!=null){
            $req->formtype = $req->session()->get('type');
            $req->inputregion = $req->session()->get('region');
            $req->inputcompany = $req->session()->get('company');
        }
        if($req->formtype==""){
            $oe = OvertimeConfig::all();     
            return view('admin.otmgmt', ['oe' => $oe]);
        }else if($req->formtype=="eligibility"){
            $oe = OvertimeConfig::where('company_id', $req->inputcompany)->where('region', $req->inputregion)->get();  
            // dd($oe);
            return view('admin.otmgmteligibility', ['oe' => $oe]);
        }else if($req->formtype=="expiry"){
            // dd($req->inputcompany);
            $oe = OvertimeExpiry::where('company_id', $req->inputcompany)->where('region', $req->inputregion)->get();  
            // dd($oe);
            return view('admin.otmgmtexpiry', ['oe' => $oe]);
        }
    }

    public function otm(){  
            Session::put(['type'=>[], 'region'=>[], 'company'=>[]]);
             
        // dd($req->session()->get('region'));
            return redirect(route('oe.show',[],false));
        }

    public function getCompany(Request $req){   
        $comp = OvertimeConfig::where('region', $req->region)->get();  
        $arr = [];
        foreach($comp as $c){
            array_push($arr, ['id'=>$c->company_id, 'name'=>$c->companyid->company_descr]);
        }
        return $arr;
    }

    public function getLast(Request $req){   
        $end = OvertimeConfig::where('company_id', $req->company)->where('region', $req->region)->where('end_date', $req->sd)->first();  
        $start = OvertimeConfig::where('company_id', $req->company)->where('region', $req->region)->where('start_date', $req->sd)->first();  
        // dd($comp->start_date);
        return ['min' => date('Y-m-d', strtotime($end->start_date . '+1 days')), 'max' => date('Y-m-d', strtotime($start->end_date . '-1 days'))];
    }

    public function eligiblestore(Request $req){
        $latest = OvertimeConfig::where('company_id', $req->inputcompany)->where('region', $req->inputregion)->latest('created_at')->first();
        $update = new OvertimeConfig;
        $old = OvertimeConfig::find($latest->id);
        $old->end_date = $req->inputdate;
        $update->company_id = $req->inputcompany;
        $update->region = $req->inputregion;
        $update->salary_cap = $req->inputsalary;
        $update->hourpermonth = $req->inputhourpm;
        $update->hourperday = $req->inputhourpd;
        $update->daypermonth = $req->inputdaypm;
        $update->start_date = $req->inputdate;
        $update->end_date = '9999-12-31';
        $update->created_by = $req->user()->id;
        $update->save();
        $old->save();
        Session::put(['region'=>$req->inputregion, 'company'=>$req->inputcompany, 'type'=>$req->formtype]);
        return redirect(route('oe.show',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Successfully created a new configuration!",
            'feedback_type' => "success"
        ]);
    }

    public function eligibleupdate(Request $req){
        $date = OvertimeConfig::where('id',$req->inputid)->first();
        $update = OvertimeConfig::find($req->inputid);
        $update->salary_cap = $req->inputesalary;
        $update->hourpermonth = $req->inputehourpm;
        $update->hourperday = $req->inputehourpd;
        $update->daypermonth = $req->inputedaypm;
        $update->start_date = $req->inputedate;
        $old = OvertimeConfig::where('end_date', $date->start_date)->first();
        $old->end_date = $req->inputedate;
        $update->save();
        $old->save();
        Session::put(['region'=>$req->inputregion, 'company'=>$req->inputcompany, 'type'=>$req->formtype]);
        return redirect(route('oe.show',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Successfully updated configuration!",
            'feedback_type' => "success"
        ]);
    }

    public function eligibledelete(Request $req){
        $date = OvertimeConfig::where('id',$req->inputid)->first();
        $delete = OvertimeConfig::find($req->inputid)->delete();
        $old = OvertimeConfig::where('end_date', $date->start_date)->first();
        $old->end_date = '9999-12-31';
        $old->save();
        Session::put(['region'=>$req->inputregion, 'company'=>$req->inputcompany, 'type'=>$req->formtype]);
        return redirect(route('oe.show',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Successfully deleted configuration!",
            'feedback_type' => "warning"
        ]);
    }

    public function expirystore(Request $req){
        $latest = OvertimeExpiry::where('company_id', $req->inputcompany)->where('region', $req->inputregion)->where('otstatus', $req->inputstatus)->latest('created_at')->first();
        $update = new OvertimeExpiry;
        if($latest!=null){
            $old = OvertimeExpiry::find($latest->id);
            $old->end_date = $req->inputdate;
            $old->save();
        }
        $update->company_id = $req->inputcompany;
        $update->region = $req->inputregion;
        $update->otstatus = $req->inputstatus;
        $update->noofmonth = $req->inputmonth;
        $update->based_date = $req->inputbasedate;
        $update->action_after = $req->inputaction;
        $update->status = 'ACTIVE';
        $update->start_date = $req->inputdate;
        $update->end_date = '9999-12-31';
        $update->created_by = $req->user()->id;
        $update->save();
        Session::put(['region'=>$req->inputregion, 'company'=>$req->inputcompany, 'type'=>$req->formtype]);
        return redirect(route('oe.show',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Successfully created a new configuration!",
            'feedback_type' => "success"
        ]);
    }

    public function active(Request $req){
        $old = OvertimeExpiry::where('id',$req->inputid)->first();
        $update = OvertimeExpiry::find($req->inputid);
        if($old->status=="ACTIVE"){
            $update->status = "INACTIVE";
            $status = "deactivate";
            $feedback = "warning";
        }else{
            $update->status = "ACTIVE";
            $status = "activate";
            $feedback = "success";
        }
        $update->save();
        Session::put(['region'=>$req->inputregion, 'company'=>$req->inputcompany, 'type'=>$req->formtype]);
        return redirect(route('oe.show',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Successfully ".$status." configuration!",
            'feedback_type' => $feedback
        ]);
    }

    public function getExpiry(Request $req){   
        $dt = OvertimeExpiry::where('company_id', $req->company)->where('region', $req->region)->where('otstatus', $req->status)->where('end_date', '9999-12-31')->first();  
        if($dt!=null){
            return ['min' => date('Y-m-d', strtotime($dt->start_date . '+1 days')), 'mon' => $dt->noofmonth, 'bd' => $dt->based_date, 'aa' => $dt->action_after];
        }else{
            return ['min' => null];
        }
    }

    public function getLast2(Request $req){   
        $end = OvertimeExpiry::where('company_id', $req->company)->where('region', $req->region)->where('end_date', $req->sd)->first();  
        $start = OvertimeExpiry::where('company_id', $req->company)->where('region', $req->region)->where('start_date', $req->sd)->first();  
        // dd($comp->start_date);
        return ['min' => date('Y-m-d', strtotime($end->start_date . '+1 days')), 'max' => date('Y-m-d', strtotime($start->end_date . '-1 days'))];
    }

    public function expirydelete(Request $req){
        $date = OvertimeExpiry::where('id',$req->inputid)->first();
        $delete = OvertimeExpiry::find($req->inputid)->delete();
        $old = OvertimeExpiry::where('end_date', $date->start_date)->first();
        $old->end_date = '9999-12-31';
        $old->save();
        Session::put(['region'=>$req->inputregion, 'company'=>$req->inputcompany, 'type'=>$req->formtype]);
        return redirect(route('oe.show',[],false))->with([
            'feedback' => true,
            'feedback_text' => "Successfully deleted configuration!",
            'feedback_type' => "warning"
        ]);
    }

}
