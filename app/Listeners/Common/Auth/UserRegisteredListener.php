<?php

namespace App\Listeners\Common\Auth;

use Illuminate\Auth\Events\Registered;
use App\Notifications\Common\Auth\UserRegisteredNotification;
use App\Gpt\Notifications\GptUserRegisteredNotification;
use App\Services\GptService;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegisteredListener //implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        // Initialization if needed
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        // Ensure $event->user is an instance of User
        $user = $event->user;

        if ($user instanceof \App\Models\User && method_exists($user, 'notify')) {
            try {
                // Crear una instancia del servicio y de la notificación GPT
                $gptService = new GptService();
                $gptNotification = new GptUserRegisteredNotification($gptService);

                // Generar el mensaje dinámico
                $gptMessage = $gptNotification->generateMessage();

                // Crear la notificación con el mensaje generado
                $userNotification = new UserRegisteredNotification($gptMessage);

                // Enviar la notificación al usuario
                $user->notify($userNotification);
            } catch (\Exception $e) {
                // Log notification failure
                Log::error('Failed to send UserRegisteredNotification: ' . $e->getMessage(), ['user_id' => $user->id]);
            }
        } else {
            // Handle the error appropriately
            Log::error('Método notify no existe en el usuario o el usuario no es un objeto User.', ['user' => $user]);
        }
    }
}
