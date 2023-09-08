<?php

namespace App\Http\Controllers;

use App\Models\AicsAssessment;
use App\Models\AicsAssistance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\AicsAssessmentFundSource;
use App\Models\FundSource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

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

                $gis = AicsAssistance::with("aics_client")->findOrFail($request->gis_id);

                if ($gis) {

                    $validator = Validator::make($request->all(), [
                        'category_id' => ['required', 'exists:categories,id'],
                        'mode_of_admission' => 'required',
                        'assessment' => 'required',
                        'purpose' => 'required',
                        'amount' => 'required',
                        'fund_sources' => 'required',
                        'mode_of_assistance' => 'required',
                        'signatory_id' => 'required',
                    ]);

                    if ($validator->fails()) {
                        $errors[] = $validator->errors();
                        return response(['errors' => $errors], 422);
                    }

                    $assessment = new AicsAssessment();
                    $assessment->fill($request->toArray());
                    $assessment->interviewed_by_id = Auth::id();
                    $assessment->records = json_encode($request->records);
                    $assessment->control_no = "FO11APPS-AICS-" . Carbon::now()->format('Y-m-dHisu') . $gis->office->office_code;
                    $assessment->save();

                    if ($assessment->id) {

                        //UPDATE GIS / SENT BY USER

                        $gis->assessment_id = $assessment->id;
                        $gis->mode_of_admission = $assessment->mode_of_admission;
                        $gis->status = "Serving";
                        $gis->save();

                        foreach ($request->fund_sources as $key => $value) {

                            $fs = FundSource::findOrFail($value["id"]);

                            if ($fs) {
                                $txn = new AicsAssessmentFundSource();
                                $txn->fund_source_id = $value["id"];
                                $txn->assessment_id = $assessment->id;
                                $txn->movement = $request->movement ? $request->movement : -1;
                                $txn->amount = $value["amount"];
                                $txn->remarks =  $request->remarks;
                                $txn->save();
                            }

                            $memo = $gis->mode_of_assistance;

                            if ($request->movement > 0) {
                                $transaction_1 = $fs->journal->creditDollars($request->amount, $memo);
                                $transaction_1->referencesObject($gis);
                            } else {

                                $transaction_1 = $fs->journal->debitDollars($request->amount, $memo);
                                $transaction_1->referencesObject($gis);
                            }
                        }

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
                    'mode_of_assistance' => 'required',
                    'interviewed_by' => 'required',
                    'signatory_id' => 'required',

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

                    #DEBIT FUND SOURCE / BAWASAN ANG FUND
                    if ($request->fund_sources) {
                        foreach ($request->fund_sources as $key => $value) {

                            if (!isset($value["txn_id"])) {
                                $fs = FundSource::findOrFail($value["id"]);

                                if ($fs) {
                                    $txn = new AicsAssessmentFundSource();
                                    $txn->fund_source_id = $value["id"];
                                    $txn->assessment_id = $assessment->id;
                                    $txn->movement = -1;
                                    $txn->amount = $value["amount"];
                                    $txn->save();
                                }

                                $transaction_1 = $fs->journal->debitDollars($value["amount"]);
                                $transaction_1->referencesObject($assessment);
                            }
                        }
                    }

                    # CREDIT BACK TO FUND SOURCE / IBALIK ANG FUND
                    if ($request->fs_reversal) {
                        foreach ($request->fs_reversal as $key => $value) {


                            $fs = AicsAssessmentFundSource::find($value["txn_id"]);
                            $fs->remarks = "CANCELLED";
                            $fs->save();

                            $fs_txn = new AicsAssessmentFundSource;
                            $fs_txn->amount = $value["amount"];
                            $fs_txn->movement = "1";
                            $fs_txn->fund_source_id  = $fs->fund_source_id;
                            $fs_txn->assessment_id   = $assessment->id;
                            $fs_txn->remarks   = "REVERSAL";
                            $fs_txn->save();

                            $fs = FundSource::findOrFail($fs->fund_source_id);
                            $transaction_1 = $fs->journal->creditDollars($value["amount"]);
                            $transaction_1->referencesObject($assessment);
                        }
                    }



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

    public function sms($request)
    {   
       $msg = "Maayong Adlaw! Kani na mensahe gikan sa DSWD Davao Region Office. Pwede na makuha ang  MEDICINE ASSISTANCE. Mamalihog mi na DALAHON ANG ORIGINAL DOCUMENTS na gi upload, apil isa ka VALID ID . Daghang Salamat!";
       $response = Http::get('http://34.80.139.96/api/v2/SendSMS?ApiKey=LWtHZKzgbIh1sNQUPInRyqDFsj8W0K+8YCeSIdN08zA=&ClientId=3b3f49c9-b8e2-4558-9ed2-d618d7743fd5&SenderId=DSWD11AICS&Message=' . $msg . '&MobileNumbers=63' . substr($request->aics_client->mobile_number, 1));
       return $response->collect();
    }
}
