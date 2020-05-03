<?php

namespace app\Api\Batch;

use Illuminate\Routing\Controller as BaseController;
use \Carbon\Carbon;
use App\User;
use App\ReminderJob;
use App\ReminderJobDetail;
use App\Overtime;
use App\Notifications\WeeklyReminder;

class ReminderController extends BaseController
{
  public function process()
  {
    $now = new Carbon;
    $week = $now->weekOfYear;
    $year = $now->year;

    // create new job
    $job = new ReminderJob;
    $job->start_time = $now;
    $job->week = $week;
    $job->year = $year;
    $job->save();
    $reccount = 0;

    // job content starts here ===============

    // fetch list of users
    $mereka = $this->getListToSend();

    $fuuser = new Carbon;
    $job->fetched_users_time = $fuuser;
    $job->expected_count = sizeof($mereka);

    foreach($mereka as $oid){
      $me = User::find($oid);

      // check to prevent dup / resend
      $dup = ReminderJobDetail::where('user_id', $me->id)
        ->where('week', $week)
        ->where('year', $year)
        ->first();

      if($dup){
        // no need to resend
        continue;
      }

      $data = $this->getNotifyData($me->id);

      $rjobdetail = new ReminderJobDetail;
      $rjobdetail->user_id = $me->id;
      $rjobdetail->week = $week;
      $rjobdetail->year = $year;
      $rjobdetail->email = $me->email;
      $rjobdetail->email_data = json_encode($data);
      $rjobdetail->reminder_job_id = $job->id;
      $rjobdetail->save();


      $me->notify(new WeeklyReminder($rjobdetail));
      $reccount++;
      $job->processed_count = $reccount;
      $job->save();
    }


    // job content ends here =================

    // finalize job
    $siap = new Carbon;
    $job->complete_time = $siap;
    $job->processed_count = $reccount;
    $job->save();

    return $job;

  }

  private function getListToSend(){
    // draft and query
    $dq = Overtime::whereIn('status', array('D1', 'D2','Q1', 'Q2'))->pluck('user_id')->toArray();

    // verifer
    $ver = Overtime::where('status', 'PV')->pluck('verifier_id')->toArray();

    // approver
    $apru = Overtime::whereIn('status', array('PV', 'PA'))->pluck('approver_id')->toArray();

    return array_diff(array_unique(array_merge($dq, $ver, $apru)), [0, null]);
  }

  private function getNotifyData($staff_id){
    $data = [];

    // get draft OT
    $draftCount = Overtime::where('user_id', $staff_id)
      ->whereIn('status', array('D1', 'D2'))->get()->count();

    if($draftCount > 0){
      array_push($data, [
        'type' => 'Unsubmitted Overtime',
        'count' => $draftCount
      ]);
    }

    // get queried OT
    $draftCount = Overtime::where('user_id', $staff_id)
      ->whereIn('status', array('Q1', 'Q2'))->get()->count();

    if($draftCount > 0){
      array_push($data, [
        'type' => 'Queried Overtime',
        'count' => $draftCount
      ]);
    }

    // pending verify
    $verifierCount = Overtime::where('verifier_id', $staff_id)
        ->where('status', 'PV')->get()->count();

    if($verifierCount > 0){
      array_push($data, [
        'type' => 'Pending Verification',
        'count' => $verifierCount
      ]);
    }

    // pending approve
    $verifierCount = Overtime::where('approver_id', $staff_id)
        ->where('status', 'PA')->get()->count();

    if($verifierCount > 0){
      array_push($data, [
        'type' => 'Pending Approval',
        'count' => $verifierCount
      ]);
    }

    // pending at my verifier
    $verifierCount = Overtime::where('approver_id', $staff_id)
        ->where('status', 'PV')->get()->count();

    if($verifierCount > 0){
      array_push($data, [
        'type' => 'Pending At Verifier',
        'count' => $verifierCount
      ]);
    }

    return $data;
  }

}
