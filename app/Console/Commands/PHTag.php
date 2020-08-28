<?php

namespace App\Console\Commands;

use App\Shared\UserHelper;
use App\Shared\URHelper;
use App\User;
use App\UserRecord;
use App\DayType;
use App\DayTag;
use App\WsrChangeReq;
use App\UserShiftPattern;
use App\ShiftPlan;
use App\ShiftPlanStaffDay;
use App\ShiftPattern;
use App\Holiday;
use App\HolidayCalendar;
use Illuminate\Console\Command;

class PHTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tag:ph';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tag PH day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $user = User::all(); //set user
        // $tagPH = new DayTag;
        // $tagPH->user_id = 1;
        // $tagPH->date = date("Y-m-d")." 00:00:00";
        // $tagPH->status = "ACTIVE";
        // $tagPH->save();

        // $user = User::where('id', 45614)->get();    //set all users
        $user = User::where('staff_no', 'TM32434')->orWhere('staff_no', 'TM33115')->orWhere('staff_no', 'TM34407')->orWhere('staff_no', 'B14882')->get();
        $today = date("Y-m-d"); 
        $startdate = date("Y-m-d", strtotime($today. "-90 days"));
        foreach($user as $us){
            for($i = 0; $i <= 180; $i++){
                $ph = null;
                $hc = null;
                $hcc = null;
                $wd = null;
                $statuschange = false;
                $date = date("Y-m-d", strtotime($startdate. "+". $i. " days"));
                $day = date('N', strtotime($date));
                $ushiftp = UserHelper::GetUserShiftPatternSAP($us->id, date('Y-m-d', strtotime($date." 00:00:00")));
                $shiftpattern = ShiftPattern::where('code', $ushiftp)->first();
                if($shiftpattern->is_weekly != 1){
                    $wd = ShiftPlanStaffDay::where('user_id', $us->id)
                    ->whereDate('work_date', $date)->first();
                    if($wd){
                        $sp = ShiftPlan::where("id", $wd->shift_plan_id)->first();
                        if($sp){
                            if($sp->status=="Revert"){
                                $statuschange = true;
                            }else if($sp->status!="Approved"){
                                $wd = null;
                            }
                        }else{
                            $wd = null;
                        }
                    }
                }
                if($wd){
                } else {
                    // not a shift staff. get based on the wsr
                    $currwsr = UserHelper::GetWorkSchedRule($us->id, $date);
                    // then get that day
                    $wd = $currwsr->ListDays->where('day_seq', $day)->first();
                };
                $idday = $wd->day_type_id;
                $dy = DayType::where('id', $idday)->first();
                $ph = Holiday::where("dt", date("Y-m-d", strtotime($date)))->first();
                if($ph!=null){
                    $hcc = HolidayCalendar::where('holiday_id', $ph->id)->get();
                }
                if($hcc){
                    if(count($hcc)!=0){
                        $userstate = URHelper::getUserRecordByDate($us->id,$date);
                        foreach($hcc as $phol){
                            $hc = HolidayCalendar::where('id', $phol->id)->first();
                            if($hc->state_id == $userstate->state_id){
                                break;
                            }else{
                                $hc = null;
                            }
                        }
                    }
                }
                if($hc){  //if public holiday exzist
                    if($dy->day_type!="O"){
                        $existTag = DayTag::where('user_id', $us->id)->where('date', $date)->first();
                        if(!($existTag)){
                            $tagPH = new DayTag;
                            $tagPH->user_id = $us->id;
                            $tagPH->date = $date;
                            $tagPH->phdate = $date;
                            $tagPH->status = "ACTIVE";
                            $tagPH->save();
                        }else{
                            $tagPH = DayTag::find($existTag->id);
                            if($statuschange){
                                $tagPH->status = "INACTIVE";
                                $tagPH->save();
                            }else{
                                $tagPH->status = "ACTIVE";
                                $tagPH->save();
                            }
                        }
                    }else{
                        $dt = $dy->day_type;
                        $x = 1;
                        $existTag = null;
                        while(($dt=="O")||($dt=="R")||($dt=="PH")||($existTag)){
                            $existTag = null;
                            $wd2 = null;
                            $date2 = date("Y-m-d", strtotime($date. "+".$x." days"));
                            $day = date('N', strtotime($date2));
                            $ushiftp = UserHelper::GetUserShiftPatternSAP($us->id, date('Y-m-d', strtotime($date2." 00:00:00")));
                            $shiftpattern = ShiftPattern::where('code', $ushiftp)->first();
                            if($shiftpattern->is_weekly != 1){
                                $wd2 = ShiftPlanStaffDay::where('user_id', $us->id)
                                ->whereDate('work_date', $date2)->first();
                                if($wd2){
                                    $sp2 = ShiftPlan::where("id", $wd2->shift_plan_id)->first();
                                    if($sp2){
                                        if($sp->status=="Revert"){
                                        }else if($sp->status!="Approved"){
                                            $wd2 = null;
                                        }
                                    }else{
                                        $wd = null;
                                    }
                                }
                            }
                            if($wd2){
                            } else {
                                // not a shift staff. get based on the wsr
                                $currwsr = UserHelper::GetWorkSchedRule($us->id, $date2);
                                // then get that day
                                $wd2 = $currwsr->ListDays->where('day_seq', $day)->first();
                            };
                            
                            $dx = DayType::where('id', $wd2->day_type_id)->first();
                            $dt = $dx->day_type;
                            $existTag = DayTag::where('user_id', $us->id)->where('phdate', $date)->first();
                            $x++;
                        }
                        if(!($existTag)){
                            $tagPH = new DayTag;
                            $tagPH->user_id = $us->id;
                            $tagPH->date = $date2;
                            $tagPH->phdate = $date;
                            // $tagPH->status = $dt;
                            $tagPH->status = "ACTIVE";
                            $tagPH->save();
                        }
                    }
                }else{  //if public holiday cancel
                    
                    $existTag = DayTag::where('user_id', $us->id)->where('phdate', $date)->first();
                    if($existTag){
                        $tagPH = DayTag::find($existTag->id);
                        $tagPH->status = "INACTIVE";
                        $tagPH->save();
                    }
                }
            }
        }
    }
}
