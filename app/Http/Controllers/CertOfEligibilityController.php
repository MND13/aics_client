<?php

namespace App\Http\Controllers;

use App\Models\AicsAssistance;
use App\Models\CertOfEligibility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertOfEligibilityController extends Controller
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
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {


            $gis = AicsAssistance::where("uuid","=",$request->uuid)->first();

            if ($gis) {
               
                $coe = new CertOfEligibility;
                $coe->sdo = $request->sdo;
                $coe->records = json_encode($request->records);
                $coe->save();

                $gis->coe_id =  $coe->id;
                $gis->save();
                DB::commit();
            }
            return ["message" => "Saved"];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CertOfEligibility  $certOfEligibility
     * @return \Illuminate\Http\Response
     */
    public function show(CertOfEligibility $certOfEligibility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CertOfEligibility  $certOfEligibility
     * @return \Illuminate\Http\Response
     */
    public function edit(CertOfEligibility $certOfEligibility)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CertOfEligibility  $certOfEligibility
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CertOfEligibility $certOfEligibility)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CertOfEligibility  $certOfEligibility
     * @return \Illuminate\Http\Response
     */
    public function destroy(CertOfEligibility $certOfEligibility)
    {
        //
    }
}
