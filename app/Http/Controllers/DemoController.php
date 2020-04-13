<?php

namespace App\Http\Controllers;


use Illuminate\Support\Collection;


use App\Shared\GeoLocHelper;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function location(Request $req){
        echo(GeoLocHelper::getTotalMinutes());
   
        return view('demo.location', []);
    }
}
