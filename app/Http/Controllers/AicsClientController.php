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
        //  $client =  AicsClient::with("psgc", "aics_type", "payroll_client.payroll", "category", "subcategory")->findOrFail($id);
        $assistance =  AicsAssistance::with(
            "aics_client.psgc",
            "aics_type:id,name",
            "aics_documents",
            "aics_documents.requirement:id,name",

        )->where("uuid", "=", $uuid)->first();
       

        if ($assistance) {
            
           
            $pdf = Pdf::loadView('pdf.gis', ["assistance" =>   $assistance->toArray() ]);
            return $pdf->stream('gis.pdf');
        }
    }
}
