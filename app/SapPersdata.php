<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SapPersdata extends Model
{
    protected $table = 'sap_persdata';
    protected $primaryKey = 'persno'; // or null
    public $incrementing = false;
}
