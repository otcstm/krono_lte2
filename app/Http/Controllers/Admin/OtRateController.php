<?php


namespace App\Http\Controllers\Admin;
use App\OtRate;
use Illuminate\Http\Request;
use App\Shared\UserHelper;
use App\Company;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;

class OtRateController extends Controller
{
  public function index()
  {
      $otrate = OtRate::all();
      $company = Company::all();

      return view('admin.compregion', ['otrates' => $otrate,'companies' => $company]);
  }
}
