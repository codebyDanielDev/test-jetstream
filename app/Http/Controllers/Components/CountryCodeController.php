<?php

namespace App\Http\Controllers\Components;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CountryCodeController extends Controller
{
    public function index()
    {
        $countryCodes = [
            ['code' => 'US', 'name' => 'United States', 'dial_code' => '+1'],
            ['code' => 'PE', 'name' => 'Peru', 'dial_code' => '+51'],
            ['code' => 'GB', 'name' => 'United Kingdom', 'dial_code' => '+44'],
            // Agrega más países según sea necesario
        ];

        return response()->json($countryCodes);
    }
}
