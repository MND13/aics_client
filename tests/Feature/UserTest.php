<?php

namespace Tests\Feature;

use App\Models\Psgc;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use App\Models\User;


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
    {    $maxDate = date('Y-m-d', strtotime('-18 years'));
        $psgc =  Psgc::inRandomOrder()->first();
      

        $this->withoutMiddleware();        
        $input['first_name'] = $this->faker->firstName();
        $input['middle_name'] = $this->faker->firstName();
        $input['last_name'] = $this->faker->lastName();
        $input['ext_name'] = $this->faker->suffix();
        $input['gender'] = $this->faker->randomElement(['male', 'female']);
        $input['mobile_number'] = preg_replace('/[^0-9]/', '', $this->faker->numerify("###########"));
        $input['birth_date'] = $this->faker->date($maxDate);
        $input['street_number'] = $this->faker->streetAddress();
        $input['psgc_id'] = $psgc->id;
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

    public function test_it_can_update_a_user()
    {   
        $psgc = Psgc::factory()->create();
        $user = User::factory()->create();
        $maxDate = date('Y-m-d', strtotime('-18 years'));

        $input['first_name'] = $this->faker->firstName();
        $input['middle_name'] = $this->faker->firstName();
        $input['last_name'] = $this->faker->lastName();
        $input['ext_name'] = $this->faker->suffix();
        $input['gender'] = $this->faker->randomElement(['male', 'female']);
        $input['mobile_number'] = preg_replace('/[^0-9]/', '', $this->faker->numerify("###########"));
        $input['birth_date'] = $this->faker->date($maxDate);
        $input['street_number'] = $this->faker->streetAddress();
        $input['psgc_id'] = $psgc->id;
        $input['email'] = $this->faker->unique()->safeEmail();
        $input['username'] = $this->faker->userName();
        $input['password'] = "password123";
        $input['password_confirmation'] = "password123";
        $input['role'] = "user";

        $response = $this->putJson(route('users.update', $user->id), $input);
        $response->assertStatus(200);
    }

    public function test_it_can_soft_delete_a_user(): void
    {
        $user = User::factory()->create();
        $response = $this->deleteJson(route('users.destroy', $user->id));
        $this->assertSoftDeleted($user);
    }

    
}
