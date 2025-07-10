<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'prokerala_client_id' => 'nullable|string|regex:/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            'prokerala_client_secret' => 'nullable|string|min:32',
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
        ], [
            'prokerala_client_id.regex' => 'Client ID must be in UUID format (xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx)',
            'prokerala_client_secret.min' => 'Client secret must be at least 32 characters long',
        ]);

        foreach ($request->only([
            'prokerala_client_id',
            'prokerala_client_secret',
            'site_name',
            'site_description',
            'contact_email',
            'contact_phone',
            'contact_address',
            'whatsapp_number',
            'facebook_url',
            'twitter_url',
            'instagram_url',
            'youtube_url'
        ]) as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    public function validateClientId(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'prokerala_client_id' => 'required|string|regex:/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid client ID format. Must be a valid UUID.',
                'errors' => $e->errors()
            ], 422);
        }

        $clientId = trim($request->prokerala_client_id);
        $origin = request()->getSchemeAndHttpHost();

        $result = $this->validateProkeralaClient($clientId, $origin);

        if ($result['status'] === 'ok') {
            return response()->json([
                'status' => 'success',
                'message' => 'Client ID verified successfully!'
            ]);
        }

        $errorMessages = [
            'invalid_client' => 'Server rejected the client ID. Please check your credentials.',
            'unauthorized_origin' => "Add <code>{$origin}</code> to your <a class=\"underline\" href=\"https://api.prokerala.com/account/client/{$clientId}\">authorized origins</a> in the Prokerala dashboard.",
            'http_error' => 'Failed to verify client ID. Please check your internet connection or firewall settings.',
        ];

        $message = $errorMessages[$result['status']] ?? 'Failed to validate client ID. Please try again.';

        return response()->json([
            'status' => 'error',
            'message' => $message
        ], 400);
    }

    private function validateProkeralaClient(string $clientId, string $origin): array
    {
        try {
            $response = Http::withHeaders([
                'Authority' => 'api.prokerala.com',
                'Content-Length' => '0',
                'User-Agent' => 'Laravel Astrology App v1.0',
                'Origin' => $origin,
            ])->post("https://api.prokerala.com/client/verify/{$clientId}");

            if ($response->failed()) {
                return [
                    'status' => 'http_error',
                    'error' => 'HTTP request failed'
                ];
            }

            return $response->json() ?: ['status' => 'unknown_error'];

        } catch (\Exception $e) {
            return [
                'status' => 'http_error',
                'error' => $e->getMessage()
            ];
        }
    }
}
