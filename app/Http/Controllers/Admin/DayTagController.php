<?php

namespace App\Http\Controllers\Admin;
use App\DayTag;
use App\Shared\UserHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use \Carbon\Carbon;
use DB;

class DayTagController extends Controller
{
  public function index(Request $req){

    // $projectlist = Project::all();
    $ph_list = [];

    if($req->filled('searching')){
            // dd('filled',$req);
      $ph_list = $this->fetch($req);
      // dd($email_list);
    }
    // dd('takde',$req);
    return view('admin.daytagPH', ['ph_lists' => $ph_list]);

  }

  public function fetch(Request $req)
  {
    $fuserid = $req->fuserid;
    $dt_list = DayTag::query();

    if(isset($fuserid)){
      $dt_list = $dt_list->where('user_id',$fuserid)
      ->orderBy('phdate','asc')->get();
    }else{
      $dt_list = [];
    }
    return $dt_list;

  }

  public function store(Request $req)
  {
      $ph= $req->inputpho;
      $fuserid = $req ->inuserid;

      $check = DayTag::whereDate('phdate',"=", $ph)
            ->where('user_id',$fuserid)
            ->get();

      if(count($check)==0){
        $new_ph = new DayTag;
        $new_ph->user_id = $fuserid;
        $new_ph->phdate = $ph;
        $new_ph->date = $req ->inrepd;
        $new_ph->status = $req ->inputstat;
        $new_ph-> save();
        $execute = UserHelper::LogUserAct($req, "Day Tag", "Create Day Tag. [User_id:$fuserid , PH_Date:$ph] ");

        return redirect(route('dt.list', ['fuserid'=>$fuserid,'searching'=>'searching'], false))
        ->with([
          'a_text'=>"Public Holiday successfully created. User_id:$fuserid, PH_Date:$ph",
          'feedback' => true,
          'a_icon'=>'success'
        ]);

      }else{
        return redirect(route('dt.list', ['fuserid'=>$fuserid,'searching'=>'searching'], false))
        ->with([
          'a_text'=>"Failed! Public Holiday already exist.",
          'feedback' => true,
          'a_icon'=>  'error'
        ]);

      }
  }

  public function update(Request $req)
  {
     $dayid = $req ->inputid;
     $fuserid = $req ->inuid;
     $ps = DayTag::where('id',$dayid)->where('user_id',$fuserid)->first();
     // dd($ps);
       if($ps){
         $ps-> phdate= $req->inputphd;
         $ps-> date = $req->inputrepd;
         $ps-> status = $req->inputstatus;
         $ps->save();
         $execute = UserHelper::LogUserAct($req, "Day Tag", "Update Day Tag , PH :$req->inputphd ,USER ID :$fuserid " );
         return redirect(route('dt.list', ['fuserid'=>$fuserid,'searching'=>'searching'], false))
         ->with([
           'feedback' => true,
           'a_text'=>"Public Holiday updated!",
           'a_icon'=>"success"
         ]);

       }else{
         return redirect(route('dt.list', ['fuserid'=>$fuserid,'searching'=>'searching'], false))
         ->with([
           'feedback' => true,
           'a_text'=>"Record not found.",
            'a_icon'=>"warning"
          ]);
       }
  }


  public function destroy(Request $req)
  {

    $ps = DayTag::find($req->inputid);
    if($ps){
      // $ps->save();
      $fuserid = $ps -> user_id;

      $dt = $ps -> phdate;
      $execute = UserHelper::LogUserAct($req, "Day Tag", "Delete Day Tag , PH :$dt ,USER ID :$fuserid ");


      $ps->delete();
      $a_text = "Public holiday deleted!";
      $a_type = "success";
    } else {
      $a_text = "Record not found";
      $a_type = "error";
    }
    return redirect(route('dt.list',['fuserid'=>$fuserid,'searching'=>'searching'], false))
    ->with([
      'a_text'=>$a_text ,
      'a_icon'=>$a_type,
    'feedback' => true]);
  }


}
