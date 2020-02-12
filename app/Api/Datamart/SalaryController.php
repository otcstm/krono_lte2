<?php

namespace app\Api\Datamart;
//use App\Api\Datamart;

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
        return "ddd";
    }

    public function returnABC()
    {
      Salary::all();
        return "ABCDE";
    }

    public function returnMaxDate()
    {
        $upd_sap = Salary::max('upd_sap');

        return $upd_sap;

    }
}
