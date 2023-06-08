<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AicsAssessmentFundSource extends Model
{
    use HasFactory;
    protected $table = "aics_assessments_fund_sources";

    public function fund_source()
    { 
        return $this->belongsTo(FundSource::class);
    }

    public function assessment()
    {
        return $this->belongsTo(AicsAssessment::class);
    }


}
