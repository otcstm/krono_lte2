<?php

namespace App\Shared;
use App\User;
use App\UserRecord;
use App\OvertimeEligibility;
use App\StaffPunch;
use App\SetupCode;
use App\OvertimeLog;

class URHelper
{
    public static function getTotalMinutes($hour, $minute)
    {
        return $hour * 60 + $minute;
    }

    public static function regUser(
        $persno,
        $nic,
        $oic,
        $staffno,
        $name,
        $ou,
        $comp,
        $persarea,
        $perssubarea,
        $empsgroup,
        $empgroup,
        $psgroup,
        $pslvl,
        $birthdate,
        $email,
        $cellno,
        $reptto,
        $empstats,
        $position,
        $costcentr,
        $upd_sap
    ) {

      //User data for current state employee
      //Use in session handling
        $u = User::find($persno);
        if (!$u) {
            $u = new User;
            $u->id = $persno;
        }

        $u->name        = $name;
        $u->email       = $email;
        $u->staff_no    = $staffno;
        $u->persno      = $persno;
        $u->new_ic      = $nic;
        $u->company_id  = $comp;
        $u->orgunit     = $ou;
        $u->persarea    = $persarea;
        $u->perssubarea = $perssubarea;
        $u->reptto = $reptto;


        $u->save();

//User records for hsitorical data
        $ur = new UserRecord;
        $ur->user_id      = $persno;
        $ur->new_ic       = $nic;
        $ur->oic          = $oic;
        $ur->staffno      = $staffno;
        $ur->name         = $name;
        $ur->orgunit      = $ou;
        $ur->company_id   = $comp;
        $ur->persarea     = $persarea;
        $ur->perssubarea  = $perssubarea;
        $ur->empsgroup    = $empsgroup;
        $ur->empgroup     = $empgroup;
        $ur->psgroup      = $psgroup;
        $ur->pslvl        = $pslvl;
        $ur->birthdate    = $birthdate;
        $ur->email        = $email;
        $ur->reptto       = $reptto;
        $ur->empstats     = $empstats;
        $ur->position     = $position;
        $ur->costcentr    = $costcentr;
        $ur->upd_sap      = $upd_sap;
        $ur->save();

        return $ur->user_id;

      }

      public static function getUserHistory( $persno)
      {
        $ur = UserRecord::where('user_id',$persno)->get();
        return $ur;

      }
// Return the latest user reord for given persno and date
      public static function getUserRecordByDate( $persno,$dt)
      {
        $urMaxDate = UserRecord::where('user_id',$persno)->where('upd_sap','<=',$dt)->max('upd_sap');
        $ur = UserRecord::where('user_id',$persno)->where('upd_sap','=',$urMaxDate)->get()->first();
        return $ur;
      }
// Return the user reord for given persno
      public static function getUser( $persno)
      {
        $u = User::where('id','=',$persno)->get();
        return $u;
      }


      public static function getUserCapping( $comp,$reg)
      {
        $salcapping = OvertimeEligibility::where('company_id',$comp)->where('region',$reg)->first();
        return $salcapping;
      }

      public static function getLocation( $userid,$pintime)
      {
        $loc = StaffPunch::where('user_id',$userid)->where('punch_in_time','=',$pintime)->first();
        return $loc;
      }

      public static function getOTStatus($code)
      {
        $st = SetupCode::where('item1','ot_status')->where('item2',$code)->first();
        return $st;
      }

      public static function getOTLog($otid)
      {
        $otlog = OvertimeLog::where('ot_id',$otid)
        ->where('message','like',"%Created draft%")
        ->first();
         // dd($otlog);
        return $otlog;

      }



      // public static function getStatusPIO( $persno,$st,$et)//get claim status for every single punch in/out
      // {
      //   $applyOT = OvertimeDetail::where('user_id',$persno)->where('start_time','=',$st)->where('end_time','=',$et)->get()->first();
      //   return $applyOT;
      // }



}
