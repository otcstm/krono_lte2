<?php
namespace App\Http\Controllers\Admin;
use App\PaymentSchedule;
use App\Shared\UserHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use \Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Payrollgroup;
use DB;


class PaymentScheduleController extends Controller
{
  public function index(Request $req)
  {
      $pygroup = Payrollgroup::all();
      // $defdate = $req ->int_date->format('M');
      $defyear= date('Y');
      $slctyr= $req->slctyr ? $req->slctyr : $defyear;
      $slctyr = Session('slctyr') ? Session('slctyr') : $slctyr;

      $list_year = PaymentSchedule::select(DB::raw('YEAR(payment_date) as year'))
      ->distinct()->orderBy('year','desc')->get()
      ->pluck('year')->toArray();
      array_push($list_year,'all');

      // dd($slctyr);

      if($slctyr=='all'){
        $ps = PaymentSchedule::all();
      }else{
        $ps = PaymentSchedule::whereRaw("YEAR(payment_date)= '".$slctyr."'")->orderBy('payment_date','asc')->get();
      }


      return view('admin.paymentschedule',
      ['ps_list' => $ps, 'pygroups' =>$pygroup, 'slctyr' => $slctyr, 'list_year' => $list_year]);
  }

  public function store(Request $req)
  {
      //dapatkan bulan paydate, check if exist
      $pd = $req ->pay_date;
      $paydate = Carbon::parse($pd);
      $pyear = $paydate->format('Y');
      $pmonth = $paydate->format('m');
      $fmonth = $paydate->format('F');
      $group = $req->pyg;
      $check = PaymentSchedule::whereYear('payment_date',"=", $pyear)
            ->whereMonth('payment_date',"=", $pmonth)
            ->where('payrollgroup_id',$group)
            ->get();

      if(count($check)==0){
        $new_ps = new PaymentSchedule;
        $new_ps-> payrollgroup_id = $req ->pyg;
        $new_ps-> last_sub_date = $req ->last_sub;
        $new_ps-> last_approval_date = $req ->last_approval;
        $new_ps-> interface_date = $req ->int_date;
        $new_ps-> payment_date = $req ->pay_date;
        $new_ps-> source = 'OT';
        $new_ps-> created_by= $req->user()->id;
        $new_ps-> save();
        $execute = UserHelper::LogUserAct($req, "Payment Schedule", "Create Payment Schedule $pmonth/$pyear");
        $a_text = "Payment Schedule $fmonth $pyear successfully created.";
        $a_type = "success";
      }else{
        // dd([$check]);

        $a_text = "Payment Schedule $fmonth $pyear already exist.";
        $a_type = "warning";
      }
      return redirect(route('paymentsc.index', [], false))->with(['a_text'=>$a_text,'a_type'=>$a_type]);
  }

  public function update(Request $req)
    {
     $pd = $req ->pay_date;
     $paydate = Carbon::parse($pd);
     $pyear = $paydate->format('Y');
     $pmonth = $paydate->format('m');
     $fmonth = $paydate->format('F');
     $inpyg = $req->inpyg;
     $check = PaymentSchedule::whereYear('payment_date',"=", $pyear)
           ->whereMonth('payment_date',"=", $pmonth)
           ->where('payrollgroup_id',$inpyg)
           ->where('id','!=',$req->inputid)
           ->get();

     if(count($check)!=0){
       $a_text = "Payment Schedule $fmonth $pyear already exist.";
       $a_type = "warning";
     }else{
       $ps = PaymentSchedule::find($req->inputid);
       if($ps){
         $ps-> payrollgroup_id = $inpyg;
         $ps-> last_sub_date = $req->inputsub;
         $ps-> last_approval_date = $req->inputapp;
         $ps-> interface_date = $req->inputint;
         $ps-> payment_date = $req->inputpay;
         $ps-> source = 'OT';
         $ps-> updated_by = $req->user()->id;
         $ps->save();
         $execute = UserHelper::LogUserAct($req, "Payment Schedule", "Update Payment Schedule $pmonth/$pyear " );
         $a_text = "Payment Schedule $fmonth $pyear updated!";
         $a_type = "success";
       }else{
         $a_text = "Payment Schedule not found.";
         $a_type = "warning";
       }
    }
    return redirect(route('paymentsc.index', [], false))->with(['a_text'=>$a_text, 'a_type'=>$a_type]);
  }

  public function destroy(Request $req)
  {
    $ps = PaymentSchedule::find($req->inputid);
    if($ps){
      $execute = UserHelper::LogUserAct($req, "Payment Schedule", "Delete Payment Schedule ");
      // $ps->save();
      $ps->delete();
      $a_text = "Payment Schedule deleted!";
      $a_type = "warning";
    } else {
      $a_text = "Payment Schedule not found";
      $a_type = "danger";
    }
    return redirect(route('paymentsc.index', [], false))->with(['a_text'=>$a_text , 'a_type'=>$a_type]);
  }
}
