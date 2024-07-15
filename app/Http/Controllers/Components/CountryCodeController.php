<?php

namespace App\Http\Controllers\Components;

use App\Http\Controllers\Controller;
use App\Models\CountryCode;
use Illuminate\Http\Request;

class CountryCodeController extends Controller
{
    public function index(Request $request)
    {
        $query = CountryCode::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('dial_code', 'like', '%' . $request->search . '%');
        }

        $countryCodes = $query->select('code', 'name', 'dial_code')
                              ->orderBy('name', 'asc') // Ordena alfabéticamente de A a Z
                              ->paginate(15); // Cambia este valor según sea necesario

        return response()->json($countryCodes);
    }
}
