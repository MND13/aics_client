<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Psgc;

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

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

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
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'ext_name' => ['sometimes', 'required', 'string', 'max:255'],
            'birth_date' => ['date'],
            'psgc_id' => ['exists:psgcs,id'],
            'mobile_number' => ['required', 'numeric', 'digits:11'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users'],
            'street_number' => ['sometimes', 'required', 'string', 'max:255'],
            # 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'middle_name' => isset($data['middle_name']) ?  $data['middle_name'] : NULL,
            'last_name' => $data['last_name'],
            'ext_name' => isset($data['ext_name']) ? $data['ext_name'] : NULL,
            'birth_date' => $data['birth_date'],
            'psgc_id' => $data['psgc_id'],
            'gender' => $data['gender'],
            'mobile_number' => $data['mobile_number'],
            'email' => isset($data['email']) ?  $data['email'] : NULL,
            'password' => "DSWD12345",
            'street_number' => isset($data['street_number']) ? $data['street_number'] : NULL,
           
          
        ]);
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
}
