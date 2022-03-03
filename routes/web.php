<?php

use App\Http\Controllers\AppointmentController;
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
    Route::get('/patients/create', 'create')->name('patients.create');
    Route::post('/patients', 'create')->name('patients.store');
    Route::get('/patients/{patient}', 'show')->name('patients.show');
    Route::put('/patients/{patient}', 'update')->name('patients.update');
    Route::delete('/patients/{patient}', 'delete')->name('patients.delete');
});

Route::controller(AppointmentController::class)->middleware('auth')->group(function(){
    Route::match(['POST', 'GET'],'/appointments', 'create')->name('appointments.create');
});

Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::get('/login', 'index')->name('login');
//    Route::match(['POST', 'GET'],'/patients/create', 'create')->name('');
});


