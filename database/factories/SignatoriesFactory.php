<?php

namespace Database\Factories;

use App\Models\Signatories;
use Illuminate\Database\Eloquent\Factories\Factory;

class SignatoriesFactory extends Factory
{
    protected $model = Signatories::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'position' => $this->faker->jobTitle(),
        ];
    }
}
