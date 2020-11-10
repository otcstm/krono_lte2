<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewOtRpt1 extends Model
{
    //
    protected $table = 'v_ot_rpt1';

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id')->withDefault(['name' => 'N/A']);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id')->withDefault(['name' => 'N/A']);
    }
    public function OTStatus()
    {
        $st = SetupCode::where('item1','ot_status')->where('item2',$this->status)->first();
        return $st;

    }

    public function detail()
    {
        return $this->hasMany(OvertimeDetail::class, 'ot_id')->get();
    }

   
}
