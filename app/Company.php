<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
  protected $primaryKey = 'id'; // or null

  public $incrementing = false;

  //use SoftDeletes;

public function users()
  {
    return $this->belongsTo(User::class);
  }

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

}
