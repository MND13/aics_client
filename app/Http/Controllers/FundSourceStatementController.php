<?php

namespace App\Http\Controllers;

use App\Models\FundSourceStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FundSourceStatementController extends Controller
{
    public function create()
    {
        $balances = FundSourceStatement::balance_query();
    
        $data = collect($balances)->map(function ($item) {
            return get_object_vars($item);
        });

        FundSourceStatement::insert($data->toArray());
    }

    public function index()
    {
      $balances = FundSourceStatement::balance_query();
      return $balances;
    }
}
