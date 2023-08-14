<?php

namespace App\Http\Controllers;

use App\Models\AicsAssessmentFundSource;
use App\Models\FundSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FundSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $fundsources =  FundSource::with("journal")->get();
        $results = array();
        foreach ($fundsources as $key => $value) {
            $results[$key] = $value;
            if ($value->journal->getCurrentBalanceInDollars() > 0) {
                $results[$key]["current_balance"] = $value->journal->getCurrentBalanceInDollars();
            }
            unset($results[$key]["journal"]);
        }

        return  $results;
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
                    'name' => 'required',
                    'legislators' => 'required',
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors();
                    return response(['errors' => $errors], 422);
                }

                DB::beginTransaction();
                $f = new FundSource;
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
     * @param  \App\Models\FundSource  $fundSource
     * @return \Illuminate\Http\Response
     */
    public function show(FundSource $fundSource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FundSource  $fundSource
     * @return \Illuminate\Http\Response
     */
    public function edit(FundSource $fundSource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FundSource  $fundSource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FundSource $fundSource)
    {
        try {

            if (Auth::check() &&  Auth::user()->hasRole(['super-admin'])) {

                $f = FundSource::findOrFail($request->id);
                if ($f) {
                    $validator = Validator::make($request->all(), [
                        'name' => 'required',
                        'legislators' => 'required',
                    ]);

                    if ($validator->fails()) {
                        $errors = $validator->errors();
                        return response(['errors' => $errors], 422);
                    }

                    DB::beginTransaction();

                    $f->fill($request->toArray());
                    $f->save();
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
     * @param  \App\Models\FundSource  $fundSource
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request)
    {
        try {

            if (Auth::check() &&  Auth::user()->hasRole(['super-admin'])) {
                DB::beginTransaction();
                $f = FundSource::findOrFail($request->id);
                $f->delete();
                DB::commit();
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
