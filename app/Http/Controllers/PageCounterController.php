<?php

namespace App\Http\Controllers;

use App\PageCounter;
use Illuminate\Http\Request;

class PageCounterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req,$tag)
    {
        $ip_address = $req->ip();
        $user_agent = $req->userAgent();

        $pc = new PageCounter;
        $pc->tag = $tag;
        $pc->ip_address = $ip_address;
        $pc->user_agent = $user_agent;
        $pc->save();

        $counter = PageCounter::where('tag',$tag)->count();



        

        return view('log.pageCounter', [
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'tag' => $tag,
            'counter' => $counter
          ]);
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
     * @param  \App\PageCounter  $pageCounter
     * @return \Illuminate\Http\Response
     */
    public function show(PageCounter $pageCounter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PageCounter  $pageCounter
     * @return \Illuminate\Http\Response
     */
    public function edit(PageCounter $pageCounter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PageCounter  $pageCounter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PageCounter $pageCounter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PageCounter  $pageCounter
     * @return \Illuminate\Http\Response
     */
    public function destroy(PageCounter $pageCounter)
    {
        //
    }
}
