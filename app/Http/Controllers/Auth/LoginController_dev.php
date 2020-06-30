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
      
      //echo "hye im before checking. mode:".$_ENV['APP_ENV'];

      if ($_ENV['APP_ENV'] == 'local' || $_ENV['APP_ENV'] == 'development') {
        dd("hye im local/development",$_ENV['APP_ENV']);
        //password same username
        if($req->username == $req->password)
        {          
          $staff_no = str_replace(' ','',strtoupper(trim($req->username)));
          $cuser = User::where(DB::raw('REPLACE(UPPER(TRIM(staff_no))," ","")'), $staff_no)->first();
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
        dd("hye im ELSE",$_ENV['APP_ENV']);
        $udata = LdapHelper::DoLogin($req->username, $req->password);
        if($udata['code'] == 200){
          $staff_no = str_replace(' ','',strtoupper(trim($req->username)));
          $cuser = User::where(DB::raw('REPLACE(UPPER(TRIM(staff_no))," ","")'), $staff_no)->first();
          if($cuser){          
            Session::put(['announcementx' => true]);
          } else {
            //no record in users table
            return redirect()->back()->withErrors(['username' => 'User not in OT system']);
          }
          //dd("hye im LdapHelper 200",$_ENV['APP_ENV']);
          //return to guess if auth not pass else authorized to homepage
          // attach normal user
          // try {
          //   $cuser->roles()->attach(1);
          // } catch(Throwable $e){
          // }
          $cuser->roles()->attach(1);
          Auth::loginUsingId($cuser->id, true);
          return redirect()->intended(route('misc.home', [], false), 302, [], true);
        }
        else{
          //code other than 200
          dd("hye im LdapHelper !200",$_ENV['APP_ENV']);
          return redirect()->back()->withErrors(['username' => $udata['msg'].$_ENV['APP_ENV']]);
        }
      }
      dd("Im after all clause",$_ENV['APP_ENV']); 
    }
}
