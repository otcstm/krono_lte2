<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Costcenter extends Model
{
  public $incrementing = false;
    //

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id')->withDefault(['company_descr' => '']);
    }
}
