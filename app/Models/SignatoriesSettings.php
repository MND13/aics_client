<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
#use Illuminate\Database\Eloquent\SoftDeletes;


class SignatoriesSettings extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
  #  use SoftDeletes;
}
