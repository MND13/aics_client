<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Signatories;

class SignatoriesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();       
    }

    public function test_it_can_create_a_signatory()
    {
        $input['name'] = $this->faker->name();
        $input['position'] = $this->faker->jobTitle();
        $response = $this->postJson(route('api.signatories'), $input);
        $response->assertStatus(200);
    }

    public function test_it_can_update_a_signatory()
    {   
        $signatory = Signatories::factory()->create();
        $input['name'] = $this->faker->name();
        $input['position'] = $this->faker->jobTitle();
        $response = $this->postJson(route('signatories.update', $signatory->id), $input);
        $response->assertStatus(200);
    }


    public function test_it_can_delete_a_signatory()
    {
        $signatory = Signatories::factory()->create();
        $response = $this->deleteJson(route('signatories.destroy', $signatory->id));
        $response->assertStatus(200);
    }

}
