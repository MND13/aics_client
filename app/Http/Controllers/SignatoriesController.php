<?php

namespace App\Http\Controllers;

use App\Models\Signatories;
use Illuminate\Http\Request;

class SignatoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Signatories::all();
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
        $sig = new Signatories;
        $sig->fill($request->toArray());
        $sig->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Signatories  $signatories
     * @return \Illuminate\Http\Response
     */
    public function show(Signatories $signatories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Signatories  $signatories
     * @return \Illuminate\Http\Response
     */
    public function edit(request $request)
    {
        /*$sig = Signatories::findOrFail($request->id);
        if ($sig) {
            $sig->fill($request->toArray());
            $sig->save();
        }*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Signatories  $signatories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Signatories $signatories)
    {
        $sig = Signatories::findOrFail($request->id);
        if ($sig) {
            $sig->fill($request->toArray());
            $sig->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Signatories  $signatories
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request)
    {
        $sig = Signatories::findOrFail($request->id);
        if ($sig) {
            $sig->delete();
            
        }
    }
}
