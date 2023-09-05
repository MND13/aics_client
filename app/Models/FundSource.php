<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Scottlaurent\Accounting\ModelTraits\AccountingJournal;


class FundSource extends Model
{
    use HasFactory, AccountingJournal;
    protected $guarded = ["id"];

    public static function boot()
    {
        parent::boot();
        self::created(function($model) {
          $model->initJournal() ;
        
        });
        
    }


    public function transactions()
    {
        $this->hasMany(AicsAssessmentFundSource::class);
    }

    public function balance()
    {
       
       return $this->journal()->getCurrentBalanceInDollars() ;
    }
   
}
