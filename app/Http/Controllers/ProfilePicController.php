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

 // protected $baseuri = "https://tmoip.tm.com.my/api/t/tm.com.my/era/1.0.0/";
 // protected $baseuri = "https://apigw.dev.tmoip.tm.com.my/t/tm.com.my/era/1.0.0/";
 static $options = [
   'verify' => false,
    'headers' => ['Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6Ik5UZG1aak00WkRrM05qWTBZemM1TW1abU9EZ3dNVEUzTVdZd05ERTVNV1JsWkRnNE56YzRaQT09In0.eyJhdWQiOiJodHRwOlwvXC9vcmcud3NvMi5hcGltZ3RcL2dhdGV3YXkiLCJzdWIiOiJUTUxEQVBcL1M1MjMxMUB0bS5jb20ubXkiLCJhcHBsaWNhdGlvbiI6eyJvd25lciI6IlRNTERBUFwvUzUyMzExQHRtLmNvbS5teSIsInRpZXJRdW90YVR5cGUiOiJyZXF1ZXN0Q291bnQiLCJ0aWVyIjoiVW5saW1pdGVkIiwibmFtZSI6Ik5FTyIsImlkIjo2NSwidXVpZCI6bnVsbH0sInNjb3BlIjoiYW1fYXBwbGljYXRpb25fc2NvcGUgZGVmYXVsdCIsImlzcyI6Imh0dHBzOlwvXC9hZG1pbi5kZXYudG1vaXAudG0uY29tLm15OjQ0M1wvb2F1dGgyXC90b2tlbiIsInRpZXJJbmZvIjp7IkZyZWUiOnsidGllclF1b3RhVHlwZSI6InJlcXVlc3RDb3VudCIsInN0b3BPblF1b3RhUmVhY2giOnRydWUsInNwaWtlQXJyZXN0TGltaXQiOi0xLCJzcGlrZUFycmVzdFVuaXQiOiJOQSJ9fSwia2V5dHlwZSI6IlNBTkRCT1giLCJzdWJzY3JpYmVkQVBJcyI6W3sic3Vic2NyaWJlclRlbmFudERvbWFpbiI6InRtLmNvbS5teSIsIm5hbWUiOiJFUkEtR0hDTSIsImNvbnRleHQiOiJcL3RcL3RtLmNvbS5teVwvZXJhXC8xLjAuMCIsInB1Ymxpc2hlciI6ImFkbWluQHRtLmNvbS5teSIsInZlcnNpb24iOiIxLjAuMCIsInN1YnNjcmlwdGlvblRpZXIiOiJGcmVlIn1dLCJjb25zdW1lcktleSI6IjllR1JYWlNuMl9oS0dDTlRhR1FmMDJCUmVWc2EiLCJleHAiOjM3NTI1NjQzNzIsImlhdCI6MTYwNTA4MDcyNSwianRpIjoiZDg2ZTFhNDktNDc3OC00MGU2LWJhZGQtZWIxZjEyYTQzOWY5In0.NuTtYGrq8bSY87yRbzJ0tNe_NMfEeQ4eamqPQ6SECJxrWxdeA9eckcOqUjn1mO-ilWx3bcyudBJcYjJowWTTnomBUIk52NBbT8u1TObyZ0WMIIRsrV0E00vEc05XwIzNcE533XQUhAsHjmt4ntGjupx8IobWkmNEki64PQfoFZonKUnhLu-L9O2FB30-KajxZzgDozDCegYesr7NXRdOmBm4Kt6DmU_bh01Nxj-d93j8LSrFB8B110pmUhVSDjaW9OaEksY2TVjIaWIsWM7YAZ0ugCAcCS3VlBTZux2g2SFWN7G2NMRe292kgccKmekjWR6m8MsRR66XCgsMxzjD_A']
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
    $dt = Carbon::now();
    $dt = $dt->subHours(0.5);
    //dd($dt);
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
        $uri = env('APIGATE_URI');
        $options = [
          'verify' => false,
           'headers' => ['Authorization' => 'Bearer '. env('APIGATE_KEY')]
         ];
       
        

        if ($expp) {
          $expp->delete();
        }
        $reclient = new Client(["base_uri" => $uri . 'profile/image/']);
        $request = $reclient->request('GET', $staff_no, $options)->getBody()->getContents();
        $response = response()->make($request, 200);
        $response->header('Content-Type', 'image/jpeg'); // change this to the download content type.
        
        if($userid != 0){
        $pp = new ProfilePic();
        $pp->user_id = $userid;
        $pp->data = $request;
        $pp->save();
        }
      } catch (Exception $e) {
        
        dd($e);
        $blankPicUrl = 'empty.png';
        $contents = file_get_contents($blankPicUrl);
        $response = response($contents)->header('Content-type', 'image/png');

      }
    } else {

    $i = $expp->data;
    $response = response($i)->header('Content-type', 'image/png');

    }

    return $response;
  }
}
