<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Psubarea extends Model
{
  use SoftDeletes;

  public function users()
    {
      return $this->belongsTo(User::class);
    }

    public function createdby()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function updatedby()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function companyid()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function stateid()
    {
        return $this->belongsTo(State::class,'state_id');
    }



}
