<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;

class NotificationHelper
{
    /**
     * Verificar si las notificaciones de Baileys WhatsApp están habilitadas.
     *
     * @return bool
     */
    public static function isBaileysWhatsAppEnabled()
    {
        return Config::get('baileys.enabled', true);
    }
}
