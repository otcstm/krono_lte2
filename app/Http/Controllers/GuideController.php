<?php

namespace App\Http\Controllers;

use App\State;
use App\Holiday;
use Illuminate\Http\Request;

use Session;
class GuideController extends Controller
{
    //
    public function viewCalendar(Request $req){
        
        if($req->session()->get('stateman')!=null){
            $ownstate = $req->session()->get('stateman');
        }else{
            $ownstate = $req->user()->state_id;
        }
        $state = State::all();
        $holiday = Holiday::whereYear("dt", date("Y"))->orderBy('dt', 'asc')->get();

        return view('guide.calendar',['state' => $state,'holiday' => $holiday, 'ownstate' => $ownstate]);
    }

    public function dateCalendar(Request $req){
        Session::put(['stateman' => $req->stet]); 
        return redirect(route('guide.calendar',[],false));
    }

    public function viewCalendarEmpty(Request $req){
        Session::put(['stateman' => null]); 
        return redirect(route('guide.calendar',[],false));
    }
}
