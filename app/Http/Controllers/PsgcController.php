<?php

namespace App\Http\Controllers;

use App\Models\Psgc;
use Illuminate\Http\Request;

class PsgcController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Psgc::getBrgys("region_psgc", "170000000");
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
     * @param  \App\Models\Psgc  $psgc
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $type)
    {
        

        switch ($type) {
            case 'region':
                return Psgc::getRegions();
                break;
            case 'cities':
                $field = $request->field;
                $value = $request->value;
                return Psgc::getCities($field, $value)->toArray();
            case 'brgy':
                $field = $request->field;
                $value = $request->value;
                return Psgc::getBrgys($field, $value);
            case 'id':
                return Psgc::find($request->id);
            default:
                # code...
                break;
            }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Psgc  $psgc
     * @return \Illuminate\Http\Response
     */
    public function edit(Psgc $psgc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Psgc  $psgc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Psgc $psgc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Psgc  $psgc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Psgc $psgc)
    {
        //
    }
}
