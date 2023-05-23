<?php

namespace App\Http\Controllers;

use App\Models\AicsAssessment;
use App\Models\AicsAssistance;
use App\Models\CertOfEligibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        if (Auth::check() &&   !Auth::user()->hasRole('user')) {


            DB::beginTransaction();
            try {


                $gis = AicsAssistance::findOrFail($request->gis_id);

                if ($gis) {

                    $validator = Validator::make($request->all(), [
                        'category_id' => ['required', 'exists:categories,id'],
                        'mode_of_admission' => 'required',
                        'assessment' => 'required',
                        'purpose' => 'required',
                        'amount' => 'required',
                        'fund_source_id' => 'required',
                        'mode_of_assistance' => 'required',
                        'interviewed_by' => 'required',
                        'approved_by_id' => 'required',

                    ]);

                    if ($validator->fails()) {
                        $errors[] = $validator->errors();
                        return response(['errors' => $errors], 422);
                    }



                    $assessment = new AicsAssessment();
                    $assessment->fill($request->toArray());
                    $assessment->interviewed_by_id = Auth::id();
                    $assessment->records = json_encode($request->records);
                    $assessment->save();

                    if ($assessment->id) {

                        //UPDATE GIS / SENT BY USER

                        $gis->assessment_id = $assessment->id;
                        $gis->mode_of_admission = $assessment->mode_of_admission;
                        $gis->status = "Served";
                        $gis->save();

                        DB::commit();
                        return ["message" => "Saved!"];
                    }
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
    public function update(Request $request)
    {
        if (Auth::check() && !Auth::user()->hasRole(['user'])) {


            DB::beginTransaction();
            try {

                $validator = Validator::make($request->all(), [
                    'category_id' => 'required',
                    'mode_of_admission' => 'required',
                    'assessment' => 'required',
                    'purpose' => 'required',
                    'amount' => 'required',
                    'fund_source_id' => 'required',
                    'mode_of_assistance' => 'required',
                    'interviewed_by' => 'required',
                    'approved_by_id' => 'required',

                ]);

                if ($validator->fails()) {
                    $errors[] = $validator->errors();
                    return response(['errors' => $errors], 422);
                }

                $assessment = AicsAssessment::findOrFail($request->id);

                if ($assessment) {
                    $assessment->fill($request->toArray());
                    $assessment->records = json_encode($request->records);
                    $assessment->save();
                    
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
