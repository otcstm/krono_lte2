<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\ProfilePic;
use App\User;
use DateTime;
use Exception;
use \Carbon\Carbon;

class ProfilePicController extends Controller
{

  protected $baseuri = "https://tmoip.tm.com.my/api/t/tm.com.my/era/1.0.0/";
  protected $options = [

    'headers' => ['Authorization' => 'Bearer fab8822f-00b5-38e3-8c58-22ec93d9adae']
  ];

  public function create(Request $req)
  {
    return "success";
  }

  public function getStaffImage($staffno)
  {
    $user = User::where('staff_no', $staffno)->first();
    $uid = 0;
    if ($user) {
      $uid = $user->id;
    }


    return $this->getEraImage($staffno, $uid);
  }


  private function getEraImage($staff_no, $userid)
  {
    $flag1 = 1;
    $expp =  ProfilePic::where('user_id', $userid)->first();
    if ($expp) {
      $dt = Carbon::now();
      $dt = $dt->subDays(0.9);
      $ppdt = Carbon::parse($expp->created_at);
      if ($ppdt >= $dt) {
        $flag1 = 0;
      }
    }

    if ($flag1 == 1) {

      try {
        

        if ($expp) {
          $expp->delete();
        }
        $reclient = new Client(["base_uri" => $this->baseuri . 'profile/image/']);
        $request = $reclient->request('GET', $staff_no, $this->options)->getBody()->getContents();
        $response = response()->make($request, 200);
        $response->header('Content-Type', 'image/jpeg'); // change this to the download content type.
        
        if($userid != 0){
        $pp = new ProfilePic();
        $pp->user_id = $userid;
        $pp->data = $request;
        $pp->save();
        }
      } catch (Exception $e) {
        

        //$blankPicUrl = 'http://ssl.gstatic.com/accounts/ui/avatar_2x.png';
        $blankPicUrl = 'empty.png';
        $contents = file_get_contents($blankPicUrl);
        $response = response($contents)->header('Content-type', 'image/png');
        //$response = $blankPicUrl ;
      }
    } else {

    $i = $expp->data;
    $response = response($i)->header('Content-type', 'image/png');

    }



    return $response;
  }
}
