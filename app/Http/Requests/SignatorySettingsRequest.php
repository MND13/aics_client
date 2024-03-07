<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\NoOverlap;

class SignatorySettingsRequest extends FormRequest
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
            'min_range' => ['required','numeric'],
            'max_range' => ['required','numeric', 'gt:min_range', new NoOverlap($this->min_range)],
            'signatories' => ['required'],
           
        ];
    }
}
