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
use App\StaffPunch;
use \Carbon\Carbon;

class ReportOTSE implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bjobid;
    // protected $reqinfo;
    protected $start_date;
    protected $end_date;
    protected $perno;
    protected $comp;
    protected $state;
    protected $region;
    protected $pilihcol;
    // protected $btnsrh;

    public $tries = 1;
    public $timeout = 7200;

    public function __construct($sdt,$edt,$per,$co,$st,$re,$col)
    {
      $this->start_date = $sdt;
      $this->end_date = $edt;
      $this->perno = $per;
      $this->comp = $co;
      $this->state = $st;
      $this->region = $re;
      $this->pilihcol = $col;
      // $this->btnsrh = $btn;

      $bjob = new BatchJob;
      $bjob->job_type = 'StartEndOTTimeReport';
      $bjob->status = 'Queued';
      $bjob->from_date = $sdt;
      $bjob->to_date = $edt;
      $bjob->remark = 'StartEndOTTimeReport';
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

        // Log::info('build filename');
        $fname = 'StartEndOTTimeReport_' . $noww->format('YmdHis') . '.xlsx';

        $cdate->addSecond();
        // Log::info('prep header');
        $headers = ['Personnel Number','Employee Name','IC Number',
        'Staff ID','Company Code','Date','Start Time','End Time'];

        $sltcol = $this->pilihcol;

        if(isset($sltcol)){
          if(in_array('psarea',$sltcol ))
            {
              array_push($headers, 'Personnel Area');
            }
            if(in_array( 'psbarea',$sltcol))
            {
              array_push($headers, 'Personnel Subarea');
            }
            if(in_array( 'state',$sltcol))
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
            if(in_array( 'dytype',$sltcol))
            {
              array_push($headers, 'Day Type');
            }
            if(in_array( 'loc',$sltcol))
            {
              array_push($headers, 'Location');
            }
            if(in_array( 'claim',$sltcol))
            {
              array_push($headers, 'Apply OT Claim?');
            }
        }

        set_time_limit(0);

        $otr = StaffPunch::query();

        if(isset($this->start_date)){
          $otr = $otr->whereBetween('punch_in_time', array($this->start_date, $this->end_date));
        }
        if(isset($this->perno)){
          $otr = $otr->whereIn('user_id',$this->perno);
        }
        $otrStEd = $otr->get();
          // ->get();

          // Log::info($otr->toSql());
          // $otr = $otr->get();
          // Log::info($otr->count());

        foreach ($otrStEd as $key => $StEd){
            // cari profile user ni
            // dd($otrStEd);
            $rekodpengguna = $StEd->URecord;
            $rekodregion = $StEd->URecord->Reg;

            // if($this->reqinfo->filled('fcompany'))
            if(isset($this->comp)){
              // dd('ada input comp')
              if(in_array($rekodpengguna->company_id, $this->comp)){
                // echo("$rekodpengguna->user_id,$rekodpengguna->company_id ,company true <br/>");
              } else {
                // dd($otrStEd);
              // if($rekodpengguna->company_id != $req->comp_no){
                unset($otrStEd[$key]);
                continue;
              }
             }

             // if($this->reqinfo->filled('fstate'))
             if(isset($this->state)){
               if(in_array($rekodpengguna->state_id, $this->state)){
             } else {
                 unset($otrStEd[$key]);
               continue;
               }
             }
             // if($this->reqinfo->filled('fregion'))
             if(isset($this->region)){
               if(in_array($rekodregion->region, $this->region)){
               } else {
                 unset($otrStEd[$key]);
                 continue;
               }
             }

             $ot = \DB::table('overtimes')
             ->join('overtime_details','overtime_details.ot_id','=','overtimes.id')
             ->where('overtimes.user_id',$StEd->user_id)
             ->where('overtime_details.start_time',$StEd->punch_in_time)
             ->where('overtime_details.end_time',$StEd->punch_out_time)
             ->where('overtime_details.checked','Y')->first();

             if($ot){
                 $StEd->ot_applied='Yes';
               }else{
                 $StEd->ot_applied='No';
             }
        }//endforeach

        $otdata = [];
        $eksel = new ExcelHandler($fname);

        foreach ($otrStEd as $value) {
          $urekod = $value->URecord;
          $pdt = new Carbon($value->punch_in_time);
          $pdt = $pdt->format('d.m.Y');
          $pi = new Carbon($value->punch_in_time);
          $pi = $pi->format('H:i:s');
          $po = new Carbon($value->punch_out_time);
          $po = $po->format('H:i:s');

          $info = [$value->user_id,$urekod->name,$urekod->new_ic,
          $urekod->staffno,$urekod->company_id,$pdt,$pi,$po];
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
                array_push($info, $urekod->state_id);
              }
              if(in_array( 'region',$sltcol))
              {
                array_push($info, $urekod->region);
              }
              if(in_array( 'empgrp',$sltcol))
              {
                array_push($info, $urekod->empgroup);
              }
              if(in_array( 'empsubgrp',$sltcol))
              {
                array_push($info, $urekod->empsgroup);
              }
              if(in_array( 'dytype',$sltcol))
              {
                array_push($info, $value->day_type);
              }
              if(in_array( 'loc',$sltcol))
              {
                array_push($info, '('.$value->in_latitude.','.$value->in_longitude.')');
              }
              if(in_array( 'claim',$sltcol))
              {
                array_push($info, $value->ot_applied);
              }
          }

          array_push($otdata, $info);
        }

        $eksel->addSheet('StartEndOTTime', $otdata, $headers);
        $eksel->removesheet();
        $eksel->saveToPerStorage();

        $bjob->status = 'Completed';
        // $bjob->attachment = $eksel->getBinary();
        $bjob->remark = $fname;
        $bjob->completed_at = now();
        $bjob->save();
       }//end Queued
    }
}
