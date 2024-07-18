<?php

namespace App\Console\Commands\GPT;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class GptNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

     protected $signature = 'gpt:notification';
     protected $description = 'Create a new GPT notification file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Obtener todas las notificaciones existentes
        $notifications = $this->getNotifications();

        if (empty($notifications)) {
            $this->error('No se encontraron notificaciones existentes.');
            return 1;
        }

        // Pedir al usuario que seleccione una notificación
        $notification = $this->choice('Seleccione una notificación para asociar', $notifications);

        // Usar el nombre de la notificación seleccionada para crear el nuevo archivo
        $className = 'Gpt' . $notification;
        $namespace = 'App\\Gpt\\Notifications';
        $filePath = app_path("Gpt/Notifications/{$className}.php");

        if (File::exists($filePath)) {
            $this->error("El archivo {$className}.php ya existe.");
            return 1;
        }

        $stub = $this->getStub();
        $stub = str_replace(['{{ namespace }}', '{{ class }}', '{{ notification }}'], [$namespace, $className, $notification], $stub);

        // Crear directorios si no existen
        if (!File::exists(dirname($filePath))) {
            try {
                File::makeDirectory(dirname($filePath), 0755, true);
            } catch (\Exception $e) {
                $this->error("Error al crear el directorio: " . $e->getMessage());
                return 1;
            }
        }

        // Crear el archivo
        try {
            File::put($filePath, $stub);
        } catch (\Exception $e) {
            $this->error("Error al crear el archivo: " . $e->getMessage());
            return 1;
        }

        $this->info("Archivo {$className}.php creado exitosamente en app/Gpt/Notifications/{$className}.php.");
        return 0;
    }

    /**
     * Obtener el stub del archivo.
     */
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
    }

    protected function systemMessage()
    {
        //en acá incluir un prePromt, el cual tendrá de contexto el bot
        return 'Escribe un mensaje de bienvenida, sin incluir nombres, en un tono formal';
    }

    public function generateMessage()
    {
        // Generar el mensaje del sistema
        $systemPrompt = $this->systemMessage();
        return $this->gptService->chat($systemPrompt);
    }
}
EOD;
    }

    /**
     * Obtener todas las notificaciones existentes.
     */
    protected function getNotifications()
    {
        $path = app_path('Notifications');
        $notifications = [];

        if (File::exists($path)) {
            $files = File::allFiles($path);

            foreach ($files as $file) {
                $notifications[] = $file->getFilenameWithoutExtension();
            }
        }

        return $notifications;
    }
}
