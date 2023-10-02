<?php

use App\Http\Controllers\AicsAssistanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AicsClientController;
use App\Http\Controllers\AicsBeneficiaryController;
use App\Http\Controllers\AicsTypeController;
use App\Models\FamilyRelationships;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/Providers', function (Request $request) {
    return $request->Providers();
});

Route::group(['prefix' => '/aics'], function () {
    Route::resource('assistances', \App\Http\Controllers\AicsAssistanceController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::get('assistance-types', [\App\Http\Controllers\AicsTypeController::class, 'index'])->name("api.aics.assistance-types");
    Route::get('assistance-types/{assistance-type}', [\App\Http\Controllers\AicsTypeController::class, 'show']);
    Route::get('categories', [\App\Http\Controllers\AicsBeneficiaryController::class, 'getCategories'])->name("api.categories");

    Route::post('export', [\App\Http\Controllers\AicsAssistanceController::class, 'export'])->name("api.report.export");

});

Route::get('psgc', [\App\Http\Controllers\PsgcController::class, 'index'])->name("api.psgc");
Route::get('psgc/{type}', [\App\Http\Controllers\PsgcController::class, 'show'])->name("api.psgc.show");
Route::get('gis/{uuid}', [\App\Http\Controllers\AicsClientController::class, 'gis'])->name("api.pdf.gis2");
Route::get('assessment_opts', [\App\Http\Controllers\AssessmentOptionsController::class, 'index'])->name("api.assessment_opts");

Route::post('assessment', [\App\Http\Controllers\AicsAssessmentController::class, 'create'])->name("api.assessment.create");
Route::post('assessment/{id}', [\App\Http\Controllers\AicsAssessmentController::class, 'update'])->name("api.assessment.update");

Route::get('fund_source', [\App\Http\Controllers\FundSourceController::class, 'index'])->name("api.fund_src");
Route::post('fund_source', [\App\Http\Controllers\FundSourceController::class, 'store'])->name("api.fund_src.store");
Route::put('fund_source/{id}', [\App\Http\Controllers\FundSourceController::class, 'update'])->name("api.fund_src.update");
Route::delete('fund_source/{id}', [\App\Http\Controllers\FundSourceController::class, 'destroy'])->name("api.fund_src.delete");

Route::get('provider', [\App\Http\Controllers\AicsProvidersController::class, 'index'])->name("api.providers");
Route::post('provider', [\App\Http\Controllers\AicsProvidersController::class, 'store'])->name("api.providers.store");
Route::put('provider/{id}', [\App\Http\Controllers\AicsProvidersController::class, 'update'])->name("api.providers.update");
Route::delete('provider/{id}', [\App\Http\Controllers\AicsProvidersController::class, 'destroy'])->name("api.providers.delete");

Route::get('offices', [\App\Http\Controllers\OfficesController::class, 'index'])->name("api.offices.index");
Route::post('offices', [\App\Http\Controllers\OfficesController::class, 'store'])->name("offices.store");
Route::post('offices/{id}', [\App\Http\Controllers\OfficesController::class, 'update'])->name("offices.update");
Route::delete('offices/{id}', [\App\Http\Controllers\OfficesController::class, 'destroy'])->name("offices.destroy");

Route::get('signatories', [\App\Http\Controllers\SignatoriesController::class, 'index'])->name("api.signatories");
Route::post('signatories', [\App\Http\Controllers\SignatoriesController::class, 'store'])->name("signatories.store");
Route::post('signatories/{id}', [\App\Http\Controllers\SignatoriesController::class, 'update'])->name("signatories.update");
Route::delete('signatories/{id}', [\App\Http\Controllers\SignatoriesController::class, 'destroy'])->name("signatories.destroy");

Route::get('coe/{uuid}', [\App\Http\Controllers\AicsClientController::class, 'coe'])->name("api.pdf.coe");
Route::get('cav/{uuid}', [\App\Http\Controllers\AicsClientController::class, 'cav'])->name("api.pdf.cav");
Route::get('gl/{uuid}', [\App\Http\Controllers\AicsClientController::class, 'gl'])->name("api.pdf.gl");

Route::post('charging', [\App\Http\Controllers\AicsAssessmentFundSourceController::class, 'create'])->name("charging.create");
Route::get('charging', [\App\Http\Controllers\AicsAssessmentFundSourceController::class, 'index'])->name("charging.index");
Route::get('charging/txn/{id}', [\App\Http\Controllers\AicsAssessmentFundSourceController::class, 'show'])->name("charging.txn");


Route::get('statement', [\App\Http\Controllers\FundSourceStatementController::class, 'create'])->name("statement.create");

Route::get('family_rel', [\App\Http\Controllers\FamilyRelationshipsController::class, 'index'])->name("api.family_rel");

