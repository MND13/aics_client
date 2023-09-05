<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AicsBeneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
       # 'aics_client_id',
        'last_name',
        'first_name',
        'middle_name',
        'ext_name',
        'street_number',
        'psgc_id',
        'mobile_number',
        'birth_date',
        'age',
        'gender',
        'occupation',
        'monthly_salary',  
        'civil_status'      
    ];

    /*public function aics_client()
    {
        return $this->belongsTo(AicsClient::class);
    }*/

    public function aics_assistance()
    {
        return $this->hasOne(AicsAssistance::class);
    }

    public function psgc()
    {
        return $this->belongsTo(Psgc::class);
    }

}
