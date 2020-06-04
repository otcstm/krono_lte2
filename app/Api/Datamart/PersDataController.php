<?php

namespace app\Api\Datamart;
//use App\Api\Datamart;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\User;
use App\UserRecord;
use DateTime;
use \Carbon\Carbon;

use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function list()
    {
        $users = User::all();
        return $users;
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
        };

        $excomp = Company::find($comp); 
        if($excomp){      }

        else{
        $company_var = new Company;
        
        $company_var->company_descr = '';
        $company_var->source  = 'OT';
        $company_var->save();
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


    public function insert(Request $req)
    {
        $startDate = DateTime::createFromFormat('Ymd H:i:s', $req->start_date . ' 00:00:00');
        $endDate = DateTime::createFromFormat('Ymd H:i:s', $req->end_date . ' 00:00:00');
        $upd_sap = DateTime::createFromFormat('Ymd H:i:s', $req->change_on . ' 00:00:00');
        $exLeaveList = Leave::where('user_id', $req->pers_no)
            ->where('start_date', $startDate)
            ->where('end_date', $endDate)
            ->where('leave_type', $req->leave_type)
            ->where('doc_id', $req->doc_id)->delete();


       

        $l = new Leave;
        $l->user_id       = $req->pers_no;
        $l->upd_sap       = $upd_sap;
        $l->start_date    = $startDate;
        $l->end_date      = $endDate;
        $l->leave_type = $req->leave_type;
        $l->leave_descr     = $req->leave_descr;
        $l->leave_status = $req->leave_status;
        $l->version_no  = $req->version_no;
        $l->doc_id      = $req->doc_id;
        $l->opr         = $req->operation;
        $l->save();
        $collection = ["user_id" => $l->user_id, "start_date" => $l->start_date];
        return $collection;
    }



    public function returnMaxDate()
    {
        $upd_sap = Leave::max('upd_sap');

        return $upd_sap;
    }
}
