<?php

namespace app\Api\Datamart;
//use App\Api\Datamart;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\User;
use App\Company;
use App\UserRecord;
use DateTime;
use \Carbon\Carbon;

use Illuminate\Http\Request;

class PersDataController extends Controller
{
    public function list()
    {
        $users = User::all();
        return $users;
    }

    public function regUser(
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
        $ur = $this->regUser(
            $req->pers_no,      //persno
            $req->new_ic_no,    //nic
            $req->old_ic_no,
            $req->staff_no,     //staffno 
            $req->name,         //complete_name   
            $req->sub_orgunit,  //orgunit
            $req->comp_code,    //comp
            $req->pers_area,    
            $req->pers_subarea,
            $req->emp_sgroup_descr, //empsgroup
            $req->band,             //psgroup
            $req->emp_group_descr,  //empgroup
            $req->ps_level,         //pslvl
            $req->birth_date,
            $req->email,
            $req->cell_no,       //cellno
            $req->reptto,
            $req->emp_status,    //empstats
            $req->position,
            $req->cost_centre,   //costcentr
            $req->last_upd_dt    //upd_sap
        ) ;

        return $ur;
        
    }



    public function returnMaxDate()
    {
        $upd_sap = User::max('upd_sap');

        return $upd_sap;
    }
}
