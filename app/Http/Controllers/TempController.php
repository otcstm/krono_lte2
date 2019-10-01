<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shared\LdapHelper;

class TempController extends Controller
{
  public function loadDummyUser(Request $req){
    return LdapHelper::loadDummyAccount(3000);
  }

}
