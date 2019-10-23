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
    $var_psubarea = $req ->inputpsubarea;
    $var_state = $req ->inputstate;

    $check = Psubarea::where('company_id', trim($var_comp))
                      ->where('persarea', trim($var_parea))
                      ->where('perssubarea', trim($var_psubarea))
                      ->where('state_id', trim($var_state))
                      ->where('deleted_at', null)
                      ->get();
    if(count($check)==0){
      $new_psubarea = new Psubarea;
      $new_psubarea-> company_id = $var_comp;
      $new_psubarea-> persarea = $var_parea;
      $new_psubarea-> perssubarea = $var_psubarea;
      $new_psubarea-> state_id = $var_state;
      $new_psubarea-> source = 'OT';
      $new_psubarea-> created_by= $req->user()->id;
      $new_psubarea->save();
      $execute = UserHelper::LogUserAct($req, "Psubarea Management", "Create Psubarea " .$var_state. ", id ".$var_id);
      $a_text = "Successfully created Psubarea for state " .$var_state. ".";
      // $feedback_icon = "ok";
      $a_type = "success";
  }
  else{
      $a_text = 'Personnel subarea already exist.';
      // $feedback_icon = "remove";
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
                          ->where('deleted_at', null)
                          ->get();
          if(count($check)==0){
          //$ps-> company_id = $req->company;
            $ps-> persarea = $req->inputparea;
            $ps-> perssubarea = $req->inputpsubarea;
            $ps-> state_id = $req->inputstate;
            $ps-> source = 'OT';
            $ps-> last_edited_by = $req->user()->id;
            $ps->save();
            $execute = UserHelper::LogUserAct($req, "Psubarea Management", "Update Psubarea " .$req ->inputstate. ", id ".$req->inputid);
            return redirect(route('psubarea.index', [], false))->with(['a_text' => 'Psubarea for state'. $req->inputstate . ' updated', 'a_type' => 'success']);
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
        $execute = UserHelper::LogUserAct($req, "Psubarea Management", "Delete Psubarea " .$req ->state. ", id ".$req->inputid);
        $ps->save();
        $ps->delete();

        return redirect(route('psubarea.index', [], false))->with(['alert' => 'Psubarea for '.$req ->state. ' deleted', 'a_type' => 'warning']);
      } else {
        return redirect(route('psubarea.index', [], false))->with(['alert' => 'Psubarea for '.$req ->state. ' not found', 'a_type' => 'danger']);
      }
    }
  }
