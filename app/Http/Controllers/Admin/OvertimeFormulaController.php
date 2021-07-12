<?php

namespace App\Http\Controllers\Admin;

use App\OvertimeFormula;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
class OvertimeFormulaController extends Controller
{
    public function show(Request $req){  
        $of = OvertimeFormula::all();  
        return view('admin.otformula', ['of' => $of]);
    }
}
