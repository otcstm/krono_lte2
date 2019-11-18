<?php

namespace App\Http\Controllers\Admin;

use App\PaymentSchedule;
use App\Shared\UserHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use \Carbon\Carbon;
use App\Http\Controllers\Controller;


class PaymentScheduleController extends Controller
{
  public function index()
  {
      $ps = PaymentSchedule::all();
      // $defdate = $req ->int_date->format('M');
      return view('admin.paymentschedule', ['ps_list' => $ps]);
  }

  public function store(Request $req)
  {
    //dapatkan bulan paydate, check if exist
      $pd = $req ->pay_date;
      $paydate = Carbon::parse($pd);
      $pyear = $paydate->format('Y');
      $pmonth = $paydate->format('m');

      $check = PaymentSchedule::whereYear('payment_date',"=", $pyear)
            ->whereMonth('payment_date',"=", $pmonth)
            ->get();

      if(count($check)==0){
      $new_ps = new PaymentSchedule;
      $new_ps-> last_sub_date = $req ->last_sub;
      $new_ps-> last_approval_date = $req ->last_approval;
      $new_ps-> interface_date = $req ->int_date;
      $new_ps-> payment_date = $req ->pay_date;
      $new_ps-> source = 'OT';
      $new_ps-> created_by= $req->user()->id;
      $new_ps-> save();
      $execute = UserHelper::LogUserAct($req, "Payment Schedule", "Create Payment Schedule $pmonth/$pyear");
      $a_text = "Payment Schedule $pmonth/$pyear successfully created.";
      $a_type = "success";

      return redirect(route('paymentsc.index', [], false))
      ->with(['a_text' => $a_text,'a_type' => $a_type]);
      }
      else{
        $a_text = "Payment Schedule $pmonth/$pyear already exist.";
        $a_type = "warning";
        return redirect(route('paymentsc.index', [], false))
        ->with(['a_text' => $a_text,'a_type' => $a_type]);
      }

  }

  public function update(Request $req)
    {
     $pd = $req ->pay_date;
     $paydate = Carbon::parse($pd);
     $pyear = $paydate->format('Y');
     $pmonth = $paydate->format('m');

     $ps = PaymentSchedule::find($req->inputid);
     if($ps){
       $ps-> last_sub_date = $req->inputsub;
       $ps-> last_approval_date = $req->inputapp;
       $ps-> interface_date = $req->inputint;
       $ps-> payment_date = $req->inputpay;
       $ps-> source = 'OT';
       $ps-> updated_by = $req->user()->id;
       $ps->save();
       $execute = UserHelper::LogUserAct($req, "Payment Schedule", "Update Payment Schedule $pmonth/$pyear " );
       return redirect(route('paymentsc.index', [], false))->with(['a_text' => "Payment Schedule $pmonth/$pyear updated!", 'a_type' => 'success']);
     }
     else{
       return redirect(route('paymentsc.index', [], false))
       ->with(['a_text' =>'Payment Schedule not found.', 'a_type' => 'warning']);
     }

    }

    public function destroy(Request $req)
    {
      $ps = PaymentSchedule::find($req->inputid);

      if($ps){
        $execute = UserHelper::LogUserAct($req, "Payment Schedule", "Delete Payment Schedule ");
        // $ps->save();
        $ps->delete();

        return redirect(route('paymentsc.index', [], false))->with(['a_text' => ' Payment Schedule deleted!', 'a_type' => 'warning']);
      } else {
        return redirect(route('paymentsc.index', [], false))->with(['a_text' => ' Payment Schedule not found', 'a_type' => 'danger']);
      }


    }
  }
