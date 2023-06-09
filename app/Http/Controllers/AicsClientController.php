<?php

namespace App\Http\Controllers;

use App\Exports\ImportErrors;
use App\Models\AicsClient;
use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Imports\ClientsImport;
use App\Models\AicsAssistance;
use App\Models\AicsType;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\DirtyList;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use \NumberFormatter;






class AicsClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return AicsClient::paginate(10);
        // return AicsClient::with("psgc", "payroll_client.payroll")->get();
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AicsClient  $aicsClient
     * @return \Illuminate\Http\Response
     */
    public function show(AicsClient $aicsClient, $id)
    {
        $aics_client = $aicsClient->findOrFail($id);
        return $aics_client;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AicsClient  $aicsClient
     * @return \Illuminate\Http\Response
     */
    public function edit(AicsClient $aicsClient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AicsClient  $aicsClient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AicsClient  $aicsClient
     * @return \Illuminate\Http\Response
     */
    public function destroy(AicsClient $aicsClient)
    {
        //
    }


    public function gis($uuid)
    {


        $assistance =  AicsAssistance::with(

            "aics_type:id,name",
            "aics_client:id,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number",
            "aics_client.psgc:id,region_name,province_name,city_name,brgy_name,region_name_short",
            "assessment.fund_source:id,name",
            "assessment.category:id,category",
            "assessment.subcategory:id,subcategory",
            "assessment.interviewed_by:id,first_name,middle_name,last_name,ext_name",
            
        )->where("uuid", "=", $uuid)->first();


        if ($assistance) {
            $pdf = Pdf::loadView('pdf.gis', ["assistance" =>   $assistance->toArray()]);
            return $pdf->stream('gis.pdf');
        }
    }

    public function coe($uuid)
    {
        $assistance =  AicsAssistance::with(

            "aics_type:id,name",
            "aics_client:id,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number",
            "aics_client.psgc:id,region_name,province_name,city_name,brgy_name,region_name_short",
            "assessment.fund_source:id,name",
            "assessment.interviewed_by:id,first_name,middle_name,last_name,ext_name",
            
        )->where("uuid", "=", $uuid)->first();

        $res = $assistance->toArray();
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);


        if ($assistance) {
            $pdf = Pdf::loadView('pdf.coe', 
                [
                    "client" => $res["aics_client"],
                    "assistance" => $res,
                    "age" => Carbon::parse($res["aics_client"]["birth_date"])->age,
                    "records"=> json_decode($res['assessment']['records']),
                    "amount_in_words" => $f->format($res["assessment"]["amount"]),
                ]
            );
            return $pdf->stream('coe.pdf');
        }
    }

}
