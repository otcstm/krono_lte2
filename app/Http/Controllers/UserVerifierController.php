<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserVerifier;

class UserVerifierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {	        
        return view('admin.verifier.index');
    }

    public function search(Request $req)
    {	        
        //$data = User::where('reptto', '=', $req->user()->id)->get();
        //return view('verifier.index')->with('subordinate', $data);

        return view('admin.verifier.search');
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

    
    public function staffverifier(Request $req)
    {
        $dataUser = User::where('id', '=', $req->user()->id)->latest('updated_at')->first();
        $dataVerifier = UserVerifier::where('user_id', '=', $req->user()->id)->get();
        $dataSubordinate = UserVerifier::where('verifier_id', '=', $req->user()->id)->get();

        return view('admin.verifier.index')
        ->with('userdata', $dataUser)
        ->with('verifiers', $dataVerifier)
        ->with('subordinates', $dataSubordinate);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
