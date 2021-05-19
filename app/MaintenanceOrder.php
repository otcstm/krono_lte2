<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaintenanceOrder extends Model
{
    public $incrementing = false;

    public function name()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
