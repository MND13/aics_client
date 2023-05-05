<?php

namespace App\Http\Controllers;

use App\Models\AicsAssessment;
use App\Models\AicsAssistance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AicsAssessmentController extends Controller
{
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
    public function create(Request $request)
    {
        if (Auth::check() &&   Auth::user()->hasRole('admin')) {


            DB::beginTransaction();
            try {
                //code...

                $assessment = new AicsAssessment();
                $assessment->fill($request->toArray());
                $assessment->save();

                if ($assessment->id) {

                    $gis = AicsAssistance::findOrFail($request->assistance_id);
                    if($gis)
                    {
                        $gis->assessment_id = $assessment->id;
                        $gis->mode_of_admission = $assessment->mode_of_admission;
                        $gis->status = "Serving";
                        $gis->save();
                    }
                    
                    DB::commit();
                    return ["message" => "Saved!"];
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }
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
     * @param  \App\Models\AicsAssessment  $aicsAssessment
     * @return \Illuminate\Http\Response
     */
    public function show(AicsAssessment $aicsAssessment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AicsAssessment  $aicsAssessment
     * @return \Illuminate\Http\Response
     */
    public function edit(AicsAssessment $aicsAssessment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AicsAssessment  $aicsAssessment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AicsAssessment $aicsAssessment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AicsAssessment  $aicsAssessment
     * @return \Illuminate\Http\Response
     */
    public function destroy(AicsAssessment $aicsAssessment)
    {
        //
    }
}
