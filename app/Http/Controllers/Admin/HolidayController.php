<?php

namespace App\Http\Controllers\Admin;

use App\Holiday;
use App\HolidayCalendar;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StateController;
use App\State;
use App\HolidayLog;
use Illuminate\Http\Request;
use DB;

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
    public function create(Request $req)
    {
        $states = State::all();
        $curYear = date('Y');
        $s_year = $req->s_year ?  $req->s_year : $curYear;
        if($s_year == 'all'){$s_year=$curYear;}

        //$dtVal = Now();
          $dtVal = $s_year.'-01'.'-01';
        //  $dtVal = '01/01/'.$s_year;






        return view('admin.holiday.createHoliday', [
            'states' => $states,'s_year'=>$s_year, 'dtVal'=>$dtVal]);
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
        $gf = $req->guarantee_flag ?  $req->guarantee_flag : 0;

        $time = strtotime($req->dt);
        $s_year = date("Y",$time);

        $states = State::find($req->state_selections);
        $a1->dt             = $req->dt;
        $a1->descr          = $req->descr;
        $a1->guarantee_flag = $gf;
        $a1->update_by     = $user->id;
        $a1->save();
        if($states){
        foreach ($states as $st) {
            $hc = new HolidayCalendar;
            $hc->holiday_id = $a1->id;
            $hc->state_id   = $st->id;
            $hc->update_by  = $user->id;
            $hc->save();
            //echo($hc);
        }
      }


        $this->log($a1->id,'INSERT');
        $ac = 'info';
        $alert = $a1->descr.' has been inserted';
        return redirect(route('holiday.show', [], false))->
        with([
          'alert' => $alert,
          'ac'=>$ac,
          's_year'=>$s_year
        ]);



    }


    /**
    * Display the specified resource.
    *
    * @param  \App\Holiday  $holiday
    * @return \Illuminate\Http\Response
    */



    public function show(Request $req)
    {

      $alert = Session('alert') ? Session('alert') : 'rest';
      $ac = Session('ac') ? Session('ac') : 'info';
      $curYear = date('Y');
      $s_year = $req->s_year ? $req->s_year : $curYear;
      $s_year = Session('s_year') ? Session('s_year') : $s_year;


      $years_in_holiday_table = Holiday::select(DB::raw('YEAR(dt) as year'))
      ->distinct()->orderBy('year','desc')->get()
      ->pluck('year')->toArray();
      array_push($years_in_holiday_table,'all');





        //dd($s_year);
        if($s_year == 'all'){
        $hol = Holiday::all();
      } else {
        $hol = Holiday::whereRaw("YEAR(dt) = '".$s_year."'")->orderBy('dt','asc')->get();
      }

        $state = State::all();
        // first, prepare starting header
        $header = ['id', 'Date', 'Event'];
        $content = [];
        // pastu, tambah state kat header
        foreach ($state as $satustate) {
            array_push($header, $satustate->id);
        }
        // next, prepare table content based on event
        foreach ($hol as $value) {
            $isi = [$value->id, $value->dt, $value->descr];
            $thisEventStateIDS = [];
            foreach ($value->StatesThatCelebrateThis as $holCal) {
                array_push($thisEventStateIDS, $holCal->state_id);
            }

            foreach ($state as $satustate) {
                if (in_array($satustate->id, $thisEventStateIDS)) {
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


        $states = State::all();
        return view('admin.holiday.show', [
          'alert'   =>$alert,
          'ac'      =>$ac,
          'header'  => $header,
          'content' => $content,
          'states' => $states,
          's_year' => $s_year,
          'years'=>$years_in_holiday_table]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $holiday = Holiday::find($id);
        $states = State::all();
        //dd($holiday);
        //dd($holiday->StatesThatCelebrateThis);


        return view('admin.holiday.edit', [
           'holiday'=>$holiday,
           'states'=> $states]);

        //
    }

    /**
     * Update holiday and holiday calendar
     */
    public function update(Request $req)
    {
        //get holiday model
        $user   = $req->user();
        //get holiday model
        $holiday = Holiday::find($req->id);

        $gf = $req->guarantee_flag ?  $req->guarantee_flag : 0;


        $holiday->dt             = $req->dt;
        $holiday->descr          = $req->descr;
        $holiday->guarantee_flag = $gf;
        $holiday->update_by     = $user->id;
        $holiday->save();


        //get list of cuurent states that ties to the holiday in holiday calendar
        $currentStates = HolidayCalendar::where('holiday_id', $holiday->id)->get('state_id');
        //$currentStates = $holiday->StatesThatCelebrateThis;
        $arr = $req->state_selections;
        echo json_encode($arr);
        echo('<br/>');
        //loop through DB data
        foreach ($currentStates as $cs) {
            echo('echoing cs');
            echo($cs->state_id);
            echo('end echoing cs <br/>');

            if (($key = array_search($cs->state_id, $arr)) !== false) {
                //if the state already existed in selection array
                //and existed in db
                //remove it from the selection array
                //only new addition would left
                unset($arr[$key]);
            } else {
                //if the state not existed in the selection
                //but exist in db
                //remove the state from DB
                $hcDel = HolidayCalendar::where('holiday_id', $holiday->id)
               ->where('state_id', $cs->state_id)->delete();
            }
        }
        //echo('<br/>');
        //echo json_encode($arr);
        foreach ($arr as $selectedState) {
            echo($selectedState);

            $hc= new HolidayCalendar;
            $hc->holiday_id = $holiday->id;
            $hc->state_id   = $selectedState;
            $hc->update_by  = $user->id;
            $hc->save();
        }
        $time = strtotime($holiday->dt);
        $s_year = date("Y",$time);


  $this->log($holiday->id,'UPDATE');
        $ac = 'info';
        $alert = $holiday->descr.' has been updated';
        return redirect(route('holiday.show', [], false))->
        with([
          'alert' => $alert,
          'ac'=>$ac,
          's_year' => $s_year
        ]);

        //dd($holiday);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
      $holiday = Holiday::find($req->holiday_id);
      //change update by field first for logging
      $holiday->update_by  = $req->user()->id;
      $holiday->save();

      $time = strtotime($holiday->dt);
      $s_year = date("Y",$time);

      $this->log($holiday->id,'DESTROY');

      Holiday::destroy($req->holiday_id);
      $hcDel = HolidayCalendar::where('holiday_id', $holiday->id)
      ->delete();




      $ac = 'info';
      $alert = $holiday->descr.' has been destroyed';
      return redirect(route('holiday.show', [], false))->
      with([
        'alert' => $alert,
        'ac'=>$ac,
        's_year'=>$s_year
      ]);

    }

      public function log($id,String $action){

      $holiday = Holiday::find($id);
      $log     = new HolidayLog;
      $states = HolidayCalendar::where('holiday_id', $holiday->id)->pluck('state_id');
      $states = str_replace("[","",$states);
      $states = str_replace("]","",$states);
      $log->holiday_id        = $holiday->id;
      $log->descr             = $holiday->descr;
      $log->dt                = $holiday->dt;
      $log->guarantee_flag    = $holiday->guarantee_flag;
      $log->update_by         = $holiday->update_by;
      $log->action            = $action;
      $log->states            = $states;

      $log->save();


      return $log;

/**
      $table->bigInteger('holiday_id');
      $table->string('descr', 255)->nullable();
      $table->date('dt');
      $table->integer('guarantee_flag');
      $table->string('states', 750)->nullable();
      $table->bigInteger('update_by');
      $table->timestamps();
**/
      }


}
