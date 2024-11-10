<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/home', [EventController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('events', EventController::class);
    Route::post('/events/{event}/register', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::post('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::post('/events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');
    Route::post('/events/{event}/archive', [EventController::class, 'archive'])->name('events.archive');
});
