<?php

namespace App\Http\Controllers;

use App\Http\Requests\AicsAssistanceCreateRequest;
use App\Http\Requests\AicsBeneficiaryCreateRequest;
use App\Http\Requests\AicsClientCreateRequest;
use App\Models\AicsAssistance;
use App\Models\AicsBeneficiary;
use App\Models\AicsClient;
use App\Models\AicsDocument;
use App\Models\AicsRequrement;
use App\Models\CertOfEligibility;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;




class AicsAssistanceController extends Controller
{

    public function store(Request $request)
    {
        DB::beginTransaction();
        $user = Auth::user();
        $year = date("Y");
        $month = date("m");

        if (Auth::check() &&   Auth::user()->hasRole('admin'))
        {
            
        }


        if (Auth::check() &&   Auth::user()->hasRole('user')) {
            try {
                $form_data = $request->all();
                $errors = ["assistance" => []];

                $assistance_request_rules = (new AicsAssistanceCreateRequest())->rules();
                $assistance_validator =  Validator::make($form_data['assistance'], $assistance_request_rules);
                if ($assistance_validator->fails()) {
                    $errors['assistance'] = $assistance_validator->errors();
                }

                if (
                    $errors['assistance'] != array()
                ) {
                    return response(['errors' => $errors], 422);
                }


                $aics_assistance = AicsAssistance::create($form_data["assistance"]);

                //Uploaded Documents
                $documents = [];
                $aics_type_id = request('assistance.aics_type_id');
                $requirements = AicsRequrement::where('aics_type_id', $aics_type_id)->where('is_required', 1)->get();
               
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
                return ["message"=> "Saved"];
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
        if (Auth::check() &&  Auth::user()->hasRole(['admin','encoder','social-worker'])) {
            
            #RESTRICTED TO OFFICE ID

            return AicsAssistance::with([
                
                 "aics_type:id,name",
                 "aics_documents",
                 "aics_documents.requirement:id,name",
                 "office:id,name,address",
                 "aics_client:id,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number",
                 "aics_client.psgc:id,region_name,province_name,city_name,brgy_name",
                 "assessment",
                
               
                 ])
                 ->where("uuid","=",$uuid)
                 ->whereRelation("office","office_id","=",Auth::user()->office_id )->firstOrFail();
               
         }

         if (Auth::check() &&  Auth::user()->hasRole(['super-admin'])) {

            return AicsAssistance::with([
                
                 "aics_type:id,name",
                 "aics_documents",
                 "aics_documents.requirement:id,name",
                 "office:id,name,address",
                 "aics_client:id,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number",
                 "aics_client.psgc:id,region_name,province_name,city_name,brgy_name",
                 "assessment",
                
               
                 ])
                 ->where("uuid","=",$uuid)
                 ->firstOrFail();
              
         }
 
 
         if (Auth::check() &&   Auth::user()->hasRole('user')) 
         {
             #RESTRICTED TO OWN SUBMISSIONS
 
             return AicsAssistance::with(
                
                 "aics_type:id,name",
                 "aics_documents",
                 "aics_documents.requirement:id,name",
                 "office:id,name,address",
                 "aics_client",
                 "assessment"
     
     
             )
             ->where("uuid","=",$uuid)
             ->where("user_id","=",Auth::id() )->first();
 
         }   


      }

   

    public function index()
    {   
        if (Auth::check() &&  Auth::user()->hasRole(['admin','encoder','social-worker'])) {

           // var_dump(Auth::user());

           return AicsAssistance::with([
               
                "aics_type:id,name",
                "aics_documents",
                "aics_documents.requirement:id,name",
                "office:id,name,address",
                "aics_client:id,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number,mobile_number",
                "aics_client.psgc:id,region_name,province_name,city_name,brgy_name",
                "assessment"
              
                ])
                //->whereRelation("office","user_id","=",Auth::id() )
                ->whereRelation("office","office_id","=",Auth::user()->office_id )
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
    
    
            )->where("user_id","=",Auth::id() )->get();

        }
        
        if (Auth::check() &&  Auth::user()->hasRole(['super-admin'])) {

            // var_dump(Auth::user());
 
            return AicsAssistance::with([
                
                 "aics_type:id,name",
                 "aics_documents",
                 "aics_documents.requirement:id,name",
                 "office:id,name,address",
                 "aics_client:id,first_name,last_name,middle_name,ext_name,psgc_id,mobile_number,birth_date,gender,street_number,mobile_number",
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

        try {
            $a = AicsAssistance::where("uuid", "=", $request->uuid)->first();

            if ($a) {

                $a->status = $request->status;
                
                if($request->remarks) 
                {$a->remarks = $request->remarks;}

                if($request->schedule) 
                {$a->schedule = $request->schedule;}
                

                
                $a->status_date = Carbon::now();
                $a->save();
                return ["message" => "saved"];
            } else {
                return ["message" => "Assistance Request Not Found"];
            }
        } catch (\Throwable $th) {
            return ["message" => $th];
        }
    }

   
}
