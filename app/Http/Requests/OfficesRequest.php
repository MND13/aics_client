<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidCellphoneNumber;

class OfficesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && !Auth::user()->hasRole('Super-Admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'address' => ['required'],
            'contact_person' => ['required'],
            'contact_no' => ['required','numeric', 'digits:11', new ValidCellphoneNumber($this->contact_no)],
        ];
    }
}
