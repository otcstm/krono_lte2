<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Shared\LdapHelper;
use App\User;
use Session;
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
    // protected $redirectTo = '/home';
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
            'username' => 'required', 
            'password' => 'required',
      ]);
      //dd(\App::environment());
            
      if (\App::environment('local','development')) {
        dd('Hye im NOT local,development',\App::environment());
        // The environment is dev
        //password same username
        if($req->username == $req->password)
        {          
          // dd(session()->all());
          $cuser = User::where('staff_no', $req->username)->first();
          //dd($cuser);
          if($cuser){            
          Session::put(['announcementx' => true]);
          } else {
            return redirect()->back()->withErrors(['username' => 'User not in OT system']);
          }
          // attach normal user
          $cuser->roles()->attach(1);
          Auth::loginUsingId($cuser->id, true);
          return redirect()->intended(route('misc.home', [], false), 302, [], true);
        }
        else{
          return redirect()->back()->withErrors(['username' => 'Invalid credentials: '.\App::environment()]);
        }
      }

      else{
        dd('Hye im NOT local,development',\App::environment());
        // The environment is not dev
        $udata = LdapHelper::DoLogin($req->username, $req->password);
        if($udata['code'] == 200){
          // session(['staffdata' => $logresp['user']]);
          // $cuser = User::find($udata['data']);
          
          // dd(session()->all());
          $cuser = User::where('staff_no', $req->username)->first();
          if($cuser){            
          Session::put(['announcementx' => true]);
          }else {
              return redirect()->back()->withErrors(['username' => 'User not in OT system']);
          }
          // attach normal user
          $cuser->roles()->attach(1);
          Auth::loginUsingId($cuser->id, true);
          return redirect()->intended(route('misc.home', [], false), 302, [], true);
        }
        else{          
          return redirect()->back()->withErrors(['username' => $udata['msg']]);
        }
      }
    }
}
