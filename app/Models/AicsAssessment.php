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

    public function fund_sources()
    {
        //return $this->belongsTo(FundSource::class);
        return $this->hasMany(AicsAssessmentFundSource::class, "assessment_id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function interviewed_by()
    {
        return $this->belongsTo(User::class, "interviewed_by_id");
    }

    public function provider()
    {
        return $this->belongsTo(AicsProviders::class, "provider_id");
    }

    public function signatory()
    {
        return $this->belongsTo(Signatories::class, "signatory_id");
    }
}
