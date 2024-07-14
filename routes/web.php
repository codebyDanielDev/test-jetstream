<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Sessions\GetSession;
use App\Http\Controllers\Dashboard;

use App\Http\Controllers\Baileys\SessionController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });


Route::middleware(['auth'])->group(function () {


    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');


    Route::post('/sessions/add', [SessionController::class, 'addSession'])->name('whatsapp-sessions.store');
    Route::get('/sessions/status', [SessionController::class, 'sessionStatus'])->name('whatsapp-sessions.status');
    Route::delete('/sessions/delete', [SessionController::class, 'deleteSession'])->name('whatsapp-sessions.destroy');
    Route::get('/sessions', [SessionController::class, 'listSessions'])->name('whatsapp-sessions.list');


});

