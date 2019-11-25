<?php

namespace App\Http\Controllers;

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
            //$spData = SapPersdata::whereNotIn('persno', User::all()->pluck('id'))->orderBy('persno')->take(5000)->get();

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
        //$sp = SapPersdata::all();
        $sp = SapPersdata::whereNotIn('persno', User::all()->pluck('id'))->get();
        $arr = [];


        foreach ($sp as $s) {
            array_push($arr, $s->persno);
            echo($s->persno);
            echo('<br/>');
        }


        //  return $arr;
    }


    public function show($persno)
    {
        $ur = URHelper::getUserHistory($persno);


  echo json_encode($ur);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserRecord  $userRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(UserRecord $userRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserRecord  $userRecord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserRecord $userRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserRecord  $userRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRecord $userRecord)
    {
        //
    }
}
