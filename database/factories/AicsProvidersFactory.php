<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AicsProviders;

class AicsProvidersFactory extends Factory
{
    protected $model = AicsProviders::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'addressee_name'=>$this->faker->name(),
            'addressee_position'=>$this->faker->jobTitle(),
            'company_name'=>$this->faker->company(),
            'company_address'=>$this->faker->address(),
            'action_executed_by'=>$this->faker->name(),
            'to_mention'=>$this->faker->name(),
        ];
    }
}
