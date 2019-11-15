<?php

namespace App\Http\Controllers\Admin;

use App\Psubarea;
use App\Shared\UserHelper;
use App\Company;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class PsubareaController extends Controller
{
  public function index()
  {
      $psubarea = Psubarea::all();
      $company = Company::all();
      $state = State::all();

      return view('admin.psubarea', ['psubareas' => $psubarea,'companies' => $company,'states' => $state]);
  }

  public function store(Request $req)
  {
    $var_id = $req ->inputip;
    $var_comp = $req ->inputcomp;
    $var_parea = $req ->inputparea;
    $var_paread = $req ->inputparead;
    $var_psubarea = $req ->inputpsubarea;
    $var_psubaread = $req ->inputpsubaread;
    $var_state = $req ->inputstate;
    $var_region = $req ->inputregion;

    $check = Psubarea::where('company_id', trim($var_comp))
                      ->where('persarea', trim($var_parea))
                      ->where('perssubarea', trim($var_psubarea))
                      ->where('state_id', trim($var_state))
                      ->where('region', trim($var_region))
                      ->where('deleted_at', null)
                      ->get();
    if(count($check)==0){
      $new_psubarea = new Psubarea;
      $new_psubarea-> company_id = $var_comp;
      $new_psubarea-> persarea = $var_parea;
      $new_psubarea-> perssubarea = $var_psubarea;
      $new_psubarea-> persareadesc = $var_paread;
      $new_psubarea-> perssubareades = $var_psubaread;
      $new_psubarea-> state_id = $var_state;
      $new_psubarea-> region = $var_region;
      $new_psubarea-> source = 'OT';
      $new_psubarea-> created_by= $req->user()->id;
      $new_psubarea->save();
      $execute = UserHelper::LogUserAct($req, "Psubarea Management", "Create Psubarea " .$var_state. ", id ".$var_id);
      $a_text = "Successfully created Psubarea for state " .$var_state. ".";
      $a_type = "success";
  }
  else{
      $a_text = 'Personnel subarea already exist.';
      $a_type = "warning";
      }
      return redirect(route('psubarea.index', [], false))
      ->with(['a_text' => $a_text,'a_type' => $a_type]);
    }

  public function update(Request $req)
    {
      //dd($req->all());
      $ps = Psubarea::find($req->inputid);
      if($ps){

        $check = Psubarea::where('company_id', trim($req ->inputcomp))
                          ->where('persarea', trim($req ->inputparea))
                          ->where('perssubarea', trim($req ->inputpsubarea))
                          ->where('state_id', trim($req ->inputstate))
                          ->where('region', trim($req ->inputregion))
                          ->where('deleted_at', null)
                          ->get();
          if(count($check)==0){
            $ps-> persarea = $req->inputparea;
            $ps-> perssubarea = $req->inputpsubarea;
            $ps-> persareadesc = $req->inputparead;
            $ps-> perssubareades = $req->inputpsubaread;
            $ps-> state_id = $req->inputstate;
            $ps-> region = $req->inputregion;
            $ps-> source = 'OT';
            $ps-> last_edited_by = $req->user()->id;
            $ps->save();
            $execute = UserHelper::LogUserAct($req, "Psubarea Management", "Update Psubarea " .$req ->inputstate. ", id ".$req->inputid);
            return redirect(route('psubarea.index', [], false))->with(['a_text' => 'Psubarea for state '. $req->inputstate . ' updated', 'a_type' => 'success']);
          }
          else{

              return redirect(route('psubarea.index', [], false))->with(['a_text' =>'Personnel subarea already exist', 'a_type' => 'warning']);
              }
      } else {
        return redirect(route('psubarea.index', [], false))
        ->with(['a_text' =>'Psubarea not found.', 'a_type' => 'warning']);
      }
    }

    public function destroy(Request $req)
    {
      $ps = Psubarea::find($req->inputid);
      if($ps){
        $ps->deleted_by = $req->user()->id;
        $execute = UserHelper::LogUserAct($req, "Psubarea Management", "Delete Psubarea " .$ps ->state_id. ", id ".$req->inputid);
        $ps->save();
        $ps->delete();

        return redirect(route('psubarea.index', [], false))->with(['alert' => 'Psubarea for '.$ps ->state_id. ' deleted', 'a_type' => 'warning']);
      } else {
        return redirect(route('psubarea.index', [], false))->with(['alert' => 'Psubarea for '.$ps ->state_id. ' not found', 'a_type' => 'danger']);
      }
    }
  }
