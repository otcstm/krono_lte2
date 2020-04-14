<?php

namespace app\Api\Datamart;
//use App\Api\Datamart;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Leave;

use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function list()
    {
        $leaves = Leave::all();
        return $leaves;
    }

    public function insert(Request $req)
    {

        $l = new Leave;
        $l->user_id       = $req->pers_no;
        $l->upd_sap       = $req->change_on;
        $l->start_date    = $req->start_date;
        $l->end_date      = $req->end_date;
        $l->leave_type = $req->leave_type;
        $l->leave_descr = $req->leave_descr;
        $l->leave_descr = $req->leave_status;
        $l->version_no = $req->version_no;
        $l->doc_id = $req->doc_id;
        $l->save();
        $collection = ["user_id" => $l->user_id, "start_date" => $l->start_date ];
        return $collection;
    }



    public function returnMaxDate()
    {
        $upd_sap = Leave::max('upd_sap');

        return $upd_sap;

    }
}
