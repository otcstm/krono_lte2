<?php

namespace App\Http\Controllers\Admin;

use App\State;
use App\Shared\UserHelper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class StateController extends Controller
{
    public function __construct()
    {
        //abort_if(Gate::denies('admin_roles'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    public function show(Request $req)
    {
        $states = $this->list();

        $alert = Session('alert') ? Session('alert') : 'rest';
        $ac = Session('ac') ? Session('ac') : 'info';
        return view('admin.states', ['states' => $states,'alert'=>$alert, 'ac'=>$ac
        ]);
    }

    public function update(Request $req)
    {
        $state_var = State::findOrFail($req->id);
        $state_var->state_descr = $req->state_descr;
        $state_var->updated_by  = $req->user()->id;
        $state_var->save();

        $ac = 'info';
        $alert = 'updated ' . $req->state_code .':' .$req->state_descr;
        $states = $this->list();
        //return view('admin.states',['states' => $states, 'alert'=>$alert, 'ac'=>$ac]);
        return redirect(route('state.show', [], false))->
        with([
          'alert' => $alert,
          'ac'=>$ac
        ]);
    }

    public function store(Request $req)
    {
        $state_check = State::find($req->state_code);
        $alert = "string";
        $ac = 'info';

        if (!$state_check) {
            $state_var = new State;
            $state_var->id          = $req->state_code;
            $state_var->state_descr = $req->state_descr;
            $state_var->updated_by  = $req->user()->id;
            $state_var->created_by  = $req->user()->id;
            $state_var->save();
            $alert = 'created ' . $req->state_code .':' .$req->state_descr;
        } else {
            $ac = 'danger';
            $alert = $req->state_code .' already existed and will not be added';
        }
        return redirect(route('state.show', [], false))->
        with([
          'alert' => $alert,
          'ac'=>$ac
        ]);
    }



    public function destroy(Request $req)
    {
        $states = $this->list();
        $st = $req->state_id;
        $state_log = State::find($req->state_id);
        $state_log->updated_by  = $req->user()->id;
        $state_log->save();
        State::destroy($st);
        
        $ac = 'info';
        $alert = $req->state_id .' has been destroyed';

        return redirect(route('state.show', [], false))->
        with([
          'alert' => $alert,
          'ac'=>$ac
        ]);
    }

    public static function list()
    {
        $states = State::all();
        return $states;
    }
}
