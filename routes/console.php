<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


// Tarea programada que se ejecuta cada minuto
// Schedule::command('inspire')
//         ->everyMinute()
//         ->monitorName('Inspire Command')
//         ->emailOutputTo('tardidaw@gmail.com');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::command('app:test-notification')
//         ->everyMinute()
//         ->monitorName('test Command');

// Programar el comando para que se ejecute cada minuto
//app(Schedule::class)->command('app:test-notification')->everyMinute();
