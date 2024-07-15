<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Sessions\GetSession;
use App\Http\Controllers\Dashboard;

use App\Http\Controllers\Baileys\SessionController;



use App\Http\Controllers\Components\CountryCodeController;
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



//ruta para componentes de controladores de uso no autenticado
Route::middleware([])->group(function () {
    Route::get('/components/country-codes', [CountryCodeController::class, 'index'])->name('components.get-country-codes');
});




use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;

Route::get('/test-gemini', function () {
    $apiKey = env('GEMINI_API_KEY');
    $client = Gemini::client($apiKey);

    $result = $client->geminiPro()->generateContent('Hola, dime como te llamas');
    $response = [
        'result' => $result->text()
    ];

    return response()->json($response);
});
