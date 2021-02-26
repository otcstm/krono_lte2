<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DayType;
use App\Shared\MathHelper;

class DayTypeController extends Controller
{
  public function index(){
    return view('admin.shiftp.daytype', ['p_list' => DayType::all()]);
  }

  public function add(Request $r){

    // check for duplicates
    $dupwd = DayType::where('code', $r->code)->first();
    if($dupwd){
      return redirect()->back()->withInput()->withErrors(['code' => 'already exist']);
    }
    
    $dt = new DayType;
    $dt->code = $r->code;
    $dt->description = $r->description;
    if($r->has('is_work_day') == true){
      $dt->is_work_day = true;
    } else {
      $dt->is_work_day = false; 
    }

    if($r->daytype == 'O'){
      $dt->total_minute = 0;
    }
    elseif($r->daytype == 'R'){
      $dt->total_minute = 0;
    }
    else{      
      $dt->total_minute = MathHelper::getTotalMinutes($r->dur_hour, $r->dur_minute);
    }
    
    $dt->start_time = $r->start_time;
    $dt->dur_hour = $r->dur_hour;
    $dt->dur_minute = $r->dur_minute;
    $dt->day_type = $r->daytype;
    $dt->working_hour = (MathHelper::getTotalMinutes($r->working_hour_h, $r->working_hour_m)/60);  
    $dt->expected_hour = $r->expected_hour;

    $dt->bg_color = $r->bgcolor;
    $dt->font_color = $r->fontcolor;
    $dt->created_by = $r->user()->id;
    $dt->save();

    return redirect(route('wd.index', [], false))->with(['alert' => $r->code . ' added', 'a_type' => 'success']);

  }

  public function edit(Request $r){
    $dt = DayType::find($r->id);
    if($dt){
      $dt->description = $r->description;
      $dt->last_edited_by = $r->user()->id;
      $dt->bg_color = $r->bgcolor;
      $dt->font_color = $r->fontcolor;
      $dt->expected_hour = $r->expected_hour;
      $dt->save();

      return redirect(route('wd.index', [], false))->with(['alert' => $dt->code . ' updated', 'a_type' => 'success']);
    } else {
      return redirect(route('wd.index', [], false))->with(['alert' => $r->code . ' not found', 'a_type' => 'warning']);
    }
  }

  public function delete(Request $r){
    $dt = DayType::find($r->id);
    if($dt){
      $dt->deleted_by = $r->user()->id;
      $dt->save();

      $dt->delete();

      return redirect(route('wd.index', [], false))->with(['alert' => $dt->code . ' deleted', 'a_type' => 'warning']);
    } else {
      return redirect(route('wd.index', [], false))->with(['alert' => 'work-day ID#' . $r->id . ' not found', 'a_type' => 'danger']);
    }
  }
}
