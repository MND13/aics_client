<?php

namespace Database\Factories;

use App\Models\AicsAssessment;
use App\Models\AicsAssistance;
use App\Models\AssessmentOptions;
use App\Models\Category;
use App\Models\FundSource;
use App\Models\Signatories;
use App\Models\AicsProviders;


use Illuminate\Database\Eloquent\Factories\Factory;


class AicsAssessmentFactory extends Factory
{
    protected $model = AicsAssessment::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category =  Category::inRandomOrder()->first();
        $assessment_options =  AssessmentOptions::inRandomOrder()->first();
        $fund_source =  FundSource::inRandomOrder()->first();
        $signatory =  Signatories::inRandomOrder()->first();
        $provider =  AicsProviders::inRandomOrder()->first();

        $mode_of_admission = $this->faker->randomElement(["CAV", "GL"]);
        $provider_id = $mode_of_admission  == "GL" ? $provider->id : NULL;

        /*$input['assessment'] = $assessment_options->template;
        $input['category_id'] = $category->id;
        $input['gis_id'] =  NULL;
        $input['gl_for_signatory_id'] = NULL;
        $input['gl_signatory_id'] = NULL;
        $input['initials'] = null;
        $input['interviewed_by'] = NULL;
        $input['mode_of_admission'] = $this->faker->randomElement(["CAV", "GL"]);
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

       $input['amount'] =  array_sum(array_column($input['fund_sources'], 'amount'));*/



        /*return [
            'assessment' => $assessment_options->template,
            'category_id' => $category->id,
            'gis_id' =>  NULL,
            'gl_for_signatory_id' => NULL,
            'gl_signatory_id' => NULL,
            'initials' => null,
            'interviewed_by' => NULL,
            'mode_of_admission' =>  $mode_of_admission,
            'mode_of_assistance' => "WALK-IN",
            'provider_id' => $provider_id,
            'purpose' => $assessment_options->option,
            'records' => ["Valid ID Presented"],
            'records_others' => NULL,
            'signatory_id' => $signatory->id,
            'fund_sources' => NULL,
            'amount' => 1000
        ];*/

        return [
            'assessment' => $assessment_options->template,
            'category_id' => $category->id,
            'gl_for_signatory_id' => NULL,
            'gl_signatory_id' => NULL,
            'initials' => null,           
            'mode_of_admission' => $mode_of_admission,
            'mode_of_assistance' => "WALK-IN",
            'provider_id' => $provider_id,
            'purpose' => $assessment_options->option,
            'records' => json_encode(["Valid ID Presented"]),
            'records_others' => NULL,
            'signatory_id' => $signatory->id,
            'amount' =>1000
        ];

    }
}
