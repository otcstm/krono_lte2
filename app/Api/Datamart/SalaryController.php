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
        $s->user_id       = $req->pers_no;
        $s->upd_sap       = $req->change_on;
        $s->start_date    = $req->start_date;
        $s->end_date      = $req->end_date;
        $s->payscale_type = $req->payscale_type;
        $s->payscale_area = $req->payscale_area;
        $s->salary        = $req->salary;
        $s->save();
        $collection = ["user_id" => $s->user_id, "start_date" => $s->start_date ];
        return $collection;
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
