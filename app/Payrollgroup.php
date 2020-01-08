<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Payrollgroup extends Model
{
  // use SoftDeletes;

  public function createdby()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
  // public function companies()
  // {
  //   return $this->belongsToMany('App\Company','company_id');
  // }
  public function companyingroup()
  {
    return $this->hasMany(CompanyPayrollgroup::class, 'payrollgroup_id');
  }

}
