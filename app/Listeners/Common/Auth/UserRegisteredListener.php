<?php

namespace App\Listeners\Common\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\Common\Auth\UserRegisteredNotification;
use Illuminate\Support\Facades\Log;

class UserRegisteredListener implements ShouldQueue
{
    use InteractsWithQueue;

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
                $user->notify(new UserRegisteredNotification());
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
