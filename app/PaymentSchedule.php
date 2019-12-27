<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentSchedule extends Model
{
  protected $dates = ['last_sub_date', 'last_approval_date', 'interface_date', 'payment_date', ];

  public function payrollgroupid()
  {
      return $this->belongsTo(Payrollgroup::class,'payrollgroup_id');
  }
}
