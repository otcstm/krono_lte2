<?php

namespace App\Http\Controllers\Dummy;

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Mail\SendMailable;
use App\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function dummyEmail()
    {

        $name = 'Afdzal';


      //  return 'Email was sent';

     return view('dummy.email.dummy', []);

    }

    public function sendDummyEmail()
    {

        $name = 'Afdzal';
      //  Mail::to(['nuramirah.adnan@tm.com.my','zatiaqmar.zahari@tm.com.my','afdzal@tm.com.my','mimi.maisara@tm.com.my'])->send(new SendMailable($name));

      //  return 'Email was sent';

     //return view('dummy.email.dummy', []);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function edit(Email $email)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Email $email)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(Email $email)
    {
        //
    }
}
