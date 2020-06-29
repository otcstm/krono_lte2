<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Shared\LdapHelper;
use App\User;
use DB;
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
    $staff_no = strtoupper(trim($req->staff_no));
    // $staff_no = str_replace(' ','',$staff_no);
    // $cuser = User::where(DB::raw('REPLACE(UPPER(staff_no)," ","")'), $staff_no)->first();
    $cuser = User::where(DB::raw('UPPER(staff_no)'), $staff_no)->first();
    if($cuser){      
      Session::put(['announcementx' => true]);
      Auth::loginUsingId($cuser->id, true);
      return redirect(route('misc.home', [], false));
    } else {
      return redirect()->back()->withErrors(['staff_no' => 'User not in OT system']);
    }
  }
}
