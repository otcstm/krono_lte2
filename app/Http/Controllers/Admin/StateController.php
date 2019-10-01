<?php

namespace App\Http\Controllers;

use App\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;



class StateController extends Controller
{

    public function __construct(){
        //abort_if(Gate::denies('admin_roles'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function show(State $state)
    {

        $states = $this->list();

        $alert = isset($alert) ? $alert : ''; 

        return view('admin.states',['states' => $states,]);
    }

    public function update(Request $req)
    {

        $state_var = State::findOrFail($req->id);
        $state_var->state_descr = $req->state_descr;
        $state_var->save();


        $ac = 'info';
        $alert = 'updated ' . $req->state_code .':' .$req->state_descr;
        $states = $this->list();
        return view('admin.states',['states' => $states, 'alert'=>$alert, 'ac'=>$ac]);
        //return redirect()->route('state.show',);
    }

    public function store(Request $req)
    {

        $state_check = State::find($req->state_code);
        $alert = "string";
        $ac = 'info';

        if(!$state_check){
        $state_var = new State;
        $state_var->id          = $req->state_code;
        $state_var->state_descr = $req->state_descr;
        $state_var->save();
        $alert = 'created ' . $req->state_code .':' .$req->state_descr;
        }
        else{
            $ac = 'danger';
            $alert = $req->state_code .' already existed and will not be added';

        }

        $states = $this->list();
        return view('admin.states',['states' => $states,'alert'=>$alert, 'ac'=>$ac]);

    }



    public function destroy(Request $req)
    {
        $states = $this->list();
        $st = $req->state_id;
        State::destroy($st);



        $ac = 'info';
        $alert = $req->state_id .' has been destroyed';
        $states = $this->list();
        return view('admin.states',['states' => $states,'alert'=>$alert, 'ac'=>$ac]);

    }

    public static function list(){
        $states = State::all();
        return $states;

    }

}
