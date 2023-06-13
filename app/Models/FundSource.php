<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundSource extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function transactions()
    {
        $this->hasMany(AicsAssessmentFundSource::class);
    }

    public function balance()
    {
        
    }
   
}
