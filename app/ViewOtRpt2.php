<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewOtRpt2 extends Model
{
    //
    protected $table = 'v_ot_rpt2';
    protected $primaryKey = 'id'; // or null

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


   
}
