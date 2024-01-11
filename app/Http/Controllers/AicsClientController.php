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
use App\Models\User;



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

        #\DB::enableQueryLog();
        $assistance =  AicsAssistance::with(

            "aics_type:id,name",
            "aics_client:id,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number",
            "aics_client.psgc:id,region_name,province_name,city_name,brgy_name,region_name_short",
            #"assessment.fund_sources:id,assessment_id,fund_source_id,amount,remarks",
            "assessment.fund_sources.fund_source:id,name",
            "assessment.category:id,category",
            "assessment.subcategory:id,subcategory",
            "assessment.interviewed_by:id,first_name,middle_name,last_name,ext_name",
            "aics_beneficiary",
            "aics_beneficiary.psgc:id,region_name,province_name,city_name,brgy_name,region_name_short",
            "assessment.signatory:id,name",
           
        )->with("assessment.fund_sources", function ($q) {
            $q->where("remarks", "!=", "CANCELLED")
                ->where("remarks", "!=", "REVERSAL")
                ->orWhereNull("remarks");
            return $q;
        })
        ->where("uuid", "=", $uuid)
        ->firstOrFail();

        if ($assistance) {

            $res = $assistance->toArray();
            $args = array();
           
            /*if(isset($res["aics_beneficiary"]))
            {
                $args = 
                [
                    "aics_beneficiary" => $res["aics_beneficiary"],
                    "aics_client" =>  $res["aics_client"],  
                    "assessment" =>  $res["assessment"]  
                ];
            }else
            {
                $args =[ 
                    "aics_beneficiary" => $res["aics_client"],
                    "assessment" =>  $res["assessment"], 
                    "ff"                   
                ];
            }*/
           
          
            
            $pdf = Pdf::loadView('pdf.gis', $res);
          #  $pdf = Pdf::loadView('pdf.gis', ["assistance" =>   $assistance->toArray()]);
            return $pdf->stream('gis.pdf');
        }
    }

    public function coe($uuid)
    {
        $assistance =  AicsAssistance::with(

            "aics_type:id,name",
            "aics_client:id,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number",
            "aics_client.psgc:id,region_name,province_name,city_name,brgy_name,region_name_short",
            "assessment.fund_sources:id,assessment_id,fund_source_id,amount",
            "assessment.fund_sources.fund_source:id,name",
            "assessment.interviewed_by:id,first_name,middle_name,last_name,ext_name",
            "assessment.category:id,category",
            "assessment.subcategory:id,subcategory",
            "assessment.provider:id,company_name",
            "assessment.signatory:id,name,position",
            "aics_beneficiary",
            "aics_beneficiary.psgc:id,region_name,province_name,city_name,brgy_name,region_name_short",
           


        )->where("uuid", "=", $uuid)->firstOrFail();

        $res = $assistance->toArray();
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);

        $assistance_type = $assistance->aics_type->name;
        
        if(str_contains( strtolower( $assistance->aics_type->name), "medical"))
        {
            $assistance_type =  "Medical Assistance"; # GENERALIZE ALL MEDICAL ASSISTANCE TYPE
        }

      
        if ($assistance) {
            $pdf = Pdf::loadView(
                'pdf.coe',
                [
                    "client" => $res["aics_client"],
                    "assistance" => $res,
                    "assistance_type" => $assistance_type,
                    "age" => Carbon::parse($res["aics_client"]["birth_date"])->age,
                    "records" => json_decode($res['assessment']['records']),
                    "records_others" => isset($res['assessment']['records_others']) ? $res['assessment']['records_others'] : "" ,
                    "amount_in_words" => $f->format($res["assessment"]["amount"]),
                    "bene"=> $res["aics_beneficiary"],
                    "relationship" => $res["rel_beneficiary"]
                ]
            );
            return $pdf->stream('coe.pdf');
        }
    }

    public function cav($uuid)
    {
        $assistance =  AicsAssistance::with(

            "aics_type:id,name",
            "aics_client:id,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number",
            "aics_client.psgc:id,region_name,province_name,city_name,brgy_name,region_name_short",
            "assessment.fund_sources:id,assessment_id,fund_source_id,amount",
            "assessment.fund_sources.fund_source:id,name",
            "assessment.category:id,category",
            "assessment.subcategory:id,subcategory",
            "assessment.interviewed_by:id,first_name,middle_name,last_name,ext_name",
            "assessment.signatory:id,name",

        )->where("uuid", "=", $uuid)->firstOrFail();

        $res = $assistance->toArray();
        if ($assistance) {
            $pdf = Pdf::loadView('pdf.cav', ["assistance" =>   $assistance->toArray(),  "client" => $res["aics_client"]])
                ->setPaper('a4', 'landscape');;
            return $pdf->stream('cav.pdf');
        }
    }

    public function gl($uuid)
    {
        $assistance =  AicsAssistance::with(
            "aics_type:id,name",
            "aics_client:id,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number",
            "aics_client.psgc:id,region_name,province_name,city_name,brgy_name,region_name_short",
            "assessment.fund_sources:id,assessment_id,fund_source_id,amount",
            "assessment.fund_sources.fund_source:id,name",
            "assessment.category:id,category",
            "assessment.subcategory:id,subcategory",
            "assessment.interviewed_by:id,first_name,middle_name,last_name,ext_name",
            "assessment.provider",
            "assessment.signatory:id,name,position",
            "assessment.gl_signatory:id,name,position",
            "aics_beneficiary",
            "aics_beneficiary.psgc:id,region_name,province_name,city_name,brgy_name,region_name_short",
            "assessment.gl_for_signatory:id,name,position",
          

        )->where("uuid", "=", $uuid)->firstOrFail();


        $res = $assistance->toArray();
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);

        $assistance_type = $assistance->aics_type->name;
        
        if(str_contains( strtolower( $assistance->aics_type->name), "medical"))
        {
            $assistance_type =  "Medical Assistance"; # GENERALIZE ALL MEDICAL ASSISTANCE TYPE
        }
 
        if ($assistance) {
            $pdf = Pdf::loadView(
                'pdf.gl',
                [
                    "assistance" =>   $assistance->toArray(),
                    "assistance_type" => $assistance_type,
                    "client" => $res["aics_client"],
                    "amount_in_words" => $f->format($res["assessment"]["amount"]),
                    "bene"=> $res["aics_beneficiary"],
                   
                ]
            );
            return $pdf->stream('gl.pdf');
        }
    }


    
}
