<?php

namespace App\Http\Controllers;

use App\Http\Requests\AicsAssistanceCreateRequest;
use App\Http\Requests\AicsBeneficiaryCreateRequest;
use App\Http\Requests\AicsClientCreateRequest;
use App\Models\AicsAssessmentFundSource;
use App\Models\AicsAssistance;
use App\Models\AicsBeneficiary;
use App\Models\AicsClient;
use App\Models\AicsDocument;
use App\Models\AicsRequrement;
use App\Models\AicsType;
use App\Models\CertOfEligibility;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class AicsAssistanceController extends Controller
{

    public function store(Request $request)
    {

        DB::beginTransaction();
        $user = Auth::user();
        $year = date("Y");
        $month = date("m");

        if (Auth::check() &&   Auth::user()->hasRole('admin')) {
        }


        if (Auth::check() &&   Auth::user()->hasRole('user')) {
            try {
                $form_data = $request->all();
                $errors = ["assistance" => []];

                $assistance_request_rules = (new AicsAssistanceCreateRequest())->rules();
                $assistance_validator =  Validator::make($form_data['assistance'], $assistance_request_rules);
                $assistance_validator->after(function ($validator) {
                    (new AicsAssistanceCreateRequest())->validateDocuments($validator);
                });

                if ($assistance_validator->fails()) {
                    $errors['assistance'] = $assistance_validator->errors();
                }

                if (
                    $errors['assistance'] != array()
                ) {
                    return response(['errors' => $errors], 422);
                }



                if (isset($form_data['beneficiary'])) {
                    $beneficiary = AicsBeneficiary::create($form_data['beneficiary']);
                }

                #$aics_assistance = AicsAssistance::create($form_data["assistance"]);
                $aics_assistance = new AicsAssistance;
                $aics_assistance->fill($form_data["assistance"]);



                if (isset($form_data['beneficiary'])) {
                    $aics_assistance->aics_beneficiary_id  =  $beneficiary->id;
                }

                #dd($aics_assistance->save());

                $aics_assistance->save();



                //Uploaded Documents
                $documents = [];
                $aics_type_id = request('assistance.aics_type_id');
                $requirements = AicsRequrement::where('aics_type_id', $aics_type_id)->get();

                $files = request('assistance.documents');


                foreach ($requirements as $key => $requirement) {

                    if (isset($files[$requirement->id])) {
                        $path = Storage::disk('local')->put("public/uploads/$year/$month/" . $aics_assistance->uuid, $files[$requirement->id]);
                        $url = Storage::url($path);
                        $documents[] = new AicsDocument([
                            'file_directory' => $url,
                            'aics_requrement_id' => $requirement->id,
                        ]);
                    }
                }

                $aics_assistance->aics_documents()->saveMany($documents);




                DB::commit();
                return ["message" => "Saved"];
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }

        /*try {
            $form_data = $request->all();
            $errors = [];
            $year = date("Y");
            $nmonth = date("m");

            $errors = [
                "client" => [],
                "beneficiary" => [],
                "assistance" => [],
            ];

            //Client Validation
            $client_request_rules = (new AicsClientCreateRequest())->rules();
            $client_validator =  Validator::make($form_data['client'], $client_request_rules);
            if ($client_validator->fails()) {
                $errors['client'] = $client_validator->errors();
            }

            //Beneficiary Validation
            $beneficiary_request_rules = (new AicsBeneficiaryCreateRequest())->rules();
            $beneficiary_validator =  Validator::make($form_data['beneficiary'], $beneficiary_request_rules);
            if ($beneficiary_validator->fails()) {
                $errors['beneficiary'] = $beneficiary_validator->errors();
            }

            //Assistance Validation
            $assistance_request_rules = (new AicsAssistanceCreateRequest())->rules();
            $assistance_validator =  Validator::make($form_data['assistance'], $assistance_request_rules);
           // (new AicsAssistanceCreateRequest())->withValidator($assistance_validator);
            if ($assistance_validator->fails()) {
                $errors['assistance'] = $assistance_validator->errors();
            }

            if (
                $errors['client'] != array() &&
                $errors['beneficiary'] != array() &&
                $errors['assistance'] != array()
            ) {
                return response(['errors' => $errors], 422);
            }

            $aics_assistance = AicsAssistance::create($form_data['assistance']);
            $client = AicsClient::create($form_data['client']);
            $beneficiary = AicsBeneficiary::create($form_data['beneficiary']);

            $aics_assistance->aics_client()->associate($client);
            $aics_assistance->aics_beneficiary()->associate($beneficiary);
            $aics_assistance->aics_beneficiary->aics_client()->associate($client);


            $aics_assistance->aics_beneficiary->save();
            $aics_assistance->save();

            $start_year = Carbon::parse("$year-01-01");
            $end_year = Carbon::parse("$year-01-01")->addYear()->subSecond();

            $count_users = User::whereBetween('created_at', [$start_year, $end_year])->whereNotNull('aics_client_id')->count();
            $user = $aics_assistance->aics_client->user()->create([
                'name' => $client->first_name . " " . $client->middle_name . " " . $client->last_name . " " . $client->ext_name,
                'email' => $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($count_users, 4, "0", STR_PAD_LEFT),
                'password' => $client->mobile_number,
            ]);

            //Uploaded Documents
            $documents = [];
            $aics_type_id = request('assistance.aics_type_id');
            $requirements = AicsRequrement::where('aics_type_id', $aics_type_id)->where('is_required', 1)->get();
            $files = request('assistance.documents');

            foreach ($requirements as $key => $requirement) {
                if (isset($files[$key])) {
                    $path = Storage::disk('local')->put("public/uploads/$year/$month/" . $aics_assistance->uuid, $files[$key]);
                    $url = Storage::url($path);
                    $documents[] = new AicsDocument([
                        'file_directory' => $url,
                        'aics_requrement_id' => $requirement->id,
                    ]);
                }
            }
            $aics_assistance->aics_documents()->saveMany($documents);
            DB::commit();
            $aics_assistance->user_account = $user;
            return $aics_assistance;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }*/
    }

    public function show(Request $request, $uuid)
    {

        if (Auth::check() &&  Auth::user()->hasRole(['admin', 'encoder', 'social-worker'])) {

            #RESTRICTED TO OFFICE ID 
            # Super Admin not bound to office id

            $assisstance =  AicsAssistance::with([
                "aics_type:id,name",
                "aics_documents",
                "aics_documents.requirement:id,name",
                "office:id,name,address",
                "aics_client:id,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number",
                "aics_client.psgc:id,region_name,province_name,city_name,brgy_name",
                #"aics_client.profile_docs:id,user_id,name,file_directory",
                #"assessment.fund_sources:id,assessment_id,fund_source_id,amount,remarks", #FS TXN
                "assessment.fund_sources.fund_source:id,name", # FS 
                "verified_by:id,full_name",
                "aics_beneficiary",
                "aics_beneficiary.psgc:id,region_name,province_name,city_name,brgy_name",
            ])
                ->where("uuid", "=", $uuid)
                ->with("assessment.fund_sources", function ($q) {
                    $q->where("remarks", "!=", "CANCELLED")
                        ->where("remarks", "!=", "REVERSAL")
                        ->orWhereNull("remarks");

                    return $q;
                })
                ->with("aics_client.profile_docs", function ($q) {

                    $q->select("id", "user_id", "name", "file_directory")
                        ->orderBy("id", "desc");
                    return $q;
                })
                ->whereRelation("office", "office_id", "=", Auth::user()->office_id)
                ->get()->transform(function ($asst) {

                    if (isset($asst->assessment->fund_sources)) {
                        $selected_fund_source = array();
                        $i = 0;
                        $r = array();
                        foreach ($asst->assessment->fund_sources as $key => $value) {

                            if (!in_array($value["remarks"], ["CANCELLED", "REVERSAL", "REVERSED"])) {

                                $selected_fund_source[$i] = (object) [];
                                $selected_fund_source[$i]->id = $value["fund_source"]["id"];
                                $selected_fund_source[$i]->name = $value["fund_source"]["name"];
                                $selected_fund_source[$i]->amount = $value["amount"];
                                $selected_fund_source[$i]->txn_id = $value["id"];
                                $selected_fund_source[$i]->remarks = $value["remarks"];
                                $selected_fund_source[$i]->key = $key;
                                $r[$i] = $value["remarks"];
                                $i++;
                            }
                        }

                        $asst->selected_fs = $selected_fund_source;
                        $asst->aa = $r;
                    }
                    return $asst;
                });



            return $assisstance[0];
        }

        if (Auth::check() &&  Auth::user()->hasRole(['super-admin'])) {

            $assisstance =  AicsAssistance::with([

                "aics_type:id,name",
                "aics_documents",
                "aics_documents.requirement:id,name",
                "office:id,name,address",
                "aics_client:id,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number",
                "aics_client.psgc:id,region_name,province_name,city_name,brgy_name",
                #"aics_client.profile_docs:id,user_id,name,file_directory",
                #"assessment.fund_sources:id,assessment_id,fund_source_id,amount,remarks", #FS TXN
                "assessment.fund_sources.fund_source:id,name", # FS 
                "verified_by:id,full_name",
                "aics_beneficiary",
                "aics_beneficiary.psgc:id,region_name,province_name,city_name,brgy_name",
            ])->where("uuid", "=", $uuid)
                ->with("assessment.fund_sources", function ($q) {
                    $q->where("remarks", "!=", "CANCELLED")
                        ->where("remarks", "!=", "REVERSAL")
                        ->orWhereNull("remarks");

                    return $q;
                })
                ->with("aics_client.profile_docs", function ($q) {

                    $q->select("id", "user_id", "name", "file_directory")
                        ->orderBy("id", "desc");
                    return $q;
                })

                ->get()->transform(function ($asst) {

                    if (isset($asst->assessment->fund_sources)) {
                        $selected_fund_source = array();
                        $i = 0;
                        $r = array();
                        foreach ($asst->assessment->fund_sources as $key => $value) {

                            if (!in_array($value["remarks"], ["CANCELLED", "REVERSAL", "REVERSED"])) {

                                $selected_fund_source[$i] = (object) [];
                                $selected_fund_source[$i]->id = $value["fund_source"]["id"];
                                $selected_fund_source[$i]->name = $value["fund_source"]["name"];
                                $selected_fund_source[$i]->amount = $value["amount"];
                                $selected_fund_source[$i]->txn_id = $value["id"];
                                $selected_fund_source[$i]->remarks = $value["remarks"];
                                $selected_fund_source[$i]->key = $key;
                                $r[$i] = $value["remarks"];
                                $i++;
                            }
                        }

                        $asst->selected_fs = $selected_fund_source;
                        $asst->aa = $r;
                    }
                    return $asst;
                });



            return $assisstance[0];
        }


        if (Auth::check() &&   Auth::user()->hasRole('user')) {
            #RESTRICTED TO OWN SUBMISSIONS

            return AicsAssistance::with(

                "aics_type:id,name",
                "aics_documents",
                "aics_documents.requirement:id,name",
                "office:id,name,address",
                "aics_client",
                "assessment",
                "aics_beneficiary"


            )
                ->where("uuid", "=", $uuid)
                ->where("user_id", "=", Auth::id())->first();
        }
    }



    public function index()
    {
        if (Auth::check() &&  Auth::user()->hasRole(['admin', 'encoder', 'social-worker'])) {

            // var_dump(Auth::user());

            return AicsAssistance::with([

                "aics_type:id,name",
                "aics_documents",
                "aics_documents.requirement:id,name",
                "office:id,name,address",
                "aics_client:id,full_name,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number,mobile_number",
                "aics_client.psgc:id,region_name,province_name,city_name,brgy_name",
                "assessment",


            ])
                //->whereRelation("office","user_id","=",Auth::id() )
                ->whereRelation("office", "office_id", "=", Auth::user()->office_id)
                ->orderBy("created_at",  "desc")
                ->get();
        }


        if (Auth::check() &&   Auth::user()->hasRole('user')) {

            return AicsAssistance::with(

                "aics_type:id,name",
                "aics_documents",
                "aics_documents.requirement:id,name",
                "office:id,name,address",
                "aics_client",
                "assessment"


            )->orderBy("created_at",  "desc")
                ->where("user_id", "=", Auth::id())->get();
        }

        if (Auth::check() &&  Auth::user()->hasRole(['super-admin'])) {

            // var_dump(Auth::user());

            return AicsAssistance::with([

                "aics_type:id,name",
                "aics_documents",
                "aics_documents.requirement:id,name",
                "office:id,name,address",
                "aics_client:id,full_name,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number,mobile_number",
                "aics_client.psgc:id,region_name,province_name,city_name,brgy_name",
                "assessment"

            ])
                //->whereRelation("office","user_id","=",Auth::id() )
                // ->whereRelation("office","office_id","=",Auth::user()->office_id )
                ->get();
        }
    }

    public function update(request $request)
    {
        DB::beginTransaction();

        try {
            $a = AicsAssistance::where("uuid", "=", $request->uuid)->with("aics_client")->first();

            if ($a) {

                #CHANGE STATUS ONLY, SEE ASSESSMENT CONTROLLER UPDATE 
                if ($request->task == "update_status") {
                    $a->status = $request->status;

                    if ($request->remarks) {
                        $a->remarks = $request->remarks;
                    }

                    if ($request->schedule) {
                        $a->schedule = $request->schedule;
                    }
                    $a->status_date = Carbon::now();
                    $a->verified_by_id = Auth::id();

                    $a->save();
                    DB::commit();
                    if ($a->status != "Rejected" || $a->status != "Served") {
                        $sms_response = $this->sms($a);
                    }
                    return ["message" => "saved", "sms_response" => $sms_response];
                }
            } else {
                return ["message" => "Assistance Request Not Found",];
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public function sms($request)
    {

        switch ($request->status) {
            case 'Rejected':
                $msg = "";
                // $msg = "Maayong Adlaw! Kani na mensahe gikan sa DSWD Davao Region Office. Ang status ng iyong request ay " . $request->status. ": " . $request->remarks;
                break;
            case 'Served':
                $msg = "";
                break;
            default:
                $assistance_type = AicsType::where("id", "=", $request->aics_type_id)->first();

                #Ayaw usaba ang spacing kai madaot pg view sa phone;
                $msg = "Maayong Adlaw! Kani na mensahe gikan sa DSWD Davao Region Office, nadawat na namo ang imohang aplikasyon sa " . strtoupper($assistance_type->name) . ". Sa karon, gina proceso na inyohang dokyumento. Mamalihog mi na magpa-abot ug tawag gikan sa social workers sa kani na schedule: " .  date_format(date_create($request->schedule), "M. d, Y, @ h:iA")  . " Daghang Salamat!";
                if ($request->remarks && $request->remarks != "") $msg .= " Pahabol na Mensahe: " .  $request->remarks;
                break;
        }

        $response = Http::get('http://34.80.139.96/api/v2/SendSMS?ApiKey=LWtHZKzgbIh1sNQUPInRyqDFsj8W0K+8YCeSIdN08zA=&ClientId=3b3f49c9-b8e2-4558-9ed2-d618d7743fd5&SenderId=DSWD11AICS&Message=' . $msg . '&MobileNumbers=63' . substr($request->aics_client->mobile_number, 1));
        return $response->collect();
    }
}
