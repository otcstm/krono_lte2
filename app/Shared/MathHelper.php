<?php

namespace App\Shared;

class MathHelper {
  public static function getTotalMinutes($hour, $minute){
    return $hour * 60 + $minute;
  }
}
