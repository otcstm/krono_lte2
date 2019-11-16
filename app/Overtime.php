<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    // public function punchList()
    // {
    //     return $this->hasMany(StaffPunch::class);
    // }

    // public function otdetails()
    // {
    //     return $this->belongsToMany(OvertimeDetail::class);
    // }

    public function name()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id')->withDefault(['name' => 'N/A']);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id')->withDefault(['name' => 'N/A']);
    }

    public function detail()
    {
        return $this->hasMany(OvertimeDetail::class, 'ot_id');
    }

    public function log()
    {
        return $this->hasMany(OvertimeLog::class, 'ot_id');
    }

    public function message()
    {
        return $this->hasMany(OvertimeLog::class, 'ot_id');
    }
}
