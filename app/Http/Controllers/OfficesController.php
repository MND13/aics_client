<?php

namespace App\Http\Controllers;

use App\Models\Offices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OfficesRequest;


class OfficesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return offices::select("id", "name", "address", "contact_person", "contact_no")->get();
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
    public function store(OfficesRequest $request)
    {
        DB::beginTransaction();

        try {
            $office = new Offices;
            $office->fill($request->toArray());
            $office->save();
            DB::commit();
            return ["message" => "Saved!"];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Offices  $offices
     * @return \Illuminate\Http\Response
     */
    public function show(Offices $offices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Offices  $offices
     * @return \Illuminate\Http\Response
     */
    public function edit(Offices $offices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offices  $offices
     * @return \Illuminate\Http\Response
     */
    public function update(OfficesRequest $request)
    {
        $office = Offices::findOrFail($request->id);
        
        if($office){
            $office->fill($request->toArray());
            $office->save();
        }
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offices  $offices
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $office = Offices::findOrFail($id);
        $office->delete();
      
       
    }
}
