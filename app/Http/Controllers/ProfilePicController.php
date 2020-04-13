<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\ProfilePic;
use App\User;
use Exception;

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

  public function getStaffImage($staffno){  
    $user = User::where('staff_no',$staffno)->first();
    $uid = 0;
    if($user){$uid = $user->id;}

    return $this->getEraImage($staffno, $uid);
  }


  private function getEraImage($staff_no, $userid)
  {
    $response = "";
    try{
    $reclient = new Client(["base_uri" => $this->baseuri . 'profile/image/']);
    $request = $reclient->request('GET', $staff_no, $this->options)->getBody()->getContents();
    $response = response()->make($request, 200);
    $response->header('Content-Type', 'image/jpeg'); // change this to the download content type.
    
    $expp =  ProfilePic::where('user_id',$userid);
    if($expp){  $expp->delete();}


    $pp = new ProfilePic();
    $pp->user_id = $userid;
    $pp->data = $request;

    $pp->save();
    

    }
    catch (Exception $e)
    {
      dd($e);
      
      $blankPicUrl = 'http://ssl.gstatic.com/accounts/ui/avatar_2x.png';
      $contents = file_get_contents($blankPicUrl);
      $response = response($contents)->header('Content-type','image/png');
      


    }


    
    return $response;

  }
}
