<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserRecord;
use App\UserVerifier;
use DataTables;
use DB;

class UserVerifierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function staffsearch(Request $req)
    {
        //    	        
        $data = [];
        if($req->has('q')){
            $search = $req->q;
            $data = User::select("id","name")
            		->where('name','LIKE',"%$search%")
                    ->get();
        }         
        return response()->json($data);
    }

    public function subordSearch(Request $req)
    {
        //    	        
        $data = [];
        if($req->has('q')){
            $search = $req->q;
            $data = User::select("id","name")
                    ->where('reptto','=',$req->user()->id)
                    ->where('name','LIKE',"%$search%")
                    //->orWhere('id', $req->user()->id)
                    ->get();
            if($data->count() == 0){                
                $data = User::select("id","name")
                ->where('reptto','=',$req->user()->id)
                ->where('name','LIKE',"%$search%")
                ->get();
            }
        }         
       
        //get crawl 8 tier down
        $reptto_list_id = $this->getListReptto($req);   

        //final
        $data = UserRecord::select("user_id as id","name","staffno")
        ->whereIn('user_id',$reptto_list_id)
        ->Where('empsgroup','Non Executive')
        ->where('name','LIKE',"%$search%")
        ->orderby('name')
        ->get();

        //dd($data);

        return response()->json($data);
    }
    
    public function staffverifier(Request $req)
    {
        $dataUser = User::where('id', '=', $req->user()->id)->latest('updated_at')->first();
        $dataVerifier = UserVerifier::where('user_id', '=', $req->user()->id)->get();
        $dataSubordinate = UserVerifier::where('verifier_id', '=', $req->user()->id)->get();

        return view('admin.verifier.index')
        //->with('userdata', $dataUser)
        //->with('verifiers', $dataVerifier)
        //->with('subordinates', $dataSubordinate)
        ->with([
            'userdata' => $dataUser,
            'verifiers' => $dataVerifier,
            'subordinates' => $dataSubordinate
        ]);
    }

    public function ajaxAdvSearchSubord(Request $req)
    {          
        //dd($req->dataSearch);
        $empl_name = strtoupper($req->dataSearch[0]['value']);
        $empl_persno = $req->dataSearch[1]['value'];
        $empl_staffno = strtoupper($req->dataSearch[2]['value']);
        $empl_position = $req->dataSearch[3]['value'];
        $empl_compcode = $req->dataSearch[4]['value'];
        $empl_costcenter = $req->dataSearch[5]['value'];
        $empl_personalarea = $req->dataSearch[6]['value'];
        $empl_personalsubarea = $req->dataSearch[7]['value'];
        $empl_subgroup = $req->dataSearch[8]['value'];
        $empl_email = strtoupper($req->dataSearch[9]['value']);
        $empl_mobilenumber = $req->dataSearch[10]['value'];
        $empl_officenumber = $req->dataSearch[11]['value'];

        $reptto_list_id = $this->getListReptto($req);        

        //check if no condition
        $checkCondition = 0;
        $data = [];

        $data = UserRecord::query();
        $data = $data->select("user_id","name","staffno","email","company_id","costcentr","persarea","perssubarea","empgroup","empsgroup");
        if(strlen(trim($empl_name)) > 0){
            $data = $data->orWhere(DB::raw('upper(name)'),'LIKE','%' .$empl_name. '%');
            $checkCondition++;
        }
        if(strlen(trim($empl_persno)) > 0){
            $data = $data->orWhere('user_id','LIKE','%' .$empl_persno. '%');
            $checkCondition++;
        }
        if(strlen(trim($empl_staffno)) > 0){
            $data = $data->orWhere(DB::raw('upper(staffno)'),'LIKE','%' .$empl_staffno. '%');
            $checkCondition++;
        }
        if(strlen(trim($empl_email)) > 0){
            $data = $data->orWhere(DB::raw('upper(email)'),'LIKE','%' .$empl_email. '%');
            $checkCondition++;
        }
        if(strlen(trim($empl_compcode)) > 0){
            $data = $data->orWhere('company_id','LIKE','%' .$empl_compcode. '%');
            $checkCondition++;
        }
        if(strlen(trim($empl_costcenter)) > 0){
            $data = $data->orWhere('costcentr','LIKE','%' .$empl_costcenter. '%');
        }
        if(strlen(trim($empl_personalarea)) > 0){
            $data = $data->orWhere('persarea','LIKE','%' .$empl_personalarea. '%');
            $checkCondition++;
        }
        if(strlen(trim($empl_personalsubarea)) > 0){
            $data = $data->orWhere('perssubarea','LIKE','%' .$empl_personalsubarea. '%');
            $checkCondition++;
        }
        if(strlen(trim($empl_subgroup)) > 0){
            $data = $data->orWhere('empsgroup','LIKE','%' .$empl_subgroup. '%');
            $checkCondition++;
        }
        if($checkCondition == 0){
            $data = $data->Where('id','=',0);
        }

        //for NE only
        $data = $data->Where('empsgroup','Non Executive');
         
        //where in 8 tiers down from user
        $data = $data->whereIn('user_id',$reptto_list_id);
        
        $data = $data->orderBy('name', 'asc');        
        $data = $data->get();       
        //$data = $data->first();
        //$data = $data->toSql();
        //$data = $data->companyid->company_descr;
        //dd($data->companyid->company_descr);
        //$data = array_push($data,['costcentr'=>$data->userRecordLatest->costcentr]);
        $arr = [];
        foreach($data as $s){
            array_push($arr, [
                'id'=> $s->user_id,
                'name'=>$s->name,
                'persno'=>sprintf('%08d', $s->user_id),
                'staffno'=>$s->staffno,
                'companycode'=>$s->companyid->company_descr,
                'costcenter'=>$s->costcentr,
                'persarea'=>$s->persarea,
                'empsubgroup'=>$s->empsgroup,
                'email'=>$s->email,
                'mobile'=>$s->name,
            ]);
        }

        return response()->json($arr);
    }

    public function getListReptto(Request $req)
    {  
        //direct rept crawl 8 tier
        $reptto_id = [];

        //1
        $data = User::select("persno as user_id","name")
        ->where('reptto','=',$req->user()->id)
        ->get();

        $reptto_id = $data->pluck('user_id')->toarray();
        
        //2
        if($data->count() > 0){ 
        $data = User::select("persno as user_id","name")
        ->whereIn('reptto',$reptto_id)
        ->get();
        
        $reptto_array = $data->pluck('user_id')->toarray();
        foreach($reptto_array as $a)
        {
           array_push($reptto_id,$a);
        }

            //3
            if($data->count() > 0){ 
                
                $data = User::select("persno as user_id","name")
                ->whereIn('reptto',$reptto_id)
                ->get();
                
                $reptto_array = $data->pluck('user_id')->toarray();
                foreach($reptto_array as $a)
                {
                array_push($reptto_id,$a);
                }

                
                //4
                if($data->count() > 0){ 
                    $data = User::select("persno as user_id","name")
                    ->whereIn('reptto',$reptto_id)
                    ->get();
                
                    $reptto_array = $data->pluck('user_id')->toarray();
                    foreach($reptto_array as $a)
                    {
                    array_push($reptto_id,$a);
                    }

                    //5
                    if($data->count() > 0){ 
                        $data = User::select("persno as user_id","name")
                        ->whereIn('reptto',$reptto_id)
                        ->get();
                    
                        $reptto_array = $data->pluck('user_id')->toarray();
                        foreach($reptto_array as $a)
                        {
                        array_push($reptto_id,$a);
                        }                        

                        //6
                        if($data->count() > 0){ 
                            $data = User::select("persno as user_id","name")
                            ->whereIn('reptto',$reptto_id)
                            ->get();
                        
                            $reptto_array = $data->pluck('user_id')->toarray();
                            foreach($reptto_array as $a)
                            {
                            array_push($reptto_id,$a);
                            }

                            //7
                            if($data->count() > 0){ 
                                $data = User::select("persno as user_id","name")
                                ->whereIn('reptto',$reptto_id)
                                ->get();
                            
                                $reptto_array = $data->pluck('user_id')->toarray();
                                foreach($reptto_array as $a)
                                {
                                array_push($reptto_id,$a);
                                }

                                //8
                                if($data->count() > 0){ 
                                    $data = User::select("persno as user_id","name")
                                    ->whereIn('reptto',$reptto_id)
                                    ->get();
                                
                                    $reptto_array = $data->pluck('user_id')->toarray();
                                    foreach($reptto_array as $a)
                                    {
                                    array_push($reptto_id,$a);
                                    }
                                }  
                            }  
                        }  
                    }      
                }            
            }            

        }

        return $reptto_id;
    }
    
}
