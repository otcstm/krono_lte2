<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Shared\URHelper;

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

    public function time()
    {
        return $this->belongsTo(OvertimeMonth::class, 'month_id')->withDefault(['hour' => '0', 'minute' => '0', ]);
    }

    public function detail()
    {
        return $this->hasMany(OvertimeDetail::class, 'ot_id');
    }

    public function daytype()
    {
        return $this->belongsTo(DayType::class, 'daytype_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function log()
    {
        return $this->hasMany(OvertimeLog::class, 'ot_id');
    }

    public function file()
    {
        return $this->hasMany(OvertimeFile::class, 'ot_id');
    }

    public function message()
    {
        return $this->hasMany(OvertimeLog::class, 'ot_id');
    }

    public function URecord(){//based on created date
      return $this->belongsTo(UserRecord::class, 'user_records_id');
    }

    // public function URApp(){//based on created date
    //   // <td>{{ $otr->detail->URApp()->ot_hour_exception }}</td>
    //   return URHelper::getUserRecordByDate($this->user_id,$this->date_created);
    // }

    public function SalCap(){//based on application date
      return URHelper::getUserCapping($this->company_id,$this->region);
    }
    public function OTStatus(){
      return URHelper::getOTStatus($this->status);
    }
    public function OTLog(){
      return URHelper::getOTLog($this->id);
    }




}
