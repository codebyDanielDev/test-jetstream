<?php

namespace App\Listeners\Common\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
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
                $ip = Request::ip();
                $userAgent = Request::header('User-Agent');
                $platform = Request::header('sec-ch-ua-platform');
                $browser = $this->getBrowserName(Request::header('sec-ch-ua'));

                $user->notify(new UserLoginSuccessNotification($ip, $userAgent, $platform, $browser));
            } catch (\Exception $e) {
                // Log notification failure
                Log::error('Failed to send UserRegisteredNotification: ' . $e->getMessage(), ['user_id' => $user->id]);
            }
        } else {
            // Handle the error appropriately
            Log::error('Método notify no existe en el usuario o el usuario no es un objeto User.', ['user' => $user]);
        }
    }

    private function getBrowserName($secChUa)
    {
        if (preg_match('/Opera|OPR/', $secChUa)) {
            return 'Opera';
        } elseif (preg_match('/Edg/', $secChUa)) {
            return 'Edge';
        } elseif (preg_match('/Chrome/', $secChUa)) {
            return 'Chrome';
        } elseif (preg_match('/Safari/', $secChUa) && !preg_match('/Chrome/', $secChUa)) {
            return 'Safari';
        } elseif (preg_match('/Firefox/', $secChUa)) {
            return 'Firefox';
        } elseif (preg_match('/MSIE|Trident/', $secChUa)) {
            return 'Internet Explorer';
        } else {
            return 'Desconocido';
        }
    }
}

