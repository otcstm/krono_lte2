<?php

namespace app\Api\Datamart;
//use App\Api\Datamart;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Leave;
use DateTime;

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
        $startDate = DateTime::createFromFormat('Ymd H:i:s', $req->start_date . ' 00:00:00');
        $endDate = DateTime::createFromFormat('Ymd H:i:s', $req->end_date . ' 00:00:00');
        $upd_sap = DateTime::createFromFormat('Ymd H:i:s', $req->change_on . ' 00:00:00');
        $exPrList = Leave::where('user_id', $req->persno)
            ->where('start_date', $startDate)
            ->where('leave_type', $req->leave_type)
            ->where('doc_id', $req->doc_id)
            ->delete();

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
