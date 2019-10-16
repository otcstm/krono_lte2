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
        ini_set('max_execution_time', 3000); // 300 seconds = 5 minutes
        set_time_limit(0);
        if ($id == 'all') {
            $spData = SapPersdata::all();

            foreach ($spData as $sp) {
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
            }
        } else {
            $sp = SapPersdata::find($id);
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
        }


        return $ur ;
    }

    public function listAll()
    {
        $sp = SapPersdata::all();
        $arr = [];


        foreach ($sp as $s) {
            array_push($arr, $s->persno);
            echo($s->persno);
            echo('<br/>');
        }


        //  return $arr;
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
     * Display the specified resource.
     *
     * @param  \App\UserRecord  $userRecord
     * @return \Illuminate\Http\Response
     */
    public function show(UserRecord $userRecord)
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
