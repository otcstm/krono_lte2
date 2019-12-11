<?php

namespace App\Shared;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataMart extends Controller
{
  public static function insertSalary($hour, $minute)
  {
      return $hour * 60 + $minute;
  }
}
