<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AicsAssessmentFundSource;
use App\Models\FundSource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AicsAssessmentFundSourceController extends Controller
{
    public function index()
    {
        /*$collection =  AicsAssessmentFundSource::orderBy("created_at", "desc")
            ->with([
                "fund_source:id,name",
                "assessment.assistance.aics_client:id,full_name",
                "assessment.provider"
            ])
            ->get();  
            
            $collection = Accouunt*/
        return FundSource::with("journal", "journal.transactions")->get();
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

            $fs = FundSource::findOrFail($request->fund_source_id);

            if ($fs) {
                /* BACKUP RECORD */
                $txn = new AicsAssessmentFundSource();
                $txn->fund_source_id = $request->fund_source_id;
                $txn->movement = $request->movement;
                $txn->amount = $request->amount;
                $txn->remarks = $request->remarks;
                $txn->save();
                $t = AicsAssessmentFundSource::find($txn->id);



                /* MAIN RECORD */
                if ($request->movement > 0) {
                    $transaction_1 = $fs->journal->creditDollars($request->amount);
                    $transaction_1->referencesObject($t);
                } else {

                    $transaction_1 = $fs->journal->debitDollars($request->amount);
                    $transaction_1->referencesObject($t);
                }
                $balance = $fs->journal->getCurrentBalance();

                DB::commit();
                return ["message" => "saved"];
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
