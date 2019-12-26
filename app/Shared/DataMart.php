<?php

namespace App\Shared;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataMart extends Controller
{
  public static function insertSalary(
      $user_id,
      $upd_sap,
      $start_date,
      $end_date,
      $payscale_type,
      $payscale_area,
      $salary
  ) {
    $s = new Salary;







    }
}
