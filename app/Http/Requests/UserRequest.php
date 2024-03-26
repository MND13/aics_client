<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $role = $this->input('role');

        if ($role == "user") {
            $rules = [
                'first_name' => 'required|string|max:255',
                'middle_name' => 'max:255',
                'last_name' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'psgc_id' => 'required|exists:psgcs,id',
                'mobile_number' => ['required', 'numeric', 'digits:11', Rule::unique('users')->ignore(request('id'))],
                'gender' => 'required|string',
                'username' => ['required', 'max:255', Rule::unique('users')->ignore(request('id'))],
                'role' => 'sometimes|required',
                'password' => 'sometimes|required|confirmed|min:12|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'mobile_number' => ['required', 'numeric', 'digits:11', Rule::unique('users')->ignore(request('id'))],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(request('id'))],
          
            ];
        } else {
            $rules = [
                'first_name' => 'required|string|max:255',
                'middle_name' => 'max:255',
                'last_name' => 'required|string|max:255',
                'office_id' => 'sometimes|required|exists:offices,id',
                'username' => ['required', 'max:255', Rule::unique('users')->ignore(request('id'))],
                'role' => 'sometimes|required',
                'password' => 'sometimes|required|confirmed|min:12|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
               
            ];
        }

        return  $rules;
    }
}
