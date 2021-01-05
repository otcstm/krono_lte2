<?php

namespace app\Api;

//use App\Api\Datamart;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;
use App\UserShiftPattern;
use App\Shared\UserHelper;
use App\DayTag;



use Illuminate\Http\Request;

class USPController extends BaseController
{
    public function getUserShiftPattern($persno, $dt)
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

        try {
            $checkDay = UserHelper::CheckDay($persno, $dt);
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
