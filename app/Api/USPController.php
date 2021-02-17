<?php

namespace app\Api;

//use App\Api\Datamart;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;
use App\UserShiftPattern;
use App\ShiftPattern;
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

    public function getUserShiftPattern($persno, $dt){
        $spsd = ShiftPlanStaffDay::with('Day')->where('user_id', $persno)
        ->where('work_date', '=', $dt)->first();
        $usr=User::find($persno);
        $ph=HolidayCalendar::with('holiday')
        ->where('state_id',$usr->state_id)

        ->get();
        $hol = Holiday::where('dt',$dt)
        ->whereHas('holCal', function ($query) use ($usr) {
            $query->where('state_id',$usr->state_id);
        })->get();
        
       // has('holCal.state_id',$usr->state_id)
       // ->get();
       //dd($ph);






        $check ="";
        $day_code="";
        $is_work_day ="1";
        $expected_work_hour = "";

        if($spsd) {
            $is_work_day = $spsd->is_work_day ;
            $day_code = $spsd->Day->code ;
            $expected_hour = $spsd->Day->working_hour ;
            

        }

        $check = [
            "persno"=>$persno,
            "is_work_day" => $is_work_day,
            "day_code" => $day_code,
            "expected_hour"=>$expected_hour
        ] ;

        

        $result = [
            "check"=>$check,
            "spsd"=>$spsd,
            "hol"=>$hol,
            "ph"=>$ph,
            
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
            if($checkDay){
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
