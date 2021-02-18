<?php

namespace app\Api;

//use App\Api\Datamart;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;
use App\UserShiftPattern;
use App\ShiftPattern;
use App\ShiftPatternDay;
use App\ShiftPlanStaffDay;
use App\Shared\UserHelper;
use App\DayTag;
use App\DayType;
use App\HolidayCalendar;
use App\User;
use App\Holiday;




use Illuminate\Http\Request;

class USPController extends BaseController
{
    public function getUserShiftPattern($persno, $dt)
    {
        $check ="";
        $day_code="";
        $day_id="";
        $day_descr="";
        $is_work_day ="1";
        $expected_hour = "";
        $wsr=null;
        $hol= null;
        $dayType=null;
        $shift_code=null;
        $shift_descr=null;

        /*
        1) if exist in shift plan staff day evaluate and exit
        2) if not exist in spsd evaluate public holiday
        3) if not public holiday evaluate wsr
        */
   
        /*check shift plan staff day if the day is recorded for this staff*/
        $spsd = ShiftPlanStaffDay::with('Day')->where('user_id', $persno)
        ->where('work_date', '=', $dt)->first();
        $usr=User::find($persno);
        

        if ($spsd) {
            $is_work_day = $spsd->is_work_day ;         
            
        } else {
            $hol = Holiday::where('dt', $dt)
            ->whereHas('holCal', function ($query) use ($usr) {
                $query->where('state_id', $usr->state_id);
            })
            ->with(['holCal' => function ($query) use ($usr) {
                $query->where('state_id', $usr->state_id);
            }])->first();            
            
        }

        if($hol){
            $dayType = DayType::where('code','PH')->first();
        }else {
            $wsr = UserHelper::GetWorkSchedRule($persno, $dt);
            //dd($dayType);

        }

        if($wsr){
            $dayType = DayType::where('code',$wsr->code)->first();
            $day = date('N', strtotime($dt));
            $spd = ShiftPatternDay::where('shift_pattern_id',$wsr->id)
            ->where('day_seq',$day)->first();

            $dayType = DayType::find($spd->day_type_id);

            //dd($wsr);
        }

        

        if ($dayType) {
            $day_id = $dayType->id ;
            $day_code = $dayType->code ;
            $day_descr= $dayType->description ;
            $expected_hour = $dayType->expected_hour ;
            $is_work_day = $dayType->is_work_day ;
        }


        $check = [
            "persno"=>$persno,
            "date"=>$dt,
            "is_work_day" => $is_work_day,
            "day_id"=>$day_id,
            "day_descr" => $day_descr,
            "day_code" => $day_code,
            "expected_hour"=>$expected_hour,
            
        ] ;

        

        $result = [
            "check"=>$check,
            "wsr"=>$wsr,
            "daytype"=>$dayType,
            "spsd"=>$spsd,
            "hol"=>$hol,



            
        ];

        

        return $result;
    }

    public function getUserShiftPatternBak($persno, $dt)
    {
        $usp = UserShiftPattern::with('shiftpattern')
        ->where('user_id', $persno)
        ->where('start_date', '<=', $dt)
        ->orderBy('upd_sap', 'desc')
        ->first();
      
 

        $dayTag = DayTag::where('user_id', $persno)
        ->where('date', $dt)
        ->get();

        $checkDay = null;
        $cd = null;
        $dayType = null;

        try {
            $checkDay = UserHelper::CheckDay($persno, $dt);
            if ($checkDay) {
                $dayType = DayType::find($checkDay[3]);
            }

            //[0] Start work time
            //[1] End work time
            //[2] Day type
            //[3] Day name
            //[4] Day id
            //[5] End of day cycle
            //[6] Is same day or not
            //[7] Date
            //[8] Start day
            //[9] Work day

            $cd = [
            "dayType" => $checkDay[2],
            "is_work_day" => $checkDay[9],
            "dayId" => $checkDay[4],
            "start_work_time"=>$checkDay[0],
            "end_work_time"=>$checkDay[1],
      
            ];
            if ($checkDay[10]) {
                $cd = $cd + ['phID' => $checkDay[10]->id];
                $cd = $cd + ['phDescr' => $checkDay[10]->descr];
            }
        } catch (\FatalThrowableError $t) {
        } catch (\Exception $e) {
        }


        

        $result = [
            "persno"=> $persno,] ;
        if ($checkDay) {
            $result = $result + [  "checkDay"=> $cd];
            $result = $result + [  "checkDay2"=> $checkDay];
            $result = $result + [  "daytype"=> $dayType];
        };

        if ($usp) {
            $result = $result + [  "shift_patterns" => $usp, ];
        };
           
        if ($dayTag) {
            $result = $result + ["day_tag" => $dayTag];
        }
       
        return $result ;
    }
}
