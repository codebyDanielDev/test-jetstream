<?php

namespace App\Http\Controllers\Verify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function check(Request $request)
    {
        return response()->json(['verified' => $request->user()->hasVerifiedEmail()]);
    }
}
