<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Helpers\NotificationHelper;

class BaileysWhatsAppChannel
{
    const AUTHENTICATED_STATUS = 'AUTHENTICATED';
    const LOG_FAILED_SESSION_RETRIEVAL = 'Failed to retrieve sessions.';
    const LOG_SESSION_NOT_FOUND = 'Session not authenticated or not found for email: ';
    const LOG_FAILED_NOTIFICATION_SEND = 'Failed to send Baileys WhatsApp notification.';
    const LOG_SUCCESS_NOTIFICATION_SEND = 'Baileys WhatsApp notification sent.';

    public function send($notifiable, Notification $notification)
    {
        try {
            // Obtener el mensaje de la notificación
            $message = $notification->toWhatsApp($notifiable);
            $phoneNumber = $notifiable->routeNotificationFor('whatsapp');

            // Verificar que el número de teléfono esté disponible
            if (!$phoneNumber) {
                Log::warning('No WhatsApp phone number found for notifiable.');
                return;
            }

            // Obtener la sesión basada en el email del notifiable
            $session = $this->getSessionFromEmail($notifiable->email);

            // Verificar si la sesión está autenticada
            if ($this->isSessionAuthenticated($session)) {
                // Enviar la notificación
                $this->sendNotification($session['id'], $phoneNumber, $message);
            } else {
                Log::warning(self::LOG_SESSION_NOT_FOUND . $notifiable->email);
            }
        } catch (\Exception $e) {
            Log::error('Exception in BaileysWhatsAppChannel: ' . $e->getMessage());
        }
    }
    private function getSessionFromEmail($email)
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => config('baileys.api_key'),
                'Content-Type' => 'application/json'
            ])
                ->timeout(config('baileys.timeout'))
                ->retry(config('baileys.retries'))
                ->get(config('baileys.base_url') . '/sessions');

            if ($response->failed()) {
                Log::error(self::LOG_FAILED_SESSION_RETRIEVAL, [
                    'response' => $response->json(),
                ]);
                return null;
            }

            $sessions = $response->json();

            foreach ($sessions as $session) {
                if ($session['id'] === $email && $session['status'] === self::AUTHENTICATED_STATUS) {
                    return $session;
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Exception while retrieving session: ' . $e->getMessage());
            return null;
        }
    }

    private function isSessionAuthenticated($session)
    {
        return $session && $session['status'] === self::AUTHENTICATED_STATUS;
    }

    private function sendNotification($sessionId, $phoneNumber, $message)
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => config('baileys.api_key'),
                'Content-Type' => 'application/json'
            ])->post(config('baileys.base_url') . '/' . $sessionId . '/messages/send', [
                'jid' => $phoneNumber . '@s.whatsapp.net',
                'type' => 'number',
                'message' => [
                    'text' => $message['message'],
                ]
            ]);

            if ($response->failed()) {
                Log::error(self::LOG_FAILED_NOTIFICATION_SEND, [
                    'response' => $response->json(),
                ]);
            } else {
                Log::info(self::LOG_SUCCESS_NOTIFICATION_SEND, [
                    'response' => $response->json(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception while sending notification: ' . $e->getMessage());
        }
    }
}
