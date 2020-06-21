<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Company;
use App\ShiftPattern;
use App\CompanyShiftPattern;
use App\ShiftPatternDay;
use App\DayType;
use DB;

class ShiftPatternController extends Controller
{
  public function index(){

    //$sp = ShiftPattern::find(2);
    //dd($sp->companies);

    return view('admin.shiftp.stemplate_list', 
    [
      'p_list' => ShiftPattern::all(),    
      'comp_list'=> Company::all()
    ]);
  }

  public function addShiftPattern(Request $req){
    $exsp = ShiftPattern::where('code', $req->code)->first();
    if($exsp){
      return redirect()->back()->withInput()->withErrors(['code' => 'already exist']);
    }

    $nsp = new ShiftPattern;
    $nsp->code = $req->code;
    $nsp->description = $req->description;
    $nsp->is_weekly = $req->has('is_weekly');
    $nsp->created_by = $req->user()->id;
    $nsp->source = 'OTCS';     
    $nsp->save();    

    //dd($req);
    if($req->compcb)
    { 
      if(sizeof($req->compcb) > 0)
      { 
        foreach($req->compcb as $acompcb){
          $addSPComp = new CompanyShiftPattern;
          $addSPComp->company_id = $acompcb;
          $addSPComp->shift_pattern_id = $nsp->id;
          $addSPComp->created_by = $req->user()->id;
          $addSPComp->save();
        }
      }        
    }

    return redirect(route('sp.view', ['id' => $nsp->id], false))
    ->with(['alert' => 'Shift pattern added successfully', 'a_type' => 'success']);

  }

  public function viewSPDetail(Request $req){
    if($req->filled('id') == false){
      return redirect()->route('sp.index', [], false);
    }

    $tsp = ShiftPattern::find($req->id);
    if($tsp){
      $tsp->updateTotals();
      $daycount = 0;
      $days = $tsp->ListDays;
      if($days){
        $daycount = $days->count();
      }

      $tspComp = [];
      $tcsp = DB::table('company_shift_pattern')->select('company_id')
      ->where('shift_pattern_id', $req->id)
      ->distinct()
      ->get();
      //dd($tcsp);

      if($tcsp->count() > 0){
        $tspComp = $tcsp->pluck('company_id')->toArray();
      }

      return view('admin.shiftp.stemplate_detail', [
        'tsp' => $tsp,
        'tspComp' => $tspComp,
        'daycount' => $daycount,
        'daytype' => DayType::all(),
        'daylist' => $days,    
        'comp_list'=> Company::all()
      ]);

    } else {
      return redirect(route('sp.index', [], false))->with(['alert' => 'Shift pattern not found', 'a_type' => 'warning']);
    }
  }

  public function pushDay(Request $req){

    $tsp = ShiftPattern::find($req->sp_id);
    $tdaytype = DayType::find($req->daytype);

    if($tdaytype){
    } else {
      return redirect()->back()->with(['alert' => 'Day type no longer exist', 'a_type' => 'danger']);
    }

    if($tsp){
      $curdaycount = $tsp->ListDays->count() + 1;

      $spdays = new ShiftPatternDay;
      $spdays->shift_pattern_id = $tsp->id;
      $spdays->day_seq = $curdaycount;
      $spdays->day_type_id = $req->daytype;
      $spdays->save();    
      
      //update latest added day (normal day only) punye start time ke start time column shiftpatern 
      if($tsp->is_weekly == 1){
        $tsp->start_time = $tdaytype->start_time;
      }
      
      // $tsp->total_minutes += $tdaytype->total_minutes;
      // $tsp->days_count = $curdaycount;  
      $tsp->last_edited_by = $req->user()->id;
      $tsp->source = 'OTCS';
      $tsp->save();
      // $tsp->updateTotals();      
      dd($tsp, $tdaytype);
      
      return redirect(route('sp.view', ['id' => $tsp->id], false))->with(['alert' => $tdaytype->code . ' day added', 'a_type' => 'success']);

    } else {
      return redirect()->back()->with(['alert' => 'Shift Pattern ' . $req->sp_code . ' no longer exist', 'a_type' => 'danger']);
    }

  }

  public function popDay(Request $req){

    $spd = ShiftPatternDay::find($req->id);
    if($spd){
      $tsp = $spd->ShiftPattern;
      $tsp->last_edited_by = $req->user()->id;
      $tsp->source = 'OTCS';
      $tsp->save();
      $code = $spd->Day->code;

      $spd->delete();

      // $tsp->updateTotals();
      return redirect(route('sp.view', ['id' => $req->sp_id], false))->with(['alert' => $code . ' removed', 'a_type' => 'warning']);

    } else {
      return redirect(route('sp.view', ['id' => $req->sp_id], false));
    }

  }

  public function editShiftPattern(Request $req){
    $tsp = ShiftPattern::find($req->id);
    if($tsp){
      $tsp->description = $req->description;
      $tsp->last_edited_by = $req->user()->id;
      $tsp->source = 'OTCS';
      $tsp->save();

      //dd($req);
      if($req->compcb)
      { 
        if(sizeof($req->compcb) > 0)
        {
          $delSPComp = DB::table('company_shift_pattern')->where('shift_pattern_id', $req->id)
          ->delete();

          //dd($req->compcb, $delSPComp, $delSPComp->pluck('id')->toArray());
  
          foreach($req->compcb as $acompcb){
            $addSPComp = new CompanyShiftPattern;
            $addSPComp->company_id = $acompcb;
            $addSPComp->shift_pattern_id = $req->id;
            $addSPComp->created_by = $req->user()->id;
            $addSPComp->save();
          }
        }      
      }
      else{
        // $delSPComp = CompanyShiftPattern::where('shift_pattern_id', $req->id)
        // ->delete();
        return redirect(route('sp.view', ['id' => $req->id], false))
        ->with(['alert' => 'Atleast has one company checked', 'a_type' => 'warning']);
      }  

      return redirect(route('sp.view', ['id' => $req->id], false))
      ->with(['alert' => 'Data updated', 'a_type' => 'success']);

    } else {
      return redirect(route('sp.index', [], false))
      ->with(['alert' => 'Shift pattern not found', 'a_type' => 'warning']);
    }
  }

  public function delShiftPattern(Request $req){
    $tsp = ShiftPattern::find($req->id);
    if($tsp){
      // $tsp->ListDays->delete();
      $code = $tsp->code;
      $tsp->deleted_by = $req->user()->id;
      $tsp->delete();

      // return redirect(route('sp.view', ['id' => $req->id], false))->with(['alert' => $code . ' deleted', 'a_type' => 'warning']);
      return redirect(route('sp.index', [], false))->with(['alert' => 'Work schedule has been deleted', 'a_type' => 'warning']);

    } else {
      dd('here');
      return redirect(route('sp.index', [], false))->with(['alert' => 'Shift pattern not found', 'a_type' => 'warning']);
    }
  }

}
