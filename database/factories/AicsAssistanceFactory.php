<?php

namespace Database\Factories;

use App\Models\AicsAssistance;
use App\Models\AicsType;
use App\Models\Offices;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

class AicsAssistanceFactory extends Factory
{   
    protected $model = AicsAssistance::class;
   
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' =>Auth::id(),
            'aics_type_id' =>  AicsType::with("requirements")->inRandomOrder()->first()->id,
            #'status',
            #'status_date',
            #'age',
            'occupation' =>  $this->faker->jobTitle(), 
            'monthly_salary' =>  rand(0, 20000), 
            'office_id' =>Offices::inRandomOrder()->first()->id,
           # 'schedule',
            'civil_status' =>  "Single",
            #'rel_beneficiary',
            #'aics_beneficiary_id'
        ];
    }
}
