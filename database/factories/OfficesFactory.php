<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OfficesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {   
        $office =  $this->faker->company();
        return [
            'name' => $office ,
            'address' =>  $this->faker->streetAddress(),
            'contact_person' =>$this->faker->name(),
            'contact_no'=>$this->faker->phoneNumber(),
            'office_code_id'=>  $office[0], 
            'office_code'=>  $office[0], 
        ];
    }
}
