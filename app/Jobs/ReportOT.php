<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\ExcelHandler;
use App\BatchJob;
use \DB;
use App\Overtime;
use App\State;
use App\SetupCode;
use App\Company;
use App\OvertimeDetail;
use \Carbon\Carbon;

class ReportOT implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bjobid;
    protected $start_date;
    protected $end_date;
    protected $app_id;
    protected $ver_id;
    protected $ref_num;
    protected $perno;
    protected $comp;
    protected $state;
    protected $region;
    protected $pilihcol;
    protected $btnsrh;

    public $tries = 1;
    public $timeout = 7200;

    public function __construct($sdt,$edt,$aid,$vid,$rno,$per,$co,$st,$re,$col,$btn)
    {
      $this->start_date = $sdt;
      $this->end_date = $edt;
      $this->app_id = $aid;
      $this->ver_id = $vid;
      $this->ref_num = $rno;

      $this->perno = $per;
      $this->comp = $co;
      $this->state = $st;
      $this->region = $re;
      $this->pilihcol = $col;
      $this->btnsrh = $btn;

// dd($this->pilihcol);
      if($this->btnsrh == 'gexcelm'){
        $jt = 'SummaryOTReport';
        $r = 'SummaryOTReport';
      }elseif ($this->btnsrh == 'gexceld') {
        $jt = 'OvertimeDetailsReport';
        $r = 'OvertimeDetailsReport';
      }

      $bjob = new BatchJob;
      $bjob->job_type = $jt;
      $bjob->status = 'Queued';
      $bjob->from_date = $sdt;
      $bjob->to_date = $edt;
      $bjob->remark = $r;
      // dd('here');
      $bjob->save();

      $this->bjobid = $bjob->id;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
     public function handle()
     {
       $bjob = BatchJob::find($this->bjobid);
       // dd($bjob);
       if($bjob && $bjob->status == 'Queued'){
         $bjob->status = 'Processing';
         $bjob->save();
         Log::info('batch job id: ' . $this->bjobid);
         Log::info('start date: ' . $this->start_date);
         Log::info('end date: ' . $this->end_date);


         $cdate = new Carbon($this->end_date);
         $ldate = new Carbon($this->start_date);
         // Log::info('finding group');

         $noww = Carbon::now();
         // $nm = $this->jtyp;
         if($this->btnsrh == 'gexcelm'){
           $nm = 'SummaryOTReport';
         }elseif ($this->btnsrh == 'gexceld') {
           $nm = 'OvertimeDetailsReport';
         }

         Log::info('build filename');
         $fname = $nm
           // . $ldate->format('Ymd') . '_to_' . $cdate->format('Ymd')
           . '_' . $noww->format('YmdHis') .'_'.$this->bjobid.'.xlsx';

         $cdate->addSecond();

         Log::info('prep header');
         if($this->btnsrh == 'gexcelm'){
           $headers = ['Personnel Number','Employee Name','IC Number','Staff ID','Company Code','Reference Number','OT Date'];
         }elseif ($this->btnsrh == 'gexceld') {
           $headers = ['Personnel Number','Employee Name','IC Number','Staff ID','Company Code','Reference Number','OT Date','Start Time','End Time'];
         }

         $sltcol = $this->pilihcol;

         if(isset($sltcol)){
           if(in_array('psarea',$sltcol ))
             {
               array_push($headers, 'Personnel Area');
             }
             if(in_array( 'psbarea',$sltcol ))
             {
               array_push($headers, 'Personnel Subarea');
             }
             if(in_array( 'state',$sltcol ))
             {
               array_push($headers, 'State');
             }
             if(in_array( 'region',$sltcol))
             {
               array_push($headers, 'Region');
             }
             if(in_array( 'empgrp',$sltcol))
             {
               array_push($headers, 'Employee Group');
             }
             if(in_array( 'empsubgrp',$sltcol))
             {
               array_push($headers, 'Employee Subgroup');
             }
             if(in_array( 'salexp',$sltcol))
             {
               array_push($headers, 'Salary Exception');
             }
             if(in_array( 'capsal',$sltcol))
             {
               array_push($headers, 'Capping Salary (RM)');
             }
             if(in_array( 'empst',$sltcol))
             {
               array_push($headers, 'Employment Status');
             }
             if(in_array( 'mflag',$sltcol))
             {
               array_push($headers, 'Manual Flag');
             }
             if(in_array( 'loc',$sltcol))
             {
               array_push($headers, 'Location');
             }
             if(in_array( 'tthour',$sltcol))
             {
               array_push($headers, 'Total Hours');
             }
             if(in_array( 'ttlmin',$sltcol))
             {
               array_push($headers, 'Total Minutes');
             }
             if(in_array( 'estamnt',$sltcol))
             {
               array_push($headers, 'Estimated Amount');
             }
             if(in_array( 'clmstatus',$sltcol))
             {
               array_push($headers, 'Claim Status');
             }
             if(in_array( 'chrtype',$sltcol))
             {
               array_push($headers, 'Charge Type');
             }
             if(in_array( 'bodycc',$sltcol))
             {
               array_push($headers, 'Body Cost Center');
             }
             if(in_array( 'othrcc',$sltcol))
             {
               array_push($headers, 'Other Cost Center');
             }
             if(in_array( 'prtype',$sltcol))
             {
               array_push($headers, 'Project Type');
             }
             if(in_array( 'pnumbr',$sltcol))
             {
               array_push($headers, 'Project Number');
             }
             if(in_array( 'ntheadr',$sltcol))
             {
               array_push($headers, 'Network Header');
             }
             if(in_array( 'ntact',$sltcol))
             {
               array_push($headers, 'Network Activity');
             }
             if(in_array( 'ordnum',$sltcol))
             {
               array_push($headers, 'Order Number');
             }
             if(in_array( 'noh',$sltcol))
             {
               array_push($headers, 'Number of Hours');
             }
             if(in_array( 'nom',$sltcol))
             {
               array_push($headers, 'Number of Minutes');
             }
             if(in_array( 'jst',$sltcol))
             {
               array_push($headers, 'Justification');
             }
             if(in_array( 'appdate',$sltcol))
             {
               array_push($headers, 'Application Date');
             }
             if(in_array( 'verdate',$sltcol))
             {
               array_push($headers, 'Verification Date');
             }
             if(in_array( 'verid',$sltcol))
             {
               array_push($headers, 'Verifier ID');
             }
             if(in_array( 'vername',$sltcol))
             {
               array_push($headers, 'Verifier Name');
             }
             if(in_array( 'vercocd',$sltcol))
             {
               array_push($headers, 'Verifier Cocd');
             }
             if(in_array( 'aprvdate',$sltcol))
             {
               array_push($headers, 'Approval Date');
             }
             if(in_array( 'apprvrid',$sltcol))
             {
               array_push($headers, 'Approver ID');
             }
             if(in_array( 'apprvrname',$sltcol))
             {
               array_push($headers, 'Approver Name');
             }
             if(in_array( 'apprvrcocd',$sltcol))
             {
               array_push($headers, 'Approver Cocd');
             }
             if(in_array( 'qrdate',$sltcol))
             {
               array_push($headers, 'Queried Date');
             }
             if(in_array( 'qrdby',$sltcol))
             {
               array_push($headers, 'Queried By');
             }
             if(in_array( 'pydate',$sltcol))
             {
               array_push($headers, 'Payment Date');
             }
             if(in_array( 'trnscd',$sltcol))
             {
               array_push($headers, 'Transaction Code');
             }
             if(in_array( 'dytype',$sltcol))
             {
               array_push($headers, 'Day Type');
             }

           }

           // dd($headers);

         set_time_limit(0);

         $otr = Overtime::query();
         $otr = $otr->whereNotNull('company_id')->where('company_id','!=','');
         if(isset($this->start_date)){
           // dd('here',$this->start_date);
             $otr = $otr->whereBetween('date', array($this->start_date, $this->end_date));
           }
           if(isset($this->perno)){
             $otr = $otr->whereIn('user_id',$this->perno);
           }
           if(isset($this->comp)){
             $otr = $otr->whereIn('company_id',$this->comp);
           }
           if(isset($this->state)){
             $otr = $otr->whereIn('state_id',$this->state);
           }
           if(isset($this->region)){
             $otr = $otr->whereIn('region',$this->region);
           }
           if(isset($this->app_id)){
             $otr = $otr->where('approver_id', 'LIKE', '%' .$this->app_id. '%');
           }
           if(isset($this->ref_num)){
             $otr = $otr->where('refno', 'LIKE', '%' .$this->ref_num. '%');
           }
           if(isset($this->ver_id)){
             $otr = $otr->where('verifier_id', 'LIKE', '%' .$this->ver_id. '%');
           }
           $otr = $otr->where('status','not like',"%D%")->get();
           // ->get();

           // Log::info($otr->toSql());
           // $otr = $otr->get();
           // Log::info($otr->count());

           if($this->btnsrh == 'gexceld') {
             $list_of_id = $otr->pluck('id');
             $otdetail = OvertimeDetail::whereIn('ot_id', $list_of_id)
             ->where('checked','Y');
             // ->get();
             // Log::info($otdetail->toSql());
             $otdetail = $otdetail->get();
             // Log::info($otdetail->count());

           }
           $otdata = [];
           $eksel = new ExcelHandler($fname);
           if($this->btnsrh == 'gexcelm') {
             foreach($otr as $value){

                 $urekod = $value->URecord;

                 $otdt = new Carbon($value->date);
                 $otdt = $otdt->format('d.m.Y');

              $info = [$value->user_id,$urekod->name,$urekod->new_ic,$urekod->staffno,$value->company_id,$value->refno,$otdt];
              $sltcol = $this->pilihcol;
              if(isset($sltcol)){
                if(in_array('psarea',$sltcol))
                {
                  array_push($info, $urekod->persarea);
                }
                if(in_array( 'psbarea',$sltcol))
                {
                  array_push($info, $urekod->perssubarea);
                }
                if(in_array( 'state',$sltcol))
                {
                  array_push($info, $value->state_id);
                }
                if(in_array( 'region',$sltcol))
                {
                  array_push($info, $value->region);
                }
                if(in_array( 'empgrp',$sltcol))
                {
                  array_push($info, $urekod->empgroup);
                }
                if(in_array( 'empsubgrp',$sltcol))
                {
                  array_push($info, $urekod->empsgroup);
                }
                if(in_array( 'salexp',$sltcol))
                {
                  if($value->sal_exception=='X'){
                    // $value->ot_hour_exception='Yes';
                    $sal_exception='Yes';
                    // $salarycap='';
                  }else{
                    // $value->ot_hour_exception='No';
                    $sal_exception='No';
                    // $salarycap=$value->SalCap()->salary_cap;
                  }
                  array_push($info, $sal_exception);
                }
                if(in_array( 'capsal',$sltcol))
                {
                  if($value->sal_exception=='X'){
                    // $value->ot_hour_exception='Yes';
                    // $sal_exception='Yes';
                    $salarycap='';
                  }else{
                    // $value->ot_hour_exception='No';
                    // $sal_exception='No';
                    try {
                      $salarycap=$value->SalCap()->salary_cap;
                    } catch (\Exception $e) {
                      $salarycap='COMP CODE ERROR';
                    }
                  }
                  array_push($info, $salarycap);
                }
                if(in_array( 'empst',$sltcol))
                {
                  array_push($info, $urekod->empstats);
                }
                if(in_array( 'tthour',$sltcol))
                {
                  array_push($info, $value->total_hour);
                }
                if(in_array( 'ttlmin',$sltcol))
                {
                  array_push($info, $value->total_minute);
                }
                if(in_array( 'estamnt',$sltcol))
                {
                  array_push($info, $value->amount);
                }
                if(in_array( 'clmstatus',$sltcol))
                {
                  try {
                    $statusOT=$value->OTStatus()->item3;
                  } catch (\Exception $e) {
                    $statusOT=$value->status;
                  }
                  array_push($info, $statusOT);
                }
                if(in_array( 'chrtype',$sltcol))
                {
                  array_push($info, $value->charge_type);
                }
                if(in_array( 'bodycc',$sltcol))
                {
                  array_push($info, $value->costcenter);
                }
                if(in_array( 'othrcc',$sltcol))
                {
                  array_push($info, $value->other_costcenter);
                }
                if(in_array( 'prtype',$sltcol))
                {
                  array_push($info, $value->project_type);
                }
                if(in_array( 'pnumbr',$sltcol))
                {
                  array_push($info, $value->project_no);
                }
                if(in_array( 'ntheadr',$sltcol))
                {
                  array_push($info, $value->network_header);
                }
                if(in_array( 'ntact',$sltcol))
                {
                  array_push($info, $value->network_act_no);
                }
                if(in_array( 'ordnum',$sltcol))
                {
                  array_push($info, $value->order_no);
                }
                if(in_array( 'appdate',$sltcol))
                {
                  $cdt = new Carbon($value->submitted_date);
                  $cdt = $cdt->format('d.m.Y');
                  array_push($info, $cdt);
                }
                if(in_array( 'verdate',$sltcol))
                {
                  if( $value->verification_date == ''){
                      $ver_date = '';
                    }else{
                      $ver_date = date('d.m.Y', strtotime($value->verification_date));
                    }
                  array_push($info, $ver_date);
                }
                if(in_array( 'verid',$sltcol))
                {
                  array_push($info, $value->verifier_id);
                }

                if(in_array( 'vername',$sltcol))
                {
                  array_push($info, $value->verifier->name);
                }
                if(in_array( 'vercocd',$sltcol))
                {
                  array_push($info, $value->verifier->company_id);
                }

                if(in_array( 'aprvdate',$sltcol))
                {
                  if( $value->approved_date == ''){
                    $appvl_date = '';
                  }else{
                    $appvl_date = date('d.m.Y', strtotime($value->approved_date));
                  }

                  array_push($info, $appvl_date);
                }
                if(in_array( 'apprvrid',$sltcol))
                {
                  array_push($info, $value->approver_id);
                }
              if(in_array( 'apprvrname',$sltcol))
              {
               array_push($info, $value->approver->name);
              }
              if(in_array( 'apprvrcocd',$sltcol))
              {
              array_push($info, $value->approver->company_id);
              }
              if(in_array( 'qrdate',$sltcol))
              {
                if( $value->queried_date == ''){
                  $queried_date ='';
                }else{
                  $queried_date =date('d.m.Y', strtotime($value->queried_date));
                }

                  array_push($info, $queried_date);
              }
                if(in_array( 'qrdby',$sltcol))
                {
                  array_push($info, $value->querier_id);
                }
                if(in_array( 'pydate',$sltcol))
                {
                  if( $value->payment_date == ''){
                    $payment_date ='';
                  }else{
                    $payment_date =date('d.m.Y', strtotime($value->payment_date));
                  }
                  array_push($info, $payment_date);
                }
                if(in_array( 'trnscd',$sltcol))
                {
                  array_push($info, $value->wage_type);
                }
                if(in_array( 'dytype',$sltcol))
                {
                  try {
                    $dtype = $value->daytype->description;
                  } catch (\Exception $e) {
                    $dtype = $value->daytype_id;
                  }
                  array_push($info, $dtype);
                }

              }

              array_push($otdata, $info);
            }
          }
          //-----------------------------OT Details---------------------------------------------------------
          elseif($this->btnsrh == 'gexceld'){
            foreach($otdetail as $value){

              $urekod = $value->mainOT->URecord;
              $mainOT = $value->mainOT;
              $otdt = new Carbon($mainOT->date);
              $otdt = $otdt->format('d.m.Y');
              $st = new Carbon($value->start_time);
              $st = $st->format('H:i:s');
              $et = new Carbon($value->end_time);
              $et = $et->format('H:i:s');

              $info = [$mainOT->user_id,$urekod->name,$urekod->new_ic,$urekod->staffno,$mainOT->company_id,$mainOT->refno,$otdt,$st,$et];
                // dd($pilihcol);
                $sltcol = $this->pilihcol;
                if(isset($sltcol)){
                if(in_array('psarea',$sltcol ))
                  {
                    array_push($info, $urekod->persarea);
                  }
                  if(in_array( 'psbarea',$sltcol))
                  {
                    array_push($info, $urekod->perssubarea);
                  }
                  if(in_array( 'state',$sltcol))
                  {
                    array_push($info, $mainOT->state_id);
                  }
                  if(in_array( 'region',$sltcol))
                  {
                    array_push($info, $mainOT->region);
                  }
                  if(in_array( 'empgrp',$sltcol))
                  {
                    array_push($info, $urekod->empgroup);
                  }
                  if(in_array( 'empsubgrp',$sltcol))
                  {
                    array_push($info, $urekod->empsgroup);
                  }
                  if(in_array( 'salexp',$sltcol))
                  {
                    if($mainOT->sal_exception=='X'){
                      // $mainOT->ot_hour_exception='Yes';
                      $sal_exception='Yes';
                      // $salarycap='';
                    }else{
                      // $mainOT->ot_hour_exception='No';
                      $sal_exception='No';
                      // $salarycap=$mainOT->SalCap()->salary_cap;
                    }
                    array_push($info, $sal_exception);
                  }
                  if(in_array( 'capsal',$sltcol))
                  {
                    if($mainOT->sal_exception=='X'){
                      // $mainOT->ot_hour_exception='Yes';
                      // $sal_exception='Yes';
                      $salarycap='';
                    }else{
                      // $mainOT->ot_hour_exception='No';
                      // $sal_exception='No';
                      try {
                        $salarycap=$mainOT->SalCap()->salary_cap;
                      } catch (\Exception $e) {
                        $salarycap='COMP CODE ERROR';
                      }
                    }
                    array_push($info, $salarycap);
                  }
                  if(in_array( 'empst',$sltcol))
                  {
                    array_push($info, $urekod->empstats);
                  }
                  if(in_array( 'mflag',$sltcol))
                  {
                    array_push($info, $value->is_manual);
                  }
                  if(in_array( 'loc',$sltcol))
                  {
                    array_push($info, '('.$value->in_latitude.','.$value->in_longitude.')');
                  }
                  if(in_array( 'estamnt',$sltcol))
                  {
                    array_push($info, $value->amount);
                  }
                  if(in_array( 'clmstatus',$sltcol))
                  {
                    try {
                      $statusOT=$mainOT->OTStatus()->item3;
                    } catch (\Exception $e) {
                      $statusOT=$mainOT->status;
                    }

                    array_push($info, $statusOT);
                  }
                  if(in_array( 'chrtype',$sltcol))
                  {
                    array_push($info, $mainOT->charge_type);
                  }
                  if(in_array( 'bodycc',$sltcol))
                  {
                    array_push($info, $mainOT->costcenter);
                  }
                  if(in_array( 'othrcc',$sltcol))
                  {
                    array_push($info, $mainOT->other_costcenter);
                  }
                  if(in_array( 'prtype',$sltcol))
                  {
                    array_push($info, $mainOT->project_type);
                  }
                  if(in_array( 'pnumbr',$sltcol))
                  {
                    array_push($info, $mainOT->project_no);
                  }
                  if(in_array( 'ntheadr',$sltcol))
                  {
                    array_push($info, $mainOT->network_header);
                  }
                  if(in_array( 'ntact',$sltcol))
                  {
                    array_push($info, $mainOT->network_act_no);
                  }
                  if(in_array( 'ordnum',$sltcol))
                  {
                    array_push($info, $mainOT->order_no);
                  }

                  if(in_array( 'noh',$sltcol))
                  {
                    array_push($info, $value->hour);
                  }
                  if(in_array( 'nom',$sltcol))
                  {
                    array_push($info, $value->minute);
                  }
                  if(in_array( 'jst',$sltcol))
                  {
                    array_push($info, $value->justification);
                  }
                  if(in_array( 'appdate',$sltcol))
                  {
                    $cdt = new Carbon($mainOT->submitted_date);
                    $cdt = $cdt->format('d.m.Y');
                    array_push($info, $cdt);
                  }
                  if(in_array( 'verdate',$sltcol))
                  {
                    if( $mainOT->verification_date == ''){
                      $ver_date = '';
                    }
                    else{
                      $ver_date = date('d.m.Y', strtotime($mainOT->verification_date));
                    }
                    array_push($info, $ver_date);
                  }
                  if(in_array( 'verid',$sltcol))
                  {
                    array_push($info, $mainOT->verifier_id);
                  }
                  if(in_array( 'vername',$sltcol))
                  {
                    array_push($info, $mainOT->verifier->name);
                  }
                  if(in_array( 'vercocd',$sltcol))
                  {
                    array_push($info, $mainOT->verifier->company_id);
                  }
                  if(in_array( 'aprvdate',$sltcol))
                  {
                    if( $mainOT->approved_date == ''){
                      $appvl_date = '';
                    }
                    else{
                      $appvl_date = date('d.m.Y', strtotime($mainOT->approved_date));
                    }
                    array_push($info, $appvl_date);
                  }
                  if(in_array( 'apprvrid',$sltcol))
                  {
                    array_push($info, $mainOT->approver_id);
                  }
                  if(in_array( 'apprvrname',$sltcol))
                  {
                    array_push($info, $mainOT->approver->name);
                  }
                  if(in_array( 'apprvrcocd',$sltcol))
                  {
                    array_push($info, $mainOT->approver->company_id);
                  }
                  if(in_array( 'qrdate',$sltcol))
                  {
                    if( $mainOT->queried_date == ''){
                      $queried_date ='';
                    }
                    else{
                      $queried_date =date('d.m.Y', strtotime($mainOT->queried_date));
                    }
                    array_push($info, $queried_date);
                  }
                  if(in_array( 'qrdby',$sltcol))
                  {
                    array_push($info, $mainOT->querier_id);
                  }
                  if(in_array( 'pydate',$sltcol))
                  {
                    if( $mainOT->payment_date == ''){
                      $payment_date ='';
                    }
                    else{
                      $payment_date =date('d.m.Y', strtotime($mainOT->payment_date));
                    }
                    array_push($info, $payment_date);
                  }
                  if(in_array( 'trnscd',$sltcol))
                  {
                    array_push($info, $mainOT->wage_type);
                  }
                  if(in_array( 'dytype',$sltcol))
                  {
                    try {
                      $dtype = $mainOT->daytype->description;
                    } catch (\Exception $e) {
                      $dtype = $mainOT->daytype_id;
                    }
                    array_push($info, $dtype);
                  }

            }
            array_push($otdata, $info);
          }

          //endforeach

          }
             // dd($otdata,$headers);
             // dd($otdata);
           // Log::info('process date ' . $value->format('Y-m-d'));

           if($this->btnsrh == 'gexcelm'){
             $sh = 'SummaryOTReport';
           }elseif ($this->btnsrh == 'gexceld') {
             $sh = 'OvertimeDetailsReport';
           }
         $eksel->addSheet($sh, $otdata, $headers);
         $eksel->removesheet();
         $eksel->saveToPerStorage();

         $bjob->status = 'Completed';
         // $bjob->attachment = $eksel->getBinary();
         $bjob->remark = $fname;
         $bjob->completed_at = now();
         $bjob->save();
       }
     }
   }
