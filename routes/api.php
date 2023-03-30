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
    Route::get('beneficiaries', [\App\Http\Controllers\AicsBeneficiaryController::class, 'index']);
    Route::get('clients', [\App\Http\Controllers\AicsClientController::class, 'index'])->name("api.clients");
    Route::post('clients', [\App\Http\Controllers\AicsClientController::class, 'client_upload'])->name("api.client.upload");
    Route::post('clients/{id}', [\App\Http\Controllers\AicsClientController::class, 'update'])->name("api.client.update");
    Route::get('categories', [\App\Http\Controllers\AicsBeneficiaryController::class, 'getCategories'])->name("api.categories");
});

Route::get('psgc', [\App\Http\Controllers\PsgcController::class, 'index'])->name("api.psgc");
Route::get('psgc/{type}', [\App\Http\Controllers\PsgcController::class, 'show'])->name("api.psgc.show");
Route::get('gis/{id}', [\App\Http\Controllers\AicsClientController::class, 'gis'])->name("api.pdf.gis2");


Route::get('uploaded-files', [\App\Http\Controllers\DirtyListController::class, 'index'])->name("api.dirty-list.index");
Route::delete('uploaded-files/{id}', [\App\Http\Controllers\DirtyListController::class, 'destroy'])->name("api.dirty-list.delete");
