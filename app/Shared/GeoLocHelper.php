<?php

namespace App\Shared;

use App\User;
use App\UserRecord;
use Illuminate\Http\Request;
use GuzzleHttp\Client;



class GeoLocHelper
{
  public static $baseuri = "https://tmoip.tm.com.my/api/t/tm.com.my/geosmartmap/1.0.0";
  public static $options = [

    'headers' => ['Custom' => 'Bearer 1f189981-5481-3c1f-aaee-283f16981eda']
  ];
  #'headers' => ['Custom' => 'Bearer fab8822f-00b5-38e3-8c58-22ec93d9adae']  
  public static function getLocDescr($lat,$lon)
  {
    $uri = self::$baseuri . "/search/reversegeocode";

    $options = [

      'headers' => ['Custom' => 'Bearer 1f189981-5481-3c1f-aaee-283f16981eda'],
      'query' => ['lat' => $lat, 'lon' => $lon]
    ];
  

  

    $reclient = new Client(["base_uri" => $uri]);
    $request = $reclient->request('GET', '',$options)->getBody();
    $response = response()->make($request, 200);
    $response->header('Content-Type', 'application/json'); 
    $ret = json_decode($response->content());
    $collection = collect($ret[0]);
    return  $collection;
  }
}
