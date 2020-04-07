<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\ProfilePic;
use Exception;

class ProfilePicController extends Controller
{

  protected $baseuri = "https://tmoip.tm.com.my/api/t/tm.com.my/era/1.0.0/";
  protected $options = [
    
    'headers' => ['Authorization' => 'Bearer 234cf8e6-2887-3ab3-9d71-a1005c254465']
  ];

  public function create(Request $req)
  {
return "success";
  }

  public function getStaffImage($staffno){
    

      
  
    return $this->getEraImage($staffno);
  }


  private function getEraImage($staff_no)
  {
    $response = "";
    try{
    $reclient = new Client(["base_uri" => $this->baseuri . 'profile/image/']);
    

    $request = $reclient->request('GET', $staff_no, $this->options)->getBody()->getContents();
    $response = response()->make($request, 200);
    $response->header('Content-Type', 'image/jpeg'); // change this to the download content type.
    }
    catch (Exception $e)
    {
      //dd($e);
      $blankPicUrl = 'http://ssl.gstatic.com/accounts/ui/avatar_2x.png';
      $reclient = new Client(["base_uri" => $blankPicUrl]);
    

      $request = $reclient->request('GET')->getBody()->getContents();
      $response = response()->make($request, 200);
      $response->header('Content-Type', 'image/jpeg'); // change this to the download content type.

      

    }
    return $response;

  }
}
