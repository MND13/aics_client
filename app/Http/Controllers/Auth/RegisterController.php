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

        $validator = Validator::make($data, [
            'first_name' => ['bail', 'required', 'string', 'max:255'],
            'middle_name' => ['sometimes', 'required', 'string', 'max:255', 'min:2'],
            'last_name' => ['bail', 'required', 'string', 'max:255'],
            'ext_name' => ['sometimes', 'string', 'max:255'],
            'birth_date' => ['bail', 'required', 'date', 'before:18 years ago'],
            'full_name' => ['unique:users'],
            #'full_name' => ['bail', 'required', Rule::unique('users', 'full_name')->where(function ($query) use ($data) {
            #   if(isset($data['birth_date'])) return $query->where('birth_date', '=',  $data['birth_date']);
            #})],
            'gender' => ['required'],
            'psgc_id' => ['required','exists:psgcs,id'],
            'mobile_number' => ['required', 'numeric', 'digits:11',],
            #'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users'],
            'street_number' => ['required', 'string', 'max:255'],
            #'password' => ['required', 'string', 'min:8', 'confirmed'],
            'valid_id' => 'required|file|mimes:jpeg,jpg,png,gif|max:2048',
            'client_photo' => 'required|file|mimes:jpeg,jpg,png,gif|max:2048',

        ]);

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
        $usernametemp = substr($data['first_name'], 0, 1) .  substr($data['middle_name'], 0, 1) . $data['last_name'];
        $username = $this->generateUserName($usernametemp);
        $middle_name = isset($data['middle_name']) && $data['middle_name'] != "NMN" ?  strtoupper($data['middle_name']) : NULL;

        $first_name = mb_strtoupper(trim($data['first_name'] ?? null));
        $middle_name = mb_strtoupper(trim($middle_name));
        $last_name = mb_strtoupper(trim($data['last_name'] ?? null));
        $ext_name = mb_strtoupper(trim($data['ext_name'] ?? null));

        $user =  User::create([
            'username' => strtoupper($username),
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' =>  $last_name,
            'ext_name' => $ext_name,
            'birth_date' => $data['birth_date'],
            'psgc_id' => $data['psgc_id'],
            'gender' => $data['gender'],
            'mobile_number' => $data['mobile_number'],
            'email' => isset($data['email']) ?  $data['email'] : NULL,
            'password' => Str::upper(Str::slug($last_name)) .  date('md', strtotime( $data['birth_date'])),
            'street_number' =>  mb_strtoupper(trim($data['street_number'] ?? null)),
            'meta_full_name' => metaphone($first_name) . metaphone($middle_name) . metaphone($last_name),
            'full_name' => trim($first_name . " " . $middle_name . " " . $last_name),
        ]);

        if ($user->id) {
            $year = date("Y");
            $month = date("m");

            $files = request('valid_id');
            $path = Storage::disk('local')->put("public/uploads/$year/$month/" . $user->uuid, $files);
            $url = Storage::url($path);
            $doc = new ProfileDocuments([
                'file_directory' => $url,
                'user_id' => $user->id,
                'name' => "valid_id",
            ]);
            $doc->save();

            $files = request('client_photo');
            $path = Storage::disk('local')->put("public/uploads/$year/$month/" . $user->uuid, $files);
            
            $url = Storage::url($path);
            $doc = new ProfileDocuments([
                'file_directory' => $url,
                'user_id' => $user->id,
                'name' => "client_photo",
            ]);
            $doc->save();
            $user->assignRole('user');
        }

        return $user;
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
        /* $username = Str::lower(Str::slug($name));
        if (User::where('username', '=', $username)->exists()) {
            $uniqueUserName = $username . '-' . mt_rand(0000, 9999);
            $username = $this->generateUserName($uniqueUserName);
        }
        return $username;*/

        $username = Str::random(8);
        if (User::where('username', '=', $username)->exists()) {
            $uniqueUserName = Str::random(8);
            $username = $this->generateUserName($uniqueUserName);
        }
        return $username;
    }
}
