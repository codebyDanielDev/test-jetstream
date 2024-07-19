<?php
namespace App\Listeners\Common\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Activitylog\Models\Activity;
use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Support\Facades\Request;

class ActivityUserLoginSuccessListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $headers = [
            'host' => Request::header('host'),
            'user-agent' => Request::header('user-agent'),
            'referer' => Request::header('referer'),
            'origin' => Request::header('origin'),
            'plataforma' => Request::header('sec-ch-ua-platform'), // Renombrado
        ];

        // Eliminar headers vacíos
        $simplifiedHeaders = array_filter($headers, fn($value) => !is_null($value) && $value !== '');

        activity()
            ->causedBy($user)
            ->withProperties([
                'ip' => Request::ip(),
                'headers' => $simplifiedHeaders, // Guardar los headers simplificados
            ])
            ->log('User logged in');
    }
}
