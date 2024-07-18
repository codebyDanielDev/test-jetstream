<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Exception;

class GptService
{
    protected $systemMessage;

    public function __construct($systemMessage = null)
    {
        $this->systemMessage = $systemMessage ?? 'Default system message';
    }

    public function setSystemMessage($message)
    {
        $this->systemMessage = $message;
    }

    public function chat($userPrompt, $model = null)
    {
        $model = $model ?? config('openai.model');
        $messages = [
            ['role' => 'system', 'content' => $this->systemMessage],
            ['role' => 'user', 'content' => $userPrompt]
        ];

        try {
            $response = OpenAI::chat()->create([
                'model' => $model,
                'messages' => $messages,
            ]);

            return $response['choices'][0]['message']['content'];
        } catch (Exception $e) {
            throw new Exception('Error al comunicarse con la API de OpenAI: ' . $e->getMessage());
        }
    }
    public function sendNotification($message)
    {
        // Implementa la lógica para enviar notificaciones aquí.
        return "Notification sent: " . $message;
    }
}
