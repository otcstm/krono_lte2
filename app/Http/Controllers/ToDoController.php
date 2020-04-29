<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Overtime;
use App\User;
use App\Notifications\TodoDraft;
use Illuminate\Database\Eloquent\Model;

class ToDoController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function notifyTodo(Request $req)
    {
        //For status Draft & Query
        $user = $req->user();
        $draftCount = Overtime::where('user_id', $req->user()->id)
            ->whereIn('status', array('D1', 'D2', 'Q1', 'Q2'))->get()->count();

        $todoObj = [

            'draftCount' => $draftCount
        ];
        /*
              $collection = collect($todoObj);
              */
        $user->notify(new TodoDraft($todoObj));
    }

    public function show(Request $req)
    {
        $user = $req->user();
        //For status Draft & Query
        $draftCount = Overtime::where('user_id', $req->user()->id)
            ->whereIn('status', array('D1', 'D2', 'Q1', 'Q2'))->get()->count();
        $verifyCount = Overtime::where('verifier_id', $req->user()->id)
            ->whereIn('status', array('PV'))->get()->count();
        $todoObj = [

            'draftCount' => $draftCount,
            'verifyCount' => $verifyCount
        ];

        $user->notify(new TodoDraft($todoObj));

        return view('todo.list', $todoObj);
    }
}
