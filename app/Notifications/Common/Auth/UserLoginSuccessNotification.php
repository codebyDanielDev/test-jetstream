<?php
namespace App\Notifications\Common\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class UserLoginSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ip;
    protected $userAgent;
    protected $platform;
    protected $browser;

    /**
     * Create a new notification instance.
     */
    public function __construct($ip, $userAgent, $platform, $browser)
    {
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->platform = $platform;
        $this->browser = $browser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'whatsapp'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Se ha registrado un nuevo inicio de sesión en tu cuenta.')
                    ->line('IP: ' . $this->ip)
                    ->line('Navegador: ' . $this->browser)
                    ->line('Plataforma: ' . $this->platform)
                    ->action('Revisar Actividad', url('/'))
                    ->line('Gracias por usar nuestra aplicación.');
    }

    public function toWhatsApp($notifiable)
    {
        $userName = $notifiable->name; // Asumiendo que el usuario tiene un campo 'name'
        $loginTime = Carbon::now()->setTimezone(config('app.timezone'))->isoFormat('dddd, MMMM Do YYYY, h:mm A'); // Formato de fecha y hora

        return [
            'type' => 'number',
            'message' => "*Hola $userName,*\n\n" . // Negrita en el nombre del usuario
                         "🔔 *¡Alerta de Seguridad!*\n\n" . // Emojis y negrita
                         "Se ha registrado un *nuevo inicio de sesión* en tu cuenta.\n\n" .
                         "*Detalles del inicio de sesión:*\n" .
                         "- Fecha y hora: *$loginTime*\n" .
                         "- IP: *$this->ip*\n" .
                         "- Navegador: *$this->browser*\n" .
                         "- Plataforma: *$this->platform*\n\n" .
                         "Si _no fuiste tú_, por favor revisa tu actividad de inmediato y cambia tu contraseña.\n\n" .
                         "Para más detalles, visita nuestro sitio web [aquí](https://www.tusitio.com).\n\n" . // Enlace
                         "_Gracias por usar nuestra aplicación._\n\n" . // Texto en cursiva
                         "Atentamente,\n" .
                         "*El equipo de Seguridad* 🔐" // Negrita y emoji
        ];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
