<?php

namespace App\Http\Requests;

use App\Models\AicsRequrement;
use App\Models\AicsType;
use App\Rules\ValidString;
use Illuminate\Foundation\Http\FormRequest;

class AicsAssistanceCreateRequest extends FormRequest
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
            'aics_type_id' => ['required'],
            'office_id' => ['required'],
            'civil_status' => ['required'],
            'documents.*' => 'file|max:20480|mimetypes:application/pdf,image/jpeg,image/png'
        ];
    }

    public function withValidator($validator)
    {
      $validator->after(function ($validator) {
            $this->validateDocuments($validator);
        });
    }

    public function validateDocuments($validator)
    {   
        $aics_type_id = request('assistance.aics_type_id');
        $requirements = AicsRequrement::where('aics_type_id', $aics_type_id)
            ->where('is_required', 1)
            ->where('is_required',"=","1")
            ->get();
        $files = request('assistance.documents');
        
        foreach ($requirements as $key => $requirement) {
            if(!isset($files[$key])){
                $validator->errors()->add("documents_".$key, $requirement->name." is required.");
            }
        }

    }
}
