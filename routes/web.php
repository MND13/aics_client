<?php

use App\Http\Controllers\AicsBeneficiaryController;
use App\Http\Controllers\AicsClientController;
use App\Http\Controllers\AicsDocumentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {   
    if (auth()->check()) return redirect('/home'); 
    return view('auth.login');
});


Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');

Auth::routes();
Route::get('/holiday-crawler', [App\Models\HolidayCrawler::class, 'crawler'])->name('holiday-crawler');
//Route::get('/verify_mobile', [App\Models\OtpController::class, 'index'])->name('otp.form');

Route::get('/{any}', [App\Http\Controllers\HomeController::class, 'index'])->where('any','^(?!js/).*')->middleware('mobile_verified');