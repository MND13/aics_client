<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FundSourceStatement extends Model
{
    use HasFactory;

    public static function balance_query()
    {
       return DB::select("
        select 
          fund_source_id, 
          ifnull(total_credit,0) as total_credit, 
          ifnull(total_debit,0) as total_debit, 
          (ifnull(balance_gahapon,0) + ifnull(total_credit,0) - ifnull(total_debit,0)) as closing_balance,
          date

        from(
        select
          sum(case when movement = 1 then amount end) as total_credit,
          sum(case when movement = -1 then amount end) as total_debit,
          ( SELECT closing_balance 
            from fund_source_statements 
            where fund_source_id = aics_assessments_fund_sources.fund_source_id 
            and date = date(now())-1
            )  as balance_gahapon,
          fund_source_id,         
          date(created_at) as date
        from aics_assessments_fund_sources 
            WHERE date(created_at) = date(now())
        GROUP BY fund_source_id, date(created_at)) a;");
    }
}
