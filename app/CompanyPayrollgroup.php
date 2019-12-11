<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyPayrollgroup extends Model
{
  protected $dates = ['start_date',  'end_date', ];
  public function companyid()
  {
      return $this->belongsTo(Company::class,'company_id')->withDefault(['company_descr' => 'NULL']);
  }
}
