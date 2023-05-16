<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:255',
            'middle_name' => 'sometimes|required|string|max:255|min:2',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'date',
            'psgc_id' => 'exists:psgcs,id',
            'mobile_number' => 'required|numeric|digits:11',
           // 'username' => 'required|unique:users,username,',
            //'username' => 'required|unique:users,username,',
           
            'email' => 'required|email|max:255',
            'role' => 'required',
            'password' => 'sometimes|required|confirmed|max:255|min:8',
        ];

/*
        'first_name' => 'required|string|max:255',
        'middle_name' => 'sometimes|required|string|max:255|min:2',
        'last_name' => 'required|string|max:255',
        'ext_name' => 'sometimes|required|string|max:255',
        'birth_date' => 'date',
        'psgc_id' => 'exists:psgcs,id',
        'mobile_number' => 'required|numeric|digits:11',
        'email' => 'sometimes|required|string|email|max:255|unique:users',
        'street_number' => 'sometimes|required|string|max:255',
        'password' => 'required|string|min:8|confirmed',*/
        
    }
}
