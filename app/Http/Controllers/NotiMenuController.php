<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shared\AlertHelper;

class NotiMenuController extends Controller
{
  public function read(Request $req){
    if($req->filled('nid')){

      $nitody = $req->user()->notifications->where('id', $req->nid)->first();
      $nitody->markAsRead();
      return redirect(AlertHelper::getUrl($nitody));

    } else {
      // no type. just redirect to home page
      return redirect(route('home'));
    }
  }
}
