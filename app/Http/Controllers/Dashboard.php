<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Dashboard extends Controller
{
    public function index(Request $request)
    {
        // Obtener el correo del usuario autenticado
        $userEmail = Auth::user()->email;

        // Realizar la petición HTTP usando la fachada Http
        $response = Http::withHeaders([
            'x-api-key' => config('baileys.api_key'),
        ])->get(config('baileys.base_url') . '/sessions');

        if ($response->failed()) {
            // Manejar el error de la petición HTTP
            Log::error('Error en la petición HTTP: ' . $response->body());
            $request->session()->flash('flash.banner', 'Error en la comunicación con el servidor de sesiones.');
            $request->session()->flash('flash.bannerStyle', 'danger');
            return Inertia::render('Dashboard');
        }

        $sessions = $response->json();

        // Buscar la sesión correspondiente al correo del usuario
        $userSession = collect($sessions)->firstWhere('id', $userEmail);

        // Verificar si se encontró la sesión y establecer el mensaje de banner
        if ($userSession) {
            //$request->session()->flash('flash.banner', 'Sesión encontrada: ' . $userSession['status']);
            //$request->session()->flash('flash.bannerStyle', 'success');
        } else {
            $request->session()->flash('flash.banner', 'Sesión no encontrada. Por favor diríjase a Perfil para conectar su cuenta de WhatsApp, de lo contrario, el envío de notificaciones por WhatsApp estará deshabilitado.');
            $request->session()->flash('flash.bannerStyle', 'danger');
        }

        // Retornar la vista con Inertia
        return Inertia::render('Dashboard');
    }
}
