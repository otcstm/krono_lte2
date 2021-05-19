<?php

namespace App\Http\Controllers;

use App\State;
use App\PaymentSchedule;
use App\Holiday;
use App\HolidayCalendar;
use Illuminate\Http\Request;

use Session;

class GuideController extends Controller
{
    //
    public function viewCalendar(Request $req)
    {

        
        if ($req->session()->get('stateman') != null) {
            $ownstate = $req->session()->get('stateman');
        } else {
            $ownstate = $req->user()->state_id;
        }
        $state = State::all();

        $holiday = Holiday::whereYear("dt", date("Y"))
            ->whereHas('holCal', function ($query) use($ownstate) {
                $query->where('state_id', $ownstate);
            })
            ->orderBy('dt', 'asc')->get();
     



       //dd($holiday);



        return view('guide.calendar', ['state' => $state, 'holiday' => $holiday, 'ownstate' => $ownstate]);
    }

    public function dateCalendar(Request $req)
    {
        Session::put(['stateman' => $req->stet]);
        return redirect(route('guide.calendar', [], false));
    }

    public function viewCalendarEmpty(Request $req)
    {
        Session::put(['stateman' => null]);
        return redirect(route('guide.calendar', [], false));
    }

    public function viewSystem(Request $req)
    {
        return view('guide.systemguideline', []);
    }

    public function viewPaymentCalendar(Request $req)
    {
        $payment = PaymentSchedule::whereYear('payment_date', date("Y"))->orderBy('payment_date', "ASC")->get();
        $month = PaymentSchedule::whereMonth('payment_date', date("m"))->first();
        $lastsub = $month->last_sub_date;
        $paymentd = $month->payment_date;
        $today = date("Y-m-d");
        $now = strtotime($today);
        $calc = strtotime($paymentd);
        // dd(($now));
        $dtg = round(($calc - $now)/(60*60*24));
        // dd($dtg);
        return view('guide.paymentcalendar', ['lastsub' => $lastsub, 'paymentd'=>$paymentd, 'dtg'=>$dtg, "date"=> $payment]);
    }
}
