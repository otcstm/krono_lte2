<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shared\LdapHelper;
use App\User;
use Illuminate\Support\Facades\Auth;

class TempController extends Controller
{
  // use AuthenticatesUsers;

  public function loadDummyUser(Request $req){
    return LdapHelper::loadDummyAccount(3000);
  }

  public function login(Request $req)
  {
    $this->validate($req, [
          'staff_no' => 'required', 'password' => 'required',
    ]);
    $cuser = User::where('staff_no', $req->staff_no)->first();
    Auth::loginUsingId($cuser->id, true);
    return redirect()->intended(route('misc.home', [], false));
    return redirect()->back()->with('message', $udata['msg']);
  }
}
