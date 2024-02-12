<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FundSource;

class FundSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

     protected $model = FundSource::class;


    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->realText(),
            'legislators' => $this->faker->name(),
        ];
    }
}
