<?php

namespace App\Console\Commands\GPT;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GptChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gpt:chat {filename}';
    protected $description = 'Create a new GPT chat file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = $this->argument('filename');

        if (!$this->isValidFilename($filename)) {
            $this->error("El nombre del archivo no es válido. Utilice sólo letras, números y los caracteres / o \\.");
            return 1;
        }

        $path = str_replace('\\', '/', $filename);
        $className = Str::studly(class_basename($path));
        $namespace = trim(implode('\\', array_map([Str::class, 'studly'], explode('/', dirname($path)))), '\\');
        $filePath = app_path("Gpt/Chats/{$path}.php");

        if (File::exists($filePath)) {
            $this->error("El archivo {$className}.php ya existe.");
            return 1;
        }

        $stub = $this->getStub();
        $stub = str_replace(['{{ namespace }}', '{{ class }}'], ["App\\Gpt\\Chats\\{$namespace}", $className], $stub);

        if (!File::exists(dirname($filePath))) {
            try {
                File::makeDirectory(dirname($filePath), 0755, true);
            } catch (\Exception $e) {
                $this->error("Error al crear el directorio: " . $e->getMessage());
                return 1;
            }
        }

        try {
            File::put($filePath, $stub);
        } catch (\Exception $e) {
            $this->error("Error al crear el archivo: " . $e->getMessage());
            return 1;
        }

        $this->info("Archivo {$className}.php creado exitosamente en app/Gpt/Chats/{$path}.php.");
        return 0;
    }

    protected function getStub()
    {
        return <<<'EOD'
<?php

namespace {{ namespace }};

use App\Services\GptService;

class {{ class }}
{
    protected $gptService;

    public function __construct(GptService $gptService)
    {
        $this->gptService = $gptService;
        $this->gptService->setSystemMessage($this->systemMessage());
    }

    public function handle()
    {
        // $prompt = '¿Cómo te llamas?';
        // $response = $this->gptService->chat($prompt);
        // echo $response;
    }

    protected function systemMessage()
    {
        return 'Carlos';
    }
}
EOD;
    }

    protected function isValidFilename($filename)
    {
        return preg_match('/^[A-Za-z0-9\/\\\\]+$/', $filename);
    }
}
