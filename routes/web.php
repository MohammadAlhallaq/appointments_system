<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\LogoutController;
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


Route::view('/', 'pages.home');


Route::GET('/{locale}', LocaleController::class)->name('changeLang')->where('locale', '(ar|en)');
Route::GET('/logout', LogoutController::class)->name('logout')->middleware('auth');

Route::controller(PatientController::class)->middleware('auth')->group(function () {
    Route::GET('/patients', 'index')->name('patients');
    Route::GET('/patients/create', 'create')->name('patients.create');
    Route::POST('/patients', 'create')->name('patients.store');
    Route::GET('/patients/{patient}', 'show')->name('patients.show');
    Route::PUT('/patients/{patient}', 'update')->name('patients.update');
    Route::DELETE('/patients/{patient}', 'delete')->name('patients.delete');
});

Route::controller(AppointmentController::class)->middleware('auth')->group(function () {
    Route::get('/appointments', 'index')->name('appointments');
    Route::POST( '/appointments', 'create')->name('appointments.store');
    Route::GET( '/appointments/create', 'create')->name('appointments.create');
    Route::DELETE( '/appointments/{appointment}', 'delete')->name('appointments.delete');
    Route::match(['PUT', 'GET'], '/patients/{patient}/appointments/{appointment:id}', 'update')->name('appointments.update');
});

Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::MATCH(['POST', 'GET'],'/login', 'login')->name('login');
});


