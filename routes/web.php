
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;


Route::get('/queue/display-data', [\App\Http\Controllers\QueueController::class, 'displayData']);

Route::view('/', 'welcome');
Route::view('/kiosk', 'kiosk');
Route::view('/vitals-desk', 'vitals_dashboard');
Route::view('/queue_display', 'queue_display');

Route::post('/queue/issue', [QueueController::class, 'issueTicket']);
Route::post('/queue/vitals/next/{counter}', [QueueController::class, 'nextToVitals']);
Route::post('/queue/vitals/complete/{id}', [QueueController::class, 'completeVitals']);
Route::post('/queue/registration/next/{counter}', [QueueController::class, 'nextToRegistration']);
Route::post('/queue/registration/complete/{id}', [QueueController::class, 'completeRegistration']);

Route::get('/queue/display-data', [QueueController::class, 'displayData']);


Route::get('/queue/display-data', [\App\Http\Controllers\QueueController::class, 'displayData']);

Route::view('/opd-clerk', 'opd_clerk');

Route::post('/queue/vitals/skip/{id}', [QueueController::class, 'skipVitals']);
Route::post('/queue/registration/skip/{id}', [QueueController::class, 'skipRegistration']);

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\QueueController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/kiosk', function () {
//     return view('kiosk');
// });

// Route::get('/vitals-desk', function () {
//     return view('vitals_dashboard');
// });

// Route::get('/queue_display', function () {
//     return view('queue_display');
// });

// // Kiosk: issue a ticket
// Route::post('/queue/issue', [
//     QueueController::class,
//     'issueTicket'
// ]);

// // Vital Signs
// Route::post('/queue/vitals/next/{counter}', [
//     QueueController::class,
//     'nextToVitals'
// ]);

// Route::post('/queue/vitals/complete/{id}', [
//     QueueController::class,
//     'completeVitals'
// ]);

// // Registration
// Route::post('/queue/registration/next/{counter}', [
//     QueueController::class,
//     'nextToRegistration'
// ]);

// Route::post('/queue/registration/complete/{id}', [
//     QueueController::class,
//     'completeRegistration'
// ]);

// use Illuminate\Support\Facades\Route;

// /*
// |--------------------------------------------------------------------------
// | Web Routes
// |--------------------------------------------------------------------------
// |
// | Here is where you can register web routes for your application. These
// | routes are loaded by the RouteServiceProvider within a group which
// | contains the "web" middleware group. Now create something great!
// |
// */
// use App\Http\Controllers\QueueController;

// // Kiosk route
// Route::post('/queue/issue', [QueueController::class, 'issueTicket']);

// // Vital Signs Department routes
// Route::post('/queue/vitals/next/{counter}', [QueueController::class, 'nextToVitals']);
// Route::post('/queue/vitals/complete/{id}', [QueueController::class, 'completeVitals']);

// // Front Desk Registration Department routes
// Route::post('/queue/registration/next/{counter}', [QueueController::class, 'nextToRegistration']);
// Route::post('/queue/registration/complete/{id}', [QueueController::class, 'completeRegistration']);

// // Route::get('/display', function () {
// //     return view('queue_display');
// // });

// // Route::get('/queue_display', function () {
// //     return view('queue_display');
// // });

// Route::get('/queue_display', function () {
//     // If your blade file loops through a variable like $queues, 
//     // passing an empty array ensures it doesn't crash:
//     return view('queue_display', [
//         'queues' => [], 
//         'current_number' => 'A-000'
//     ]);
// });



// Route::get('/kiosk', function () {
//     return view('kiosk');
// });

// Route::get('/vitals-desk', function () {
//     return view('vitals_dashboard');
// });


// Route::get('/', function () {
//     return view('welcome');
// });
