<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\VerifierGroup;
use App\VerifierGroupMember;

class VerifierGroupController extends Controller
{
    public function index(Request $req)
    {	                
        $dataUser = User::where('id', '=', $req->user()->id)->latest('updated_at')->first();
        //$repttoUser = User::where('reptto', '=', $req->user()->id)->latest('updated_at')->first();
        $verifierGroups = VerifierGroup::where('approver_id', '=', $req->user()->id)->get();
  
        return view('verifier.index')
        ->with('userdata', $dataUser)  
        //->with('repttodata', $repttoUser)            
        ->with('verifierGroups', $verifierGroups);
    }

    public function search(Request $req)
    {	        
        //$data = User::where('reptto', '=', $req->user()->id)->get();
        //return view('verifier.index')->with('subordinate', $data);

        return view('verifier.search');
    }

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

    public function createGroup(Request $req)
    {	     
        dd($request->all());           
        $userId = $req->user()->id;
    
        return Response::json($userId);
    }
}
