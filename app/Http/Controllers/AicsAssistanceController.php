<?php

namespace App\Http\Controllers;

use App\Http\Requests\AicsAssistanceCreateRequest;
use App\Models\AicsAssistance;
use App\Models\AicsBeneficiary;
use App\Models\AicsDocument;
use App\Models\AicsRequrement;
use App\Models\AicsType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AcisCrimsExport;
use App\Models\User;
use Image;

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
                        
                        /*$path = Storage::disk('s3')->put("public/uploads/$year/$month/" . $aics_assistance->uuid, $files[$requirement->id]);
                        $url = Storage::url($path);

                        $documents[] = new AicsDocument([
                            'file_directory' => $url,
                            'aics_requrement_id' => $requirement->id,
                        ]);*/


                        $filesx = $files[$requirement->id]; 
            
                        $img = Image::make($filesx->getRealPath())->resize(500, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $path_OG = "public/uploads/$year/$month/" . $aics_assistance->uuid  ;
                        $path = Storage::disk('s3')->put($path_OG,  $img->stream()->__toString());
                        $url = Storage::url($path_OG);

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
                "verified_by:id,full_name,first_name,middle_name,last_name",
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


                    if ($asst->aics_client->profile_docs) {
                        foreach ($asst->aics_client->profile_docs as $key => $value) {
                            $value->file_directory =  User::s3Url($value->file_directory);
                        }
                    }

                    if ($asst->verified_by) {
                        $asst->en = substr($asst->verified_by["first_name"], 0, 1)
                            . substr($asst->verified_by["middle_name"], 0, 1)
                            . substr($asst->verified_by["last_name"], 0, 1);
                        $asst->en  = strtolower($asst->en);                      
                    }

                    if (isset($asst->assessment->interviewed_by)) {                      
                        $asst->sw = substr($asst->assessment->interviewed_by->first_name, 0, 1)
                            . substr($asst->assessment->interviewed_by->middle_name, 0, 1)
                            . substr($asst->assessment->interviewed_by->last_name, 0, 1);
                        $asst->sw  = strtolower($asst->sw);
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

                    if ($asst->aics_client->profile_docs) {
                        foreach ($asst->aics_client->profile_docs as $key => $value) {
                            $value->file_directory =  User::s3Url($value->file_directory);
                        }
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
                ->whereRelation("office", "office_id", "=", Auth::user()->office_id)
                ->orderByRaw("FIELD(status , 'Pending', 'Verified', 'Serving', 'Served','Rejected') ASC")
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

                ->orderBy("created_at",  "desc")
                ->orderByRaw("FIELD(status , 'Pending', 'Verified', 'Serving', 'Served','Rejected') ASC")

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

    public function export(Request $request)
    {


        $filename = "";
        $filename = "aics-app-client-" . \Str::slug(Carbon::now());
        $export_file_name = "$filename.xlsx";

        Excel::store(new AcisCrimsExport($request->date_option, $request->date_range), "public/$export_file_name", 'local');
        return [
            "file" => url(Storage::url("public/$export_file_name")),
        ];
    }

    public function view_attachment(Request $request)
    {
        return  User::s3Url($request->file_directory);
    }
}
