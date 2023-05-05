<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AicsAssessment extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function assistance()
    {
        return $this->hasOne(AicsAssistance::class);
    }


}
