<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    // public function punchList()
    // {
    //     return $this->hasMany(StaffPunch::class);
    // }

    public function otdetails()
    {
        return $this->belongsToMany(OvertimeDetail::class);
    }
}
