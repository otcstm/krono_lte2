<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shared\AlertHelper;

class NotiMenuController extends Controller
{
  public function read(Request $req){
    if($req->filled('nid')){

      $nitody = $req->user()->notifications->where('id', $req->nid)->first();
      if($nitody){
        $nitody->markAsRead();
        return redirect(AlertHelper::getUrl($nitody));
      }

      // not found. maybe belong to other user / not currently logged in user
      return redirect(route('misc.home'));

    } else {
      // no type. just redirect to home page
      return redirect(route('misc.home'));
    }
  }
}
