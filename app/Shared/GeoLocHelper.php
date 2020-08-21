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

    'headers' => ['Custom' => 'Bearer 9b2a6889-16db-38ca-b18d-280f191f2be6']
  ];
  #'headers' => ['Custom' => 'Bearer 9b2a6889-16db-38ca-b18d-280f191f2be6']  
  public static function getLocDescr($lat,$lon)
  {
    $uri = self::$baseuri . "/search/reversegeocode";

    $options = [

      'headers' => ['Custom' => 'Bearer 9b2a6889-16db-38ca-b18d-280f191f2be6'],
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
