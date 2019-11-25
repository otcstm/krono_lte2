<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;

use App\UserRecord;
use App\User;
use App\SapPersdata;
use App\Shared\URHelper;
use Illuminate\Http\Request;

class URController extends Controller
{
    public function popById($id)
    {
        ini_set('max_execution_time', 30000); // 300 seconds = 5 minutes
        set_time_limit(0);
        if ($id == 'all') {
            $spData = SapPersdata::orderBy('persno')->get();
            foreach ($spData as $sp) {
                try {
                    $ur = URHelper::regUser(
                    $sp->persno,
                    $sp->nic,
                    $sp->oic,
                    $sp->staffno,
                    $sp->complete_name,
                    $sp->orgunit,
                    $sp->comp,
                    $sp->persarea,
                    $sp->perssubarea,
                    $sp->empsgroup,
                    $sp->psgroup,
                    $sp->empgroup,
                    $sp->pslvl,
                    $sp->birthdate,
                    $sp->email,
                    $sp->cellno,
                    $sp->reptto,
                    $sp->empstats,
                    $sp->position,
                    $sp->costcentr,
                    $sp->upd_sap
                );
              } catch (\Exception $e) {
echo("error <br/>");
echo($e);
echo("<br/>");
echo($sp->persno);

                }
            }
        } else {
            $sp = SapPersdata::find($id);

            try {

                $ur = URHelper::regUser(
                $sp->persno,
                $sp->nic,
                $sp->oic,
                $sp->staffno,
                $sp->complete_name,
                $sp->orgunit,
                $sp->comp,
                $sp->persarea,
                $sp->perssubarea,
                $sp->empsgroup,
                $sp->psgroup,
                $sp->empgroup,
                $sp->pslvl,
                $sp->birthdate,
                $sp->email,
                $sp->cellno,
                $sp->reptto,
                $sp->empstats,
                $sp->position,
                $sp->costcentr,
                $sp->upd_sap
            );
          } catch (\Exception $e) {
echo("error <br/>");
                          }
        }


        return 'done' ;
    }

    public function listAll()
    {
        $sp = SapPersdata::whereNotIn('persno', User::all()->pluck('id'))->get();
        $arr = [];
        foreach ($sp as $s) {
            array_push($arr, $s->persno);
            echo($s->persno);
            echo('<br/>');
        }
    }

    public function show($persno)
    {
        $ur = URHelper::getUserHistory($persno);
        echo json_encode($ur);

    }

    public function gUR($persno,$dt){
    $ur = URHelper::gUR($persno,$dt);
    $u = URHelper::gU($persno);


    $collection = collect(['user'=>$u,'user_records'=>$ur]);
    return $collection;
    }
}
