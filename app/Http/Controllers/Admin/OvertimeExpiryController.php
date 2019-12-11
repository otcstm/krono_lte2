<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// class OvertimeExpiryController extends Controller
// {
//     public function show(Request $req){  
//         if($req->session()->get('region')!=null){
//             $req->inputregion = $req->session()->get('region');
//             $req->inputcompany = $req->session()->get('company');
//         }
//         if($req->inputregion==""){
//             $oe = CompRegionConfig::all();     
//             return view('admin.oteligibilitymain', ['oe' => $oe]);
//         }else{
//             $oe = CompRegionConfig::where('company_id', $req->inputcompany)->where('region', $req->inputregion)->get();  
//             // dd($oe);
//             return view('admin.oteligibilitysub', ['oe' => $oe]);
//         }
//     }
// }
