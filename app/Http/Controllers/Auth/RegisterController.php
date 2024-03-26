<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Psgc;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\AicsDocument;
use App\Models\ProfileDocuments;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\OtpController;
use Image;
use App\Rules\ValidCellphoneNumber;
use App\Rules\AllowedStringName;



class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use HasRoles;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /*protected function redirectTo()
    {
        //You would need to modify this according to your needs, this is just an example.
        if (Auth::user()->hasRole('admin')) {
            return route("admin.home");
        }

        return route("user.home");
    }*/

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {


        if (!isset($data['middle_name'])) {
            $data['middle_name'] = NULL;
        }
        $middle_name = isset($data['middle_name']) && $data['middle_name'] != "NMN" ?  strtoupper($data['middle_name']) : NULL;
        $first_name = mb_strtoupper(trim($data['first_name'] ?? null));
        $middle_name = mb_strtoupper(trim($middle_name));
        $last_name = mb_strtoupper(trim($data['last_name'] ?? null));
        $ext_name = mb_strtoupper(trim($data['ext_name'] ?? null));
        $data["full_name"] = trim($first_name . " " . $middle_name . " " . $last_name . " " . $ext_name);

        $fields = [
            'first_name' => ['bail', 'required', 'string', 'max:255', new AllowedStringName($data['first_name']),  'regex:/^[^<>]*$/'],
            'middle_name' => ['sometimes', 'required', 'string', 'max:255', 'min:2', 'regex:/^[^<>]*$/'],
            'last_name' => ['bail', 'required', 'string', 'max:255', new AllowedStringName($data['last_name']),  'regex:/^[^<>]*$/'],
            'ext_name' => ['sometimes', 'string', 'max:255','regex:/^[^<>]*$/'],
            'birth_date' => ['bail', 'required', 'date', 'before:18 years ago','regex:/^[^<>]*$/'],
            'full_name' => ['unique:users', new AllowedStringName($data['full_name']), 'regex:/^[^<>]*$/'],
            'gender' => ['required'],
            'psgc_id' => ['required', 'exists:psgcs,id'],
            'mobile_number' => ['required', 'numeric', 'digits:11', 'unique:users', new ValidCellphoneNumber($data['mobile_number']), 'regex:/^[^<>]*$/'],
            'street_number' => ['required', 'string', 'max:255', 'regex:/^[^<>]*$/'],
            'valid_id' => 'required|file|mimes:jpeg,jpg,png',
            'client_photo' => 'required|file|mimes:jpeg,jpg,png',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users','regex:/^[^<>]*$/'],
            'password' => ['required','confirmed','min:12','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'],
           
            # 'g-recaptcha-response' =>  'required|captcha',


        ];

        if (config('app.env') === 'production') {
            $fields['g-recaptcha-response'] = ['required', 'captcha'];
        }


        $validator = Validator::make($data,  $fields);

        $validator->after(function ($validator) use ($data, $first_name, $middle_name, $last_name, $ext_name) {
            if (isset($data['birth_date'])) {
                $user_check = User::where("first_name", "=", $first_name)
                    ->where("last_name", "=", $last_name)
                    ->where("birth_date", "=", $data['birth_date']);

                if ($middle_name) {
                    $user_check->where("middle_name", "=", $middle_name);
                }

                if ($user_check->first()) {
                    $validator->errors()->add("full_name", "User already exists.");
                }
            }
        });



        return  $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        try {
            //code...


            $usernametemp = substr($data['first_name'], 0, 1) .  substr($data['middle_name'], 0, 1) . $data['last_name'];
            $username = $this->generateUserName($usernametemp);
            $middle_name = isset($data['middle_name']) && $data['middle_name'] != "NMN" ?  strtoupper($data['middle_name']) : NULL;

            $first_name = mb_strtoupper(trim($data['first_name'] ?? null));
            $middle_name = mb_strtoupper(trim($middle_name));
            $last_name = mb_strtoupper(trim($data['last_name'] ?? null));
            $ext_name = mb_strtoupper(trim($data['ext_name'] ?? null));

            $fields = [
                'username' => strtoupper($username),
                'first_name' => $first_name,
                'middle_name' => $middle_name,
                'last_name' =>  $last_name,
                'ext_name' => $ext_name,
                'birth_date' => $data['birth_date'],
                'psgc_id' => $data['psgc_id'],
                'gender' => $data['gender'],
                'mobile_number' => $data['mobile_number'],
                'email' => $data['email'],
                'password' => $data["password"],
                //'password' => Str::upper(Str::slug($last_name)) .  date('md', strtotime($data['birth_date'])),
                'street_number' =>  mb_strtoupper(trim($data['street_number'] ?? null)),
                'meta_full_name' => metaphone($first_name) . metaphone($middle_name) . metaphone($last_name),
                'full_name' => trim($first_name . " " . $middle_name . " " . $last_name),

            ];

            if (config('app.env') != 'production') {
                $fields["mobile_verified"] = 1;
            }


            $user =  User::create($fields);
            $user->assignRole('user');

            if ($user->id) {
                #if (config('app.env') == 'production') {

                $year = date("Y");
                $month = date("m");

                $files = request('valid_id');
                $filename = $files->hashName();

                $img = Image::make($files->getRealPath())->resize(150, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $path_OG = "public/uploads/$year/$month/" . $user->uuid . "/" . $filename;
                $path = Storage::disk('s3')->put($path_OG,  $img->stream()->__toString());
                $img->destroy();
                $url = Storage::url($path_OG);
                $doc = new ProfileDocuments([
                    'file_directory' => $url,
                    'user_id' => $user->id,
                    'name' => "valid_id",
                ]);
                $doc->save();

                $files = request('client_photo');
                $filename = $files->hashName();

                $img = Image::make($files->getRealPath())->resize(150, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $path_OG = "public/uploads/$year/$month/" . $user->uuid . "/" . $filename;
                $path = Storage::disk('s3')->put($path_OG,  $img->stream()->__toString());
                $img->destroy();
                $url = Storage::url($path_OG);
                $doc = new ProfileDocuments([
                    'file_directory' => $url,
                    'user_id' => $user->id,
                    'name' => "client_photo",
                ]);
                $doc->save();

                $msg = "Salamat sa pag rehistro sa DSWD Davao Region! Kani ang detalye sa imong account.  

USERNAME: " . strtoupper($username)  . " 

ANG PAG PROSESO AY LIBRE.";
                $response = Http::get('http://34.80.139.96/api/v2/SendSMS?ApiKey=LWtHZKzgbIh1sNQUPInRyqDFsj8W0K+8YCeSIdN08zA=&ClientId=3b3f49c9-b8e2-4558-9ed2-d618d7743fd5&SenderId=DSWD11AICS&Message=' . $msg . '&MobileNumbers=63' . substr($data['mobile_number'], 1));
                $res = $response->collect();
                #}
            }

            return $user;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function showRegistrationForm()
    {
        $provinces = Psgc::getProvinces("region_psgc", "110000000");

        return view(
            'auth.register',
            [
                "provinces" => $provinces
            ]
        );
    }

    public function generateUserName($name)
    {
        $username = Str::random(8);
        if (User::where('username', '=', $username)->exists()) {
            $uniqueUserName = Str::random(8);
            $username = $this->generateUserName($uniqueUserName);
        }
        return $username;
    }
}
