<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ShiftPattern;
use App\ShiftPatternDay;
use App\DayType;

class ShiftPatternController extends Controller
{
  public function index(){
    return view('admin.shiftp.stemplate_list', ['p_list' => ShiftPattern::all()]);
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

    return redirect(route('sp.view', ['id' => $nsp->id], false));

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

      return view('admin.shiftp.stemplate_detail', [
        'tsp' => $tsp,
        'daycount' => $daycount,
        'daytype' => DayType::all(),
        'daylist' => $days
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

      // $tsp->total_minutes += $tdaytype->total_minutes;
      // $tsp->days_count = $curdaycount;
      $tsp->last_edited_by = $req->user()->id;
      $tsp->source = 'OTCS';
      $tsp->save();
      // $tsp->updateTotals();


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

      return redirect(route('sp.view', ['id' => $req->id], false))->with(['alert' => 'Description updated', 'a_type' => 'success']);

    } else {
      return redirect(route('sp.index', [], false))->with(['alert' => 'Shift pattern not found', 'a_type' => 'warning']);
    }
  }

  public function delShiftPattern(Request $req){
    $tsp = ShiftPattern::find($req->id);
    if($tsp){
      // $tsp->ListDays->delete();
      $code = $tsp->code;
      $tsp->deleted_by = $req->user()->id;
      $tsp->delete();

      return redirect(route('sp.view', ['id' => $req->id], false))->with(['alert' => $code . ' deleted', 'a_type' => 'warning']);

    } else {
      return redirect(route('sp.index', [], false))->with(['alert' => 'Shift pattern not found', 'a_type' => 'warning']);
    }
  }

}
