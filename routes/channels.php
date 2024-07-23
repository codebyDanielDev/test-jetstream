<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('test-channel', function ($user) {
    return true; // Aquí puedes agregar lógica de autorización si es necesario.
});


Broadcast::channel('whatsapp-status', function ($user) {
    return true;
});
