<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MiscController extends Controller
{
  public function home(){
    return view('home');
  }

  public function index(){
    return view('welcome');
  }
}
