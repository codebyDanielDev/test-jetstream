<?php

namespace App\Http\Controllers\Baileys;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SessionController extends Controller
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('baileys.base_url');
        $this->apiKey = config('baileys.api_key');
    }

    protected function getHeaders()
    {
        return ['X-API-Key' => $this->apiKey];
    }

    public function listSessions()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get("{$this->baseUrl}/sessions");
            $sessions = $response->json();

            if (!is_array($sessions)) {
                throw new \Exception('Unexpected response format');
            }

            return response()->json([
                'success' => true,
                'sessions' => $sessions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function findSession($id)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get("{$this->baseUrl}/sessions/{$id}");
            $session = $response->json();
            return response()->json($session);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sessionStatus()
    {
        $user = Auth::user();
        $sessionId = $user->email;

        try {
            $response = Http::withHeaders($this->getHeaders())->get("{$this->baseUrl}/sessions/{$sessionId}/status");
            $status = $response->json();

            if (isset($status['status'])) {
                return response()->json(['status' => $status['status']]);
            }
        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
                return response()->json(['status' => 'DISCONNECTED'], 404);
            }
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addSession(Request $request)
    {
        $user = Auth::user();
        $sessionId = $user->email;

        try {
            $existingSession = Http::withHeaders($this->getHeaders())->get("{$this->baseUrl}/sessions/{$sessionId}/status")->json();
            if (isset($existingSession['status']) && $existingSession['status'] !== 'DISCONNECTED') {
                return response()->json(['error' => 'Session already exists'], 409);
            }

            $response = Http::withHeaders($this->getHeaders())->post("{$this->baseUrl}/sessions/add", [
                'sessionId' => $sessionId,
            ]);
            $session = $response->json();
            return response()->json($session, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteSession()
    {
        $user = Auth::user();
        $sessionId = $user->email;

        try {
            $response = Http::withHeaders($this->getHeaders())->delete("{$this->baseUrl}/sessions/{$sessionId}");
            return response()->json($response->json());
        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
                return response()->json(['error' => 'Session not found'], 404);
            }
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
