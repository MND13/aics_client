<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class DirtyList extends Model
{
    use HasFactory;
    protected $guarded = ['id']; 


}