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

    public function fund_source()
    {
        return $this->belongsTo(FundSource::class);
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
        return $this->belongsTo(User::class,"interviewed_by_id");
    }

   


}
