<?php

namespace App\Http\Controllers\Admin;

use App\Holiday;
use App\HolidayCalendar;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StateController;
use App\State;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
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
      $states = State::all();
//         $statesCtrl = new StateController();
//         $states = $statesCtrl->list();
 //       $states = StateController::list();

        return view('admin.holiday.createHoliday',[
            'states' => $states,]);
    }





    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insert(Request $req)
    {


        $a1 = new Holiday;
        $user   = $req->user();
        $states = State::find($req->state_selections);


        $a1->dt             = $req->dt;
        $a1->descr          = $req->descr;
        $a1->guarantee_flag = $req->guarantee_flag;
        $a1->update_by     = $user->id;

        $a1->save();
        foreach ($states as $st) {
          $hc = new HolidayCalendar;
          $hc->holiday_id = $a1->id;
          $hc->state_id   = $st->id;
          $hc->update_by  = $user->id;
          $hc->save();
          echo($hc);
        }

        return $a1;
    }


     /**
     * Display the specified resource.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */



    public function show(Holiday $holiday)
    {
      $hol = Holiday::all();
      $state = State::all();



      // first, prepare starting header
      $header = ['id', 'date', 'event'];
      $content = [];

      // pastu, tambah state kat header
      foreach($state as $satustate){
        array_push($header, $satustate->id);
      }

      // next, prepare table content based on event
      foreach ($hol as $value) {

        $isi = [$value->id, $value->dt, $value->descr];

        $thisEventStateIDS = [];
        foreach($value->StatesThatCelebrateThis as $holCal){
          array_push($thisEventStateIDS, $holCal->state_id);
        }

        foreach($state as $satustate){
          if(in_array($satustate->id, $thisEventStateIDS)){
            array_push($isi, '*');
          } else {
            array_push($isi, '');
          }
        }

        array_push($content, $isi);
      }

      $output = [
        'header' => $header,
        'content' => $content
      ];

 //dd($output);
      $states = State::all();
      return view('admin.holiday.show',[
          'header' => $header,
          'content'=> $content,
           'states'=> $states]);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
      $states = State::all();
      return view('admin.holiday.edit',[

           'states'=> $states]);

        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        //
    }
}
