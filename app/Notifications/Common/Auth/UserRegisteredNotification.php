<?php

namespace App\Notifications\Common\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $gptMessage;

    /**
     * Create a new notification instance.
     *
     * @param string $gptMessage
     */
    public function __construct(string $gptMessage)
    {
        $this->gptMessage = $gptMessage;
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
                    ->subject('¡Bienvenido a nuestra plataforma!')
                    ->greeting('¡Bienvenido a nuestra plataforma!')
                    ->line($this->gptMessage)
                    ->line('---')
                    ->line('**Nota:** Este mensaje ha sido generado automáticamente por un sistema de inteligencia artificial. Podría contener errores o inexactitudes.')
                    ->line('Si tiene alguna pregunta o necesita asistencia, no dude en contactarnos.')
                    ->action('Visite nuestro sitio web', url('/'))
                    ->line('Gracias por usar nuestra aplicación!')
                    ->salutation('Saludos cordiales, El equipo de soporte.');
    }
    public function toWhatsApp($notifiable)
    {
        return [
            'type' => 'number',
            'message' => "¡Bienvenido a nuestra plataforma!\n\n" .
                         $this->gptMessage . "\n\n" .
                         "*Nota:* Este mensaje ha sido generado automáticamente por un sistema de inteligencia artificial. Podría contener errores o inexactitudes.\n\n" .
                         "Si tiene alguna pregunta o necesita asistencia, no dude en contactarnos.\n\n" .
                         "Gracias por usar nuestra aplicación!\n\n" .
                         "Saludos cordiales,\nEl equipo de soporte.",
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
