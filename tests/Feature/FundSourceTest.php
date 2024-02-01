<?php

namespace Tests\Feature;

use App\Models\FundSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FundSourceTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_it_can_create_a_fund_source()
    {
        $input['name'] = $this->faker->name();
        $input['description'] = $this->faker->realText();
        $input['legislators'] = $this->faker->name();

        $response = $this->postJson(route('api.fund_src.store'), $input);
        $response->assertStatus(200);
    }

    public function test_it_can_update_a_fund_source()
    {   
        $fund_src = FundSource::factory()->create();
        $input['name'] = $this->faker->name();
        $input['description'] = $this->faker->realText();
        $input['legislators'] = $this->faker->name();        
        $response = $this->putJson(route('api.fund_src.update', $fund_src->id), $input);
        $response->assertStatus(200);
    }

    public function test_it_can_delete_a_fund_source()
    {
        $fund_src = FundSource::factory()->create();
        $response = $this->deleteJson(route('api.fund_src.delete', $fund_src->id));
        $response->assertStatus(200);
    }
}
