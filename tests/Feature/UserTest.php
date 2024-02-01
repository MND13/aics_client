<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;



class UserTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
   
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_it_can_create_a_user()
    {   
        $this->withoutMiddleware();
        
        $input['first_name'] = $this->faker->firstName();
        $input['middle_name'] = $this->faker->firstName();
        $input['last_name'] = $this->faker->lastName();
        $input['ext_name'] = $this->faker->suffix();
        $input['gender'] = $this->faker->randomElement(['male', 'female']);
        $input['mobile_number'] = preg_replace('/[^0-9]/', '', $this->faker->phoneNumber());
        $input['birth_date'] = $this->faker->date($format = 'Y-m-d', $max = 'now');
        $input['street_number'] = $this->faker->streetAddress();
        $input['psgc_id'] = 368;
        $input['email'] = $this->faker->unique()->safeEmail();
        $input['username'] = $this->faker->userName();
        $input['valid_id'] = UploadedFile::fake()->image('ValidID.jpg');
        $input['client_photo'] =  UploadedFile::fake()->image('Avatar.jpg');
        $input['password'] = bcrypt("password123");
        $input['password_confirmation'] = bcrypt("password123");

        $response = $this->withHeaders(['X-CSRF-TOKEN' => csrf_token()])
            ->postJson('register', $input);

        $response->assertSuccessful(); 
    }
}
