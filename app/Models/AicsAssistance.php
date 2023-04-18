<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
class AicsAssistance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'aics_type_id',
        'status',
        'status_date',        
        'age',
        'occupation',
        'monthly_salary'
    ];

    
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->status = "Pending";
            $model->status_date = Carbon::now();
            $model->user_id = Auth::id();
            $model->age =  Auth::user()->age();
        });
        self::updating(function($model) {

        });
    }

    public function aics_client()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    /*public function aics_beneficiary()
    {
        return $this->belongsTo(AicsBeneficiary::class);
    }*/

    public function aics_type()
    {
        return $this->belongsTo(AicsType::class);
    }

    public function aics_documents()
    {
        return $this->hasMany(AicsDocument::class);
    }

    
}
