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

      if ($_ENV['APP_ENV'] == 'local' || $_ENV['APP_ENV'] == 'development') {
        //dd("hye im local/development",$_ENV['APP_ENV']);
        //password same username
        if($req->username == $req->password)
        {          
          $cuser = User::where('staff_no', $req->username)->first();
          if($cuser){            
            Session::put(['announcementx' => true]);
            // attach normal user
            $cuser->roles()->attach(1);
            Auth::loginUsingId($cuser->id, true);
            return redirect(route('misc.home', [], false));
          } 
          else {
            return redirect()->back()->withErrors(['username' => 'User not in OT system']);
          }
        }
        else{
          return redirect()->back()->withErrors(['username' => 'Invalid credentials: '.$_ENV['APP_ENV']]);
        }
      }
      else{
        //dd("hye im ELSE",$_ENV['APP_ENV']);
        $udata = LdapHelper::DoLogin($req->username, $req->password);
        if($udata['code'] == 200){
          $cuser = User::where('staff_no', $req->username)->first();
          if($cuser){          
            Session::put(['announcementx' => true]);
            // attach normal user
            $cuser->roles()->attach(1);
            Auth::loginUsingId($cuser->id, true);
          } else {
            //no record in users table
            return redirect()->back()->withErrors(['username' => 'User not in OT system']);
          }
          //return to guess if auth not pass else authorized to homepage
          return redirect()->intended(route('misc.home', [], false), 302, [], true);
        }
        else{
          //code other than 200
          return redirect()->back()->withErrors(['username' => $udata['msg']]);
        }
      }
      
    }
}
