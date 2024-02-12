<?php

namespace Tests\Feature;

use App\Models\AicsAssistance;
use App\Models\AicsType;
use App\Models\Offices;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;



class AicsAssistanceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_it_can_create_an_assistance_request()
    {
        $user = User::factory()->create()->first();
        $user->assignRole('user');
        $this->actingAs($user);

        $aics_type =  AicsType::with("requirements")->inRandomOrder()->first();

        $input["assistance"]['user_id'] = $user->id;
        $input["assistance"]['aics_type_id'] =  $aics_type->id;
        $input["assistance"]['occupation'] = $this->faker->jobTitle();
        $input["assistance"]['monthly_salary'] = rand(0, 20000);;
        $input["assistance"]['office_id'] = Offices::inRandomOrder()->first()->id;
        $input["assistance"]['schedule'] = null;
        $input["assistance"]['civil_status'] = "Single";

        foreach ($aics_type->requirements as $key => $value) {

            $input["assistance"]["documents"][$value->id] = UploadedFile::fake()->image('fake_image.jpg');
        }

        $response = $this->postJson(route('assistances.store'), $input);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has('documents')
            );
    }

    public function test_it_can_update_an_assistance_request()
    {
        $user = User::factory()->create()->first();
        $user->assignRole('user');
        $this->actingAs($user);
        $assistance_request = AicsAssistance::factory()->create();

        $input['occupation'] = $this->faker->jobTitle();
        $input['monthly_salary'] = rand(0, 20000);;
        $input['office_id'] = Offices::inRandomOrder()->first()->id;
        $input['civil_status'] = "Married";

        $response = $this->putJson(route('assistances.update', $assistance_request->uuid), $input);
        # $response;

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')

            );
    }


    public function test_it_can_get_an_assistance_request()
    {
        $user = User::factory()->create()->first();
        $user->assignRole('user');
        $this->actingAs($user);
        $assistance_request = AicsAssistance::factory()->create();
        $response = $this->getJson(route('assistances.show',  $assistance_request->uuid));
        $response->assertStatus(200)->assertJson(['user_id' => $user->id]);
    }

    public function test_it_can_get_all_assistance_request()
    {
        $user = User::factory()->create()->first();
        $user->assignRole('user');
        $this->actingAs($user);
        AicsAssistance::factory()->count(5)->create();

        $admin = User::factory()->create()->first();
        $admin->assignRole('super-admin');
        $this->actingAs($admin);

        $response = $this->getJson(route('assistances.index'));
        $response->assertStatus(200)->assertJsonStructure([
            '*' => [
                'id',
                'aics_type',
                'aics_documents',
                'office',
                'aics_client',
                'assessment',
                'verified_by',                
            ],
        ]);

    }

}
