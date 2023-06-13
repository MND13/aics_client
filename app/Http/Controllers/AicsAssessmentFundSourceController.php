<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AicsAssessmentFundSource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AicsAssessmentFundSourceController extends Controller
{
    public function index()
    {
        return AicsAssessmentFundSource::orderBy("created_at", "desc")
        ->with(["fund_source:id,name", "assessment.assistance.aics_client:id,full_name",  "assessment.provider" ])
       



       // ->with(["assessment:id,mode_of_assistance"])
        //  ->with("assessment.assistance.aics_client:id,full_name")
        
       // ->with("assessment.assistance:id")
       // ->with("assessment.assistance.aics_client:id,full_name,uuid")
        ->get();
    }

    public function create(Request $request)
    {
        //movement -1 debit | 1 credit 
        //value = amount x movement
        try {


            $validator = Validator::make($request->all(), [
                'amount' => 'required',
                'movement' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response(['errors' => $errors], 422);
            }

            DB::beginTransaction();

            $txn = new AicsAssessmentFundSource();
            $txn->fund_source_id = $request->fund_source_id;
            $txn->movement = $request->movement;
            $txn->amount = $request->amount;
            $txn->remarks = $request->remarks;
            $txn->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
