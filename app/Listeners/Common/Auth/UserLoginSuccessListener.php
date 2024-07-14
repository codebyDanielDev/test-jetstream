<?php

namespace App\Listeners\Common\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Notifications\Common\Auth\UserLoginSuccessNotification;

class UserLoginSuccessListener
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
        // Ensure $event->user is an instance of User
        $user = $event->user;

        if ($user instanceof \App\Models\User && method_exists($user, 'notify')) {
            try {
                $user->notify(new UserLoginSuccessNotification());
            } catch (\Exception $e) {
                // Log notification failure
                Log::error('Failed to send UserRegisteredNotification: ' . $e->getMessage(), ['user_id' => $user->id]);
            }
        } else {
            // Handle the error appropriately
            Log::error('Método notify no existe en el usuario o el usuario no es un objeto User.', ['user' => $user]);
        }    }
}
