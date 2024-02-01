<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\AicsProviders;

class AicsProvidersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_it_can_create_a_provider()
    {
        $input['addressee_name'] = $this->faker->name();
        $input['addressee_position'] = $this->faker->jobTitle();
        $input['company_name'] = $this->faker->company();
        $input['company_address'] = $this->faker->address();
        $input['action_executed_by'] = $this->faker->name();
        $input['to_mention'] = $this->faker->name();
        
        $response = $this->postJson(route('api.providers'), $input);
        $response->assertStatus(200);
    }

    public function test_it_can_update_a_provider()
    {   
        $provider = AicsProviders::factory()->create();
        $input['addressee_name'] = $this->faker->name();
        $input['addressee_position'] = $this->faker->jobTitle();
        $input['company_name'] = $this->faker->company();
        $input['company_address'] = $this->faker->address();
        $input['action_executed_by'] = $this->faker->name();
        $input['to_mention'] = $this->faker->name();
        $response = $this->putJson(route('api.providers.update', $provider->id), $input);
        $response->assertStatus(200);
    }


    public function test_it_can_delete_a_provider()
    {
        $provider = AicsProviders::factory()->create();
        $response = $this->deleteJson(route('api.providers.delete', $provider->id));
        $response->assertStatus(200);
    }



}
