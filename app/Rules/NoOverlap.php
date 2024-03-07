<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class NoOverlap implements Rule
{
    protected $min_range;

    public function __construct($min_range)
    {
        $this->min_range = $min_range;
    }

    public function passes($attribute, $max_range)
    {
        // Check if max is greater than existing data range
        $overlap = DB::table('signatories_settings')
            ->where('min_range', '<', $max_range)
            ->where('max_range', '>', $this->min_range)
            ->exists();

        return !$overlap;
    }

    public function message()
    {
        return 'The range overlaps with existing data.';
    }
}