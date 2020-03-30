<?php

namespace App\Http\Controllers;

use Session;
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
    if($cuser){
      
      Session::put(['announcementx' => true]);
      Auth::loginUsingId($cuser->id, true);
      return redirect(route('misc.home', [], false));
    } else {
      return redirect()->back()->withErrors('staff_no', 'user not exist');
    }
  }
}
