<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
  public function saluser()
    {
      return $this->belongsTo(User::class);
    }

}
