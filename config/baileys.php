<?php

return [
    'enabled' => env('BAILEYS_ENABLED', true),
    'base_url' => env('BAILEYS_BASE_URL', 'https://default-url.com'),
    'api_key' => env('BAILEYS_API_KEY', ''),
    'log' => env('BAILEYS_LOG', false),


    'timeout' => env('BAILEYS_TIMEOUT', 30), // en segundos
    'retries' => env('BAILEYS_RETRIES', 3),
    'log_level' => env('BAILEYS_LOG_LEVEL', 'error'), // o 'info', 'debug'
    'debug' => env('BAILEYS_DEBUG', false),



];
