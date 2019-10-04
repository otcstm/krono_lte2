<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Shared\LdapHelper;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $req)
    {
      $this->validate($req, [
            'staff_no' => 'required', 'password' => 'required',
      ]);

      $udata = LdapHelper::DoLogin($req->staff_no, $req->password);

      //=========== use this when coding at work ===========
      if($udata['code'] == 200){
        // session(['staffdata' => $logresp['user']]);

        $cuser = User::where('staff_no', $req->staff_no)->first();
        if($cuser){
        } else {
          // temporary: use ldap data to create user
          // $udata = LdapHelper::FetchUser($req->staff_no, 'cn');
          $cuser = new User;
          $cuser->staff_no = $udata['data']['STAFF_NO'];
          $cuser->email = $udata['data']['EMAIL'];
          $cuser->name = $udata['data']['NAME'];
          // $cuser->persno = $udata['data']['PERSNO'];
          $cuser->new_ic = $udata['data']['NIRC'];
          $cuser->save();

          // also give the super admin role lol
          // $cuser->roles()->attach(1);
        }

        Auth::loginUsingId($cuser->id, true);
        return redirect()->intended(route('misc.home', [], false));
      }
      //=========== use this when coding at work ===========  

      //=========== use this when coding at home ===========
      // $cuser = User::where('staff_no', $req->staff_no)->first();
      // Auth::loginUsingId($cuser->id, true);
      // return redirect()->intended(route('misc.home', [], false));
      //=========== use this when coding at home ===========

      return redirect()->back()->with('message', $udata['msg']);

    }

}
