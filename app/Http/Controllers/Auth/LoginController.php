<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Shared\LdapHelper;
use App\User;
use Session;
use DB;
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

      $inp_staffno = str_replace(' ','',strtoupper(trim($req->username)));      
      $udata = LdapHelper::DoLogin($inp_staffno, $req->password);
      if($udata['code'] == 200){
        // session(['staffdata' => $logresp['user']]);
        // $cuser = User::find($udata['data']);        
        // dd(session()->all());
        //$cuser = User::where('staff_no', $req->username)->first();
        
        $cuser = User::where(DB::raw('REPLACE(UPPER(TRIM(staff_no))," ","")'), $inp_staffno)->first();
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
      return redirect()->back()->withErrors(['username' => $udata['msg']]);
    }
}
