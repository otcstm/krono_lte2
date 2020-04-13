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

    'headers' => ['Authorization' => 'Bearer 234cf8e6-2887-3ab3-9d71-a1005c254465']
  ];

  public static function getTotalMinutes()
  {
    $uri = self::$baseuri . "search/reversegeocode";

    $reclient = new Client(["base_uri" => $uri]);
    //$request = $reclient->request('GET', self::$options)->getBody()->getContents();
    //$response = response()->make($request, 200);
    //$response->header('Content-Type', 'image/jpeg'); // change this to the download content type.

    return $uri;
  }
}
