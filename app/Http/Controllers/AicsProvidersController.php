<?php

namespace App\Http\Controllers;

use App\Models\AicsProviders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AicsProvidersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AicsProviders::orderBy("company_name")->get();
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
        try {
            if (Auth::check() &&  Auth::user()->hasRole(['super-admin'])) {

                $validator = Validator::make($request->all(), [
                    'addressee_name' => 'required',
                    'company_name' => 'required',
                    'company_address' => 'required',
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return response(['errors' => $errors], 422);
                }

                DB::beginTransaction();
                $f = new AicsProviders();
                $f->fill($request->toArray());
                $f->save();
                DB::commit();
                return ["message" => "Saved"];
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AicsProviders  $aicsProviders
     * @return \Illuminate\Http\Response
     */
    public function show(AicsProviders $aicsProviders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AicsProviders  $aicsProviders
     * @return \Illuminate\Http\Response
     */
    public function edit(AicsProviders $aicsProviders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AicsProviders  $aicsProviders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AicsProviders $aicsProviders)
    {
        try {

            if (Auth::check() &&  Auth::user()->hasRole(['super-admin'])) {

                $p = AicsProviders::findOrFail($request->id);
                if ($p) {
                    $validator = Validator::make($request->all(), [
                        'addressee_name' => 'required',
                        'company_name' => 'required',
                        'company_address' => 'required',
                    ]);

                    if ($validator->fails()) {
                        $errors = $validator->errors();
                        return response(['errors' => $errors], 422);
                    }

                    DB::beginTransaction();

                    $p->fill($request->toArray());
                    $p->save();
                    DB::commit();
                    return ["message" => "Saved"];
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AicsProviders  $aicsProviders
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request)
    {
        try {

            if (Auth::check() &&  Auth::user()->hasRole(['super-admin'])) {
                $f = AicsProviders::findOrFail($request->id);
                $f->delete();
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
