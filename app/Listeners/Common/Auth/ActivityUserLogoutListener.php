<?php

namespace App\Listeners\Common\Auth;

use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityUserLogoutListener
{
    protected $request;

    /**
     * Create the event listener.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $headers = [
            'host' => $this->request->header('host'),
            'user-agent' => $this->request->header('user-agent'),
            'referer' => $this->request->header('referer'),
            'origin' => $this->request->header('origin'),
            'plataforma' => $this->request->header('sec-ch-ua-platform'), // Renombrado
        ];

        // Eliminar headers vacíos
        $simplifiedHeaders = array_filter($headers, fn($value) => !is_null($value) && $value !== '');

        activity()
            ->causedBy($user)
            ->withProperties([
                'ip' => $this->request->ip(),
                'headers' => $simplifiedHeaders, // Guardar los headers simplificados
            ])
            ->log('User logged out');
    }
}
