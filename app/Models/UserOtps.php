<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtps extends Model
{
    use HasFactory;
    protected   $fillable = [
        'user_id',
        
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->otp = rand(123456, 999999);
            $model->expire_at = now()->addMinutes(15);
        });
       
    }
}
