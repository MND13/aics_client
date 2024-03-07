<?php

namespace App\Http\Controllers;

use App\Models\SignatoriesSettings;
use Illuminate\Http\Request;
use App\Models\Signatories;
use Illuminate\Support\Arr;
use App\Http\Requests\SignatorySettingsRequest;


class SignatoriesSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SignatoriesSettings::all()->transform(function ($s) {

            $ss = implode(",", json_decode($s->signatories));
            $s->names =  Signatories::select("id", "name", "initials")
                ->whereIn("id", json_decode($s->signatories))
                ->orderByRaw('FIELD(id,' .  $ss . ')')
                ->get();


            $i = "";
            foreach ($s->names as $key => $value) {
                $i .= $value["initials"] . "/";
            }
            $s->i = $i;
            return $s;
        });
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
    public function store(SignatorySettingsRequest $request)
    {
        

        try {
            $s = new SignatoriesSettings;
            $s->fill($request->toArray());
            $s->signatories = json_encode($request->signatories);
            $s->save();
            return $s;
        } catch (\Throwable $th) {
            return  $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SignatoriesSettings  $signatoriesSettings
     * @return \Illuminate\Http\Response
     */
    public function show(SignatoriesSettings $signatoriesSettings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SignatoriesSettings  $signatoriesSettings
     * @return \Illuminate\Http\Response
     */
    public function edit(SignatoriesSettings $signatoriesSettings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SignatoriesSettings  $signatoriesSettings
     * @return \Illuminate\Http\Response
     */
    public function update(SignatorySettingsRequest $request, SignatoriesSettings $signatoriesSettings)
    {

        try {
            $s = SignatoriesSettings::findorFail($request->id);

            $s->min_range = $request->min_range;
            $s->max_range = $request->max_range;
            $ids = array();
            if (isset($request->signatories["0"]["id"])) {
                $ids = array_column($request->signatories, 'id');
                $s->signatories = json_encode($ids);
            } else {
                $s->signatories = json_encode($request->signatories);
            }


            $s->save();
            return $s;
        } catch (\Throwable $th) {
            return  $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SignatoriesSettings  $signatoriesSettings
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $s = SignatoriesSettings::findOrFail($id);
        if ($s) {
            return  $s->delete();
        }
    }
}
