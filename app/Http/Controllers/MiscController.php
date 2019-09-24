<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MiscController extends Controller
{
  public function home(Request $req){
    // dd($req->user()->name);
    return view('home', ['uname' => $req->user()->name]);
  }

  public function index(){
    return view('welcome');
  }
}
