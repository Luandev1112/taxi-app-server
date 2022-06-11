<?php

namespace App\Http\Controllers;

use App\Adhoc;
use Illuminate\Http\Request;

class AdhocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('adhoc.adhoc1');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd(request()->phone);
         $default_lat = env('DEFAULT_LAT');
        $default_lng = env('DEFAULT_LNG');
        return view('adhoc.adhoc2')->with(compact('default_lat','default_lng'));
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
     * @param  \App\Adhoc  $adhoc
     * @return \Illuminate\Http\Response
     */
    public function show(Adhoc $adhoc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Adhoc  $adhoc
     * @return \Illuminate\Http\Response
     */
    public function edit(Adhoc $adhoc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Adhoc  $adhoc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Adhoc $adhoc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Adhoc  $adhoc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adhoc $adhoc)
    {
        //
    }
}
