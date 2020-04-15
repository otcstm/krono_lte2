<?php

namespace App\Http\Controllers;

use Session;
use App\Announcement;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
date_default_timezone_set("Asia/Kuala_Lumpur");

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // public function __construct()
    // {

    //   $this->middleware(function ($request, $next) {
    //   // fetch session and use it in entire class with constructor
    //   // dd($request->session()->all());
    //   \App\Shared\UserHelper::LoadNotifyList();

    //   return $next($request);
    //   });

    //     // dd(session()->all());
    // }


    public function __construct()
    {

      $this->middleware(function ($request, $next) {
      // fetch session and use it in entire class with constructor
      // dd($request->session()->all());
      \App\Shared\AlertHelper::LoadNotifyList();

      $an = Announcement::where('start_date','<=',date('Y-m-d'))->where('end_date','>=',date('Y-m-d'))->first();
      if($an!=null){
        Session::put(['announcement' => $an]);
      }
      // dd(session()->all());
      return $next($request);
      });

        // dd(session()->all());
    }

    public function errorHandler($errno, $errstr) {
  		return [
        'code' => $errno,
        'msg' => $errstr
      ];
  	}
}
