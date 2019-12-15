<?php

namespace App\Http\Controllers\Datamart;

use Illuminate\Http\Request;

class SalaryController extends Controller
{
  public static function insert(
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
