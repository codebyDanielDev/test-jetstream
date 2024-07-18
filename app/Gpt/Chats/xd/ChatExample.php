<?php

namespace App\Gpt\Chats\Xd;

use App\Services\GptService;

class ChatExample
{
    protected $gptService;

    public function __construct(GptService $gptService)
    {
        $this->gptService = $gptService;
        $this->gptService->setSystemMessage($this->systemMessage());
    }

    public function handle()
    {
        $prompt = '¿Cómo te llamas?';
        $response = $this->gptService->chat($prompt);
        echo $response;
    }

    protected function systemMessage()
    {
        return 'Te llamas Pedro.';
    }
}
