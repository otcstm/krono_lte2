<?php

namespace App\Http\Controllers;


use Illuminate\Support\Collection;


use App\Shared\GeoLocHelper;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function location(Request $req){
        $loc = null; 
if($req->submitForm){
        
    $loc = GeoLocHelper::getLocDescr($req->lat,$req->lon);        
}  
        return view('demo.location', ['loc'=>$loc]);
    }
}
