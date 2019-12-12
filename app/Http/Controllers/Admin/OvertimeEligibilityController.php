<?php

namespace App\Http\Controllers;

use App\CompRegionConfig;
use App\Company;
use Illuminate\Http\Request;
use Session;

class OvertimeEligibilityController extends Controller
{

    public function show(Request $req){  
        // dd($req->session()->get('inputregion'));
        if($req->session()->get('region')!=null){
            $req->inputregion = $req->session()->get('region');
            $req->inputcompany = $req->session()->get('company');
        }
        if($req->inputregion==""){
            $oe = CompRegionConfig::all();     
            return view('admin.oteligibilitymain', ['oe' => $oe]);
        }else{
            $oe = CompRegionConfig::where('company_id', $req->inputcompany)->where('region', $req->inputregion)->get();  
            // dd($oe);
            return view('admin.oteligibilitysub', ['oe' => $oe]);
        }
    }

    public function otm(){   
            // Session::put(['inputregion'=>[], 'inputcompany'=>[]]);
            return redirect(route('oe.show',[],false));
        }

    public function getCompany(Request $req){   
        $comp = CompRegionConfig::where('region', $req->region)->get();  
        $arr = [];
        foreach($comp as $c){
            array_push($arr, ['id'=>$c->company_id, 'name'=>$c->companyid->company_descr]);
        }
        return $arr;
    }

    public function store(Request $req){
        $latest = CompRegionConfig::where('company_id', $req->inputcompany)->where('region', $req->inputregion)->latest('created_at')->first();
        if(date($latest->start_date)>date($req->inputdate)){
            
            // dd("korbnn");
            $update = CompRegionConfig::find($latest->id);
            $updateold = CompRegionConfig::where('company_id', $req->inputcompany)->where('region', $req->inputregion)->latest('created_at')->skip(1)->first();
            $updateold->end_date = $req->inputdate;
            $updateold->save();
        }else{
            // dd("korbnn");
            $update = new CompRegionConfig;
            $old = CompRegionConfig::find($latest->id);
            $old->end_date = $req->inputdate;
            $old->save();
        }
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
        Session::put(['region'=>$req->inputregion, 'company'=>$req->inputcompany]);
        return redirect(route('oe.show',[],false))->with([
            'inputregion'=>$req->inputregion, 
            'inputcompany'=>$req->inputcompany,
            'feedback' => true,
            'feedback_text' => "Successfully updated configuration!",
            'feedback_type' => "success"
        ]);
    }

}
