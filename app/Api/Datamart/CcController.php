<?php

namespace app\Api\Datamart;
//use App\Api\Datamart;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Costcenter;

use Illuminate\Http\Request;

class CcController extends Controller
{
    public function insert(Request $req)
    {

      $cc = new Costcenter;
      $cc->id                   = $req->costcenter;
      $cc->descr                = $req->cc_desc;
      $cc->status               = $req->cc_status;
      $cc->costcenter_name      = $req->cc_name;
      $cc->company_id           = $req->cc_cocode;
      $cc->replacement_cc       = $req->cc_replace;
      $cc->save();





      return $cc->id;

    }

    public function returnABC()
    {
      Salary::all();
        return "ABCDE";
    }

    public function deleteAll()
    {
        Costcenter::truncate();
        return "cc delete";

    }
}
