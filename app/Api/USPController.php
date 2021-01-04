<?php

namespace app\Api;
//use App\Api\Datamart;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller as BaseController;
use App\UserShiftPattern;
use App\Shared\UserHelper;
use App\DayTag;



use Illuminate\Http\Request;

class USPController extends BaseController {

    public function getUserShiftPattern($persno,$dt)
    {   
        $usp = UserShiftPattern::where('user_id',$persno)
        ->where('start_date','<=',$dt)
        ->orderBy('upd_sap','desc')
        ->first();

        $dayTag = DayTag::where('user_id',$persno)
        ->where('date',$dt)
        ->get();

        $checkDay = UserHelper::CheckDay($persno,$dt);
        $cd = [
        "dayType" => $checkDay[2],
        "dayId" => $checkDay[4],
  
        ];
        if($checkDay[10]){
            $cd = $cd + ['phID' => $checkDay[10]->id];
            $cd = $cd + ['phDescr' => $checkDay[10]->descr];
        }


        $result = [
            "persno"=> $persno,
            "checkDay"=> $cd,
            "shift_patterns" => $usp,
            "day_tag" => $dayTag
            
        ];
        
        return $result ;
    }



}