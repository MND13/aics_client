<?php

use App\Http\Controllers\AicsAssistanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AicsClientController;
use App\Http\Controllers\AicsBeneficiaryController;
use App\Http\Controllers\AicsTypeController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/aics'], function () {
    Route::resource('assistances', \App\Http\Controllers\AicsAssistanceController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
    Route::get('assistance-types', [\App\Http\Controllers\AicsTypeController::class, 'index'])->name("api.aics.assistance-types");
    Route::get('assistance-types/{assistance-type}', [\App\Http\Controllers\AicsTypeController::class, 'show']);
    Route::get('categories', [\App\Http\Controllers\AicsBeneficiaryController::class, 'getCategories'])->name("api.categories");
});

Route::get('psgc', [\App\Http\Controllers\PsgcController::class, 'index'])->name("api.psgc");
Route::get('psgc/{type}', [\App\Http\Controllers\PsgcController::class, 'show'])->name("api.psgc.show");
Route::get('gis/{uuid}', [\App\Http\Controllers\AicsClientController::class, 'gis'])->name("api.pdf.gis2");
Route::get('offices', [\App\Http\Controllers\OfficesController::class, 'index'])->name("api.offices.index");
Route::get('assessment_opts', [\App\Http\Controllers\AssessmentOptionsController::class, 'index'])->name("api.assessment_opts");

Route::post('assessment', [\App\Http\Controllers\AicsAssessmentController::class, 'create'])->name("api.assessment.create");
