<?php

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
use App\Http\Controllers\QueueController;

// Kiosk route
Route::post('/queue/issue', [QueueController::class, 'issueTicket']);

// Vital Signs Department routes
Route::post('/queue/vitals/next/{counter}', [QueueController::class, 'nextToVitals']);
Route::post('/queue/vitals/complete/{id}', [QueueController::class, 'completeVitals']);

// Front Desk Registration Department routes
Route::post('/queue/registration/next/{counter}', [QueueController::class, 'nextToRegistration']);
Route::post('/queue/registration/complete/{id}', [QueueController::class, 'completeRegistration']);

Route::get('/display', function () {
    return view('queue_display');
});

Route::get('/kiosk', function () {
    return view('kiosk');
});

Route::get('/vitals-desk', function () {
    return view('vitals_dashboard');
});


Route::get('/', function () {
    return view('welcome');
});
