<?php

namespace App\Http\Controllers;

use App\Models\AssessmentOptions;
use Illuminate\Http\Request;

class AssessmentOptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #return AssessmentOptions::all();
        return AssessmentOptions::whereIn("id",[1,2,3,4,5,9])->get();
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
     * @param  \App\Models\AssessmentOptions  $assessmentOptions
     * @return \Illuminate\Http\Response
     */
    public function show(AssessmentOptions $assessmentOptions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AssessmentOptions  $assessmentOptions
     * @return \Illuminate\Http\Response
     */
    public function edit(AssessmentOptions $assessmentOptions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssessmentOptions  $assessmentOptions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssessmentOptions $assessmentOptions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssessmentOptions  $assessmentOptions
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssessmentOptions $assessmentOptions)
    {
        //
    }
}
