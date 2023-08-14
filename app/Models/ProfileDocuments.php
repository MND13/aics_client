<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProfileDocuments extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_directory',
        'name', 
        'user_id',
    ];

    protected $table = 'profile_documents';


    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
        self::updating(function($model) {

        });
    }

    public function user()
    {
        return $this->belongsTo(User::class,"user_id");
    }


}
