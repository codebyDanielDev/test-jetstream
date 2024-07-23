<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Sessions\GetSession;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Verify\VerificationController;

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


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {


    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');


    Route::post('/sessions/add', [SessionController::class, 'addSession'])->name('whatsapp-sessions.store');
    Route::get('/sessions/status', [SessionController::class, 'sessionStatus'])->name('whatsapp-sessions.status');
    Route::delete('/sessions/delete', [SessionController::class, 'deleteSession'])->name('whatsapp-sessions.destroy');
    Route::get('/sessions', [SessionController::class, 'listSessions'])->name('whatsapp-sessions.list');

});



Route::middleware(['auth'])->group(function () {
    Route::get('/verification/check', [VerificationController::class, 'check'])->name('verification.check');
});
//ruta para componentes de controladores de uso no autenticado
Route::middleware([])->group(function () {
    Route::get('/components/country-codes', [CountryCodeController::class, 'index'])->name('components.get-country-codes');
});




use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;

//probar y profunzar despues https://github.com/google-gemini-php/client?tab=readme-ov-file#stream-generate-content
Route::get('/test-gemini', function () {
    $apiKey = env('GEMINI_API_KEY');
    $client = Gemini::client($apiKey);

    $result = $client->geminiPro()->generateContent('dame una imagen de un gato');
    $response = [
        'result' => $result->text()
    ];

    return response()->json($response);
});


use App\Services\GptService;
use App\Gpt\Chats\xd\ChatExample;

Route::get('/test-chat', function (GptService $gptService) {
    // Instancia la clase ChatExample y ejecuta su método handle
    $chatExample = new ChatExample($gptService);

    try {
        ob_start();
        $chatExample->handle();
        $output = ob_get_clean();
        return response()->json(['response' => $output]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

use App\Events\TestEvent;
Route::get('/broadcast-test', function () {
    event(new TestEvent('Hello, Reverb!'));
    return 'Event has been broadcasted!';
});
