<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// use Illuminate\Support\Facades\Log;

use App\ExcelHandler;
use App\BatchJob;
use \DB;
use App\Overtime;
use App\OvertimeDetail;
use App\OvertimeLog;
use App\StaffPunch;
use \Carbon\Carbon;

class ReportOTLC implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bjobid;
    protected $start_date;
    protected $end_date;
    protected $perno;
    protected $ref_num;

    public $tries = 1;
    public $timeout = 7200;

    public function __construct($sdt,$edt,$per,$rno)
    {
      $this->start_date = $sdt;
      $this->end_date = $edt;
      $this->perno = $per;
      $this->ref_num = $rno;

      $bjob = new BatchJob;
      $bjob->job_type = 'OTLogChangesReport';
      $bjob->status = 'Queued';
      $bjob->from_date = $sdt;
      $bjob->to_date = $edt;
      $bjob->remark = 'OTLogChangesReport';
      // dd('here');
      $bjob->save();

      $this->bjobid = $bjob->id;

    }

    public function handle()
    {
      $bjob = BatchJob::find($this->bjobid);
      // dd($bjob);
      if($bjob && $bjob->status == 'Queued'){
        $bjob->status = 'Processing';
        $bjob->save();
        // Log::info('batch job id: ' . $this->bjobid);
        // Log::info('start date: ' . $this->start_date);
        // Log::info('end date: ' . $this->end_date);


        $cdate = new Carbon($this->end_date);
        $ldate = new Carbon($this->start_date);
        $noww = Carbon::now();

        $fname = 'OTLogChangesReport_' . $noww->format('YmdHis') . '.xlsx';

        $cdate->addSecond();
        $headers = ['Reference Number','Personnel Number','Employee Name','IC Number','Staff ID',
        'Action Date','Action Time','Action By','Action Log','Remarks'];

        set_time_limit(0);
        $otr = Overtime::query();
        if(isset($this->start_date)){
          $otr = $otr->whereBetween('date', array($this->start_date, $this->end_date));
        }
        if(isset($this->perno)){
          $otr = $otr->whereIn('user_id',$this->perno);
        }
        if(isset($this->ref_num)){
          $otr = $otr->where('refno', 'LIKE', '%' .$this->ref_num. '%');
        }
        $otr = $otr->get();
        $list_of_id = $otr->pluck('id');
        $otlog = OvertimeLog::whereIn('ot_id', $list_of_id)->where('action','not like',"%Created draft%")->get();
        // Log::info($otlog->toSql());
        // $otlog = $otlog->get();
        // Log::info($otlog->count());


        $otid = $otlog->pluck('ot_id');
        $just = OvertimeDetail::whereIn('ot_id', $otid)->where('checked','=','Y')->get();

        $otdata = [];
        $eksel = new ExcelHandler($fname);

        foreach($otlog as $value){
          // dd($value);
          $urekod = $value->detail->URecord;
          $detail = $value->detail;
          $cdate = date('d.m.Y', strtotime($value->created_at));
          $ctime = date('H:i:s', strtotime($value->created_at));
          $msgarry= [];
          foreach($just as $justi){
            if($justi->ot_id==$value->ot_id){
              $msg = $justi->justification;
              array_push($msgarry,$msg);
            }
          }
          // dd($msgarry);


          if($value->action =='Submitted'){
            $remark= "Submitted with justification :";
          }else{
            $remark=$value->message;
          }
          // dd($act);
          $info = [
            $detail->refno,
            $detail->user_id,
            $urekod->name,
            $urekod->new_ic,
            $urekod->staffno,
            $cdate,
            $ctime,
            $value->user_id,
            $value->action,
            $remark];

            array_push($otdata, $info);
        }
        $eksel->addSheet('OTLogChanges', $otdata, $headers);
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
