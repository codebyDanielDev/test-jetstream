<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<p align="center"><img src="https://raw.githubusercontent.com/laravel/jetstream/5.x/art/logo.svg" alt="Logo Laravel Jetstream"></p>

<p align="center">
    <a href="https://github.com/laravel/jetstream/actions">
        <img src="https://github.com/laravel/jetstream/workflows/tests/badge.svg" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/laravel/jetstream">
        <img src="https://img.shields.io/packagist/dt/laravel/jetstream" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/laravel/jetstream">
        <img src="https://img.shields.io/packagist/v/laravel/jetstream" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/laravel/jetstream">
        <img src="https://img.shields.io/packagist/l/laravel/jetstream" alt="License">
    </a>
</p>

## Mejoras de implementacion con Laravel 11 y Jetstream

Este proyecto está construido sobre Laravel 11 utilizando Jetstream como la base del proyecto. He realizado varias modificaciones para agregar una gran cantidad de funcionalidades adicionales.


- [Laravel 11](https://laravel.com).
- [Jetstream](https://jetstream.laravel.com/introduction.html).
- Simple WhatsApp REST API with multiple device support  usando Bayles[Bayles](https://github.com/nizarfadlan/baileys-api) con el fork de [Nizarfadlan](https://github.com/nizarfadlan)
Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Requisitos

1. **PHP 8.3**: Laravel 11 requiere PHP 8.3 o superior.
2. **Composer**: Para gestionar las dependencias de PHP.
3. **Node.js y npm**: Para gestionar las dependencias de JavaScript y compilar activos frontend.
4. **MySQL**: Para la base de datos, aunque puedes usar cualquier otra base de datos compatible con Laravel.
5. **Redis**: Para gestionar las colas de trabajo con Laravel Horizon.
6. **Gemini**: Para obtener respuestas de gemini, obtener el api key(beta).
7. **Laravel Horizon**: Para monitorear y gestionar las colas de trabajo.
8. **Baileys API**: Es necesario instalar y configurar el paquete [Baileys API](https://github.com/Danielowoow/baileys-api). Sigue los pasos de instalación y configuración en su repositorio para asegurarte de que está funcionando correctamente antes de proceder con la configuración en este proyecto.

### Instalación y Configuración
Instalación y Configuración
Clonar el repositorio:

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/tu-usuario/tu-repositorio.git
   cd tu-repositorio
   ```

2. Instalar dependencias:
   ```bash
    composer install
    npm install
   ```
3. Configurar el entorno:
   ```bash
    cp .env.example .env
    php artisan key:generate
   ```

4. Instalar dependencias::
   ```bash
    composer install
    npm install
   ```
5. Configurar las credenciales de la Baileys API en el archivo .env:
   ```bash
    BAILEYS_API_URL=https://tu-url-de-baileys-api
    BAILEYS_API_KEY=tu-clave-api

   ```

   Igual puedes revisar el archivo config/baileys.php

6. Migrar la base de datos con los seeders:
   ```bash
    php artisan migrate --seed

   ```
7. Ejecutar el servidor de desarrollo:
   ```bash
    php artisan serve
    npm run dev
    php artisan horizon
    ```
    Adicional a ello en su proyecto [Baileys API](https://github.com/Danielowoow/baileys-api) mantenerlo encendido.



## Uso

1. Crear una notificacion:
   ```bash
    php artisan make:notification
    ```
2. En el archivo de la notificación, por ejemplo App\Notifications\TuNotificacion.php:
    ```bash
use Illuminate\Notifications\Notification;

class TuNotificacion extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail', 'whatsapp'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Alerta de Seguridad')
            ->line('Se ha registrado un nuevo inicio de sesión en tu cuenta.')
            ->action('Revisar Actividad', url('/'))
            ->line('Gracias por usar nuestra aplicación!');
    }

    public function toWhatsApp($notifiable)
    {
        $userName = $notifiable->name;

        return [
            'type' => 'number',
            'message' => "*Hola $userName,*\n\n" .
                         "🔔 *¡Alerta de Seguridad!*\n\n" .
                         "Se ha registrado un *nuevo inicio de sesión* en tu cuenta.\n\n" .
                         "Si _no fuiste tú_, por favor revisa tu actividad de inmediato y cambia tu contraseña.\n\n" .
                         "Para más detalles, visita nuestro sitio web [aquí](https://www.tusitio.com).\n\n" .
                         "_Gracias por usar nuestra aplicación._\n\n" .
                         "Atentamente,\n" .
                         "*El equipo de Seguridad* 🔐"
        ];
    }
}
    ```

   
3. 
   ```bash
    php artisan make:notification
    ```


    

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
