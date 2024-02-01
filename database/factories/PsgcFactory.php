<?php

namespace Database\Factories;

use App\Models\Psgc;
use Illuminate\Database\Eloquent\Factories\Factory;

class PsgcFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Psgc::class;

    public function definition()
    {
        $min = 0;
        $max = 50000000;

        return [
            'region_name' => $this->faker->state(),
            'region_name_short' =>  $this->faker->state(),
            'region_psgc' => rand($min, $max),
            'province_name' => $this->faker->city(),
            'province_psgc' => rand($min, $max),
            'city_name' => $this->faker->city(),
            'city_name_psgc' => rand($min, $max),
            'brgy_name' =>  $this->faker->streetName(),
            'brgy_psgc' => rand($min, $max),
            'district' =>  $this->faker->streetName(),
            'subdistrict' =>  $this->faker->streetName(),
        ];
    }
}
