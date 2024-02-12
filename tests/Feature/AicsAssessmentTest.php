<?php

namespace Tests\Feature;

use App\Models\AicsAssessment;
use App\Models\AicsAssistance;
use App\Models\AicsProviders;
use App\Models\AicsType;
use App\Models\AssessmentOptions;
use App\Models\Category;
use App\Models\FundSource;
use App\Models\Offices;
use App\Models\Signatories;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Facades\Session;


class AicsAssessmentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_it_can_create_assessement()
    {
        $user = User::factory()->create()->first();
        $user->assignRole('user');
        $this->actingAs($user);
        $assistance = AicsAssistance::factory()->create();

        Session::flush();
        $social_worker = User::factory()->create()->first();
        $social_worker->removeRole("user");
        $social_worker->assignRole('social-worker');
        $this->actingAs($social_worker);

        $category =  Category::inRandomOrder()->first();
        $assessment_options =  AssessmentOptions::inRandomOrder()->first();
        $fund_source =  FundSource::inRandomOrder()->first();
        $signatory =  Signatories::inRandomOrder()->first();
        $provider =  AicsProviders::inRandomOrder()->first();

        $input['assessment'] = $assessment_options->template;
        $input['category_id'] = $category->id;
        $input['gis_id'] = $assistance->id;
        $input['gl_for_signatory_id'] = NULL;
        $input['gl_signatory_id'] = NULL;
        $input['initials'] = mb_substr($social_worker->first_name, 0, 1);
        $input['mode_of_admission'] = $this->faker()->randomElement(["CAV", "GL"]);
        $input['mode_of_assistance'] = "WALK-IN";
        $input['provider_id'] = $input['mode_of_admission']  == "GL" ? $provider->id : NULL;
        $input['purpose'] = $assessment_options->option;
        $input['records'] = ["Valid ID Presented"];
        $input['records_others'] = NULL;
        $input['signatory_id'] = $signatory->id;

        $input['fund_sources'][] = [
            "id" => $fund_source->id,
            "name" => $fund_source->name,
            "amount" => 1000,
        ];

        $input['amount'] =  array_sum(array_column($input['fund_sources'], 'amount'));

        $response =  $this->postJson(route('api.assessment.create'), $input)
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
            );
    }

    public function test_it_can_update_assessment()
    {
        $user = User::factory()->create()->first();
        $user->assignRole('user');
        $this->actingAs($user);
        $assistance = AicsAssistance::factory()->create();

        Session::flush();
        $social_worker = User::factory()->create()->first();
        $social_worker->removeRole("user");
        $social_worker->assignRole('social-worker');
        $this->actingAs($social_worker);

        $assessment = AicsAssessment::factory()
            ->create([
                'initials' => mb_substr($social_worker->first_name, 0, 1),
            ])->first();

        $category =  Category::inRandomOrder()->first();
        $assessment_options =  AssessmentOptions::inRandomOrder()->first();
        $fund_source =  FundSource::inRandomOrder()->first();
        $signatory =  Signatories::inRandomOrder()->first();
        $provider =  AicsProviders::inRandomOrder()->first();

        $input['assessment'] = $assessment_options->template;
        $input['category_id'] = $category->id;
        $input['gis_id'] = $assistance->id;
        $input['gl_for_signatory_id'] = NULL;
        $input['gl_signatory_id'] = NULL;
        $input['initials'] = mb_substr($social_worker->first_name, 0, 1);
        $input['mode_of_admission'] = $this->faker()->randomElement(["CAV", "GL"]);
        $input['mode_of_assistance'] = "WALK-IN";
        $input['provider_id'] = $input['mode_of_admission']  == "GL" ? $provider->id : NULL;
        $input['purpose'] = $assessment_options->option;
        $input['records'] = ["Valid ID Presented", ""];
        $input['records_others'] = NULL;
        $input['signatory_id'] = $signatory->id;

        $input['fund_sources'][] = [
            "id" => $fund_source->id,
            "name" => $fund_source->name,
            "amount" => 2000,
        ];

        $input['amount'] =  array_sum(array_column($input['fund_sources'], 'amount'));

        $response =  $this->postJson(route('api.assessment.update', $assessment->id), $input)
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
            );
                
    }
}
