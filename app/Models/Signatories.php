<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signatories extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->setInitials();
        });

        self::updating(function ($model) {
            $model->setInitials();
        });
    }

    public function setInitials()
    {
        $nameParts = explode(' ', $this->name);
        $initials = '';
        foreach ($nameParts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
        $this->initials = $initials;
    }
}
