<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
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
    return view('pages.home');
})->middleware('auth');


Route::controller(PatientController::class)->middleware('auth')->group(function () {
    Route::get('/patients', 'index')->name('patients');
    Route::match(['POST', 'GET'],'/patients/create', 'create')->name('patients.new');
});

Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::get('/login', 'index')->name('login');
//    Route::match(['POST', 'GET'],'/patients/create', 'create')->name('');
});


