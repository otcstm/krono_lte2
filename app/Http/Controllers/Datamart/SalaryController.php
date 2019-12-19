<?php

namespace App\Http\Controllers\Datamart;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Salary;

use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function list()
    {
        $sal = Salary::all();

        return $sal;
    }


    public function insert(Request $req)
    {
        $s = new Salary;
        $s->user_id       = $req->pernr;
        $s->upd_sap       = $req->aedtm;
        $s->start_date    = $req->begda;
        $s->end_date      = $req->endda;
        $s->payscale_type = $req->trfar;
        $s->payscale_area = $req->trfgb;
        $s->salary        = $req->betrg;
        $s->save();
    }


    public function insert_bak(
        $user_id,
        $upd_sap,
        $start_date,
        $end_date,
        $payscale_type,
        $payscale_area,
        $salary
    ) {
        $s = new Salary;
        $s->user_id = $user_id;

        $s->upd_sap       = $upd_sap;
        $s->start_date    = $start_date;
        $s->end_date      = $end_date;
        $s->payscale_type = $payscale_type;
        $s->payscale_area = $payscale_area;
        $s->salary        = $salary;
        $s->save();
    }


    public function returnABC()
    {
        return "ABC";
    }
}
