<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Offices;
use App\Models\Psgc;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = User::class;


    public function definition()
    {
        $office = Offices::factory()->create();
        $psgc = Psgc::factory()->create();

        return [
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->lastName(),
            'last_name' => $this->faker->lastName(),
            'ext_name' => $this->faker->suffix(),
            'gender' => "Lalake",
            'mobile_number' =>  preg_replace('/[^0-9]/', '', $this->faker->numerify("###########")),
            'birth_date' => $this->faker->date(),
            'street_number' => $this->faker->streetAddress(),
            'psgc_id' => $psgc->id,
            'email' => $this->faker->unique()->safeEmail(),
            'username' => $this->faker->userName(),
            'office_id' => $office->id,
            'full_name' => $this->faker->name(),
            'mobile_verified' => "1",
            'password' => 'password',

        ];
    }

    /**
     * Attach a standard user role after creating or making a user model
     */
    public function configure(): UserFactory
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole("user");
        })->afterMaking(function (User $user) {
            $user->assignRole("user");
        });
    }


    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
    
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
     */

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'mobile_verified' => 0,
            ];
        });
    }
}
