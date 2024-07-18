<?php

namespace App\Gpt\Notifications;

use App\Services\GptService;

class GptUserRegisteredNotification
{
    protected $gptService;

    public function __construct(GptService $gptService)
    {
        $this->gptService = $gptService;
    }


    protected function systemMessage()
    {
        return 'Genera un texto breve y formal de bienvenida para un nuevo usuario registrado en nuestra empresa IMSEGMET. Evita incluir nombres o frases como "Aquí tienes un ejemplo".';
    }

    public function generateMessage()
    {
        // Generar el mensaje del sistema
        $systemPrompt = $this->systemMessage();
        return $this->gptService->chat($systemPrompt);
    }
}
