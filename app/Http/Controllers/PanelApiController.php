<?php

namespace App\Http\Controllers;

use App\Services\WaGatewayApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PanelApiController extends Controller
{
    public function __construct(private readonly WaGatewayApi $api)
    {
    }

    public function apps(): JsonResponse
    {
        return $this->jsonFromResponse($this->api->adminRequest('GET', '/admin/apps'));
    }

    public function storeApp(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'appId' => ['required', 'string'],
            'name' => ['nullable', 'string'],
        ]);

        return $this->jsonFromResponse($this->api->adminRequest('POST', '/admin/apps', $payload));
    }

    public function updateBlastSetting(Request $request, string $appId): JsonResponse
    {
        $payload = $request->validate([
            'delaySeconds' => ['required', 'numeric'],
            'maxPerBatch' => ['required', 'numeric'],
        ]);

        return $this->jsonFromResponse($this->api->adminRequest('PUT', "/admin/apps/{$appId}/blast-settings", $payload));
    }

    public function updateWebhookSetting(Request $request, string $appId): JsonResponse
    {
        $payload = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'url' => ['nullable', 'url'],
            'secret' => ['nullable', 'string', 'max:255'],
        ]);

        return $this->jsonFromResponse($this->api->adminRequest('PUT', "/admin/apps/{$appId}/webhook-settings", $payload));
    }

    public function regenerateApiKey(Request $request, string $appId): JsonResponse
    {
        $payload = $request->validate([
            'apiKey' => ['nullable', 'string', 'max:255'],
        ]);

        return $this->jsonFromResponse($this->api->adminRequest('PUT', "/admin/apps/{$appId}/api-key", $payload));
    }

    public function sessions(): JsonResponse
    {
        return $this->jsonFromResponse($this->api->adminRequest('GET', '/admin/sessions'));
    }

    public function createSession(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'sessionId' => ['required', 'string'],
            'label' => ['nullable', 'string'],
            'appApiKey' => ['required', 'string'],
            'connectMode' => ['nullable', 'string'],
            'phoneNumber' => ['nullable', 'string'],
        ]);

        $createResponse = $this->api->appRequest('POST', '/sessions', $payload['appApiKey'], [
            'sessionId' => $payload['sessionId'],
            'label' => $payload['label'] ?? $payload['sessionId'],
        ]);

        if (!$createResponse->successful()) {
            return response()->json($createResponse->json(), $createResponse->status());
        }

        $startResponse = $this->api->appRequest('POST', "/sessions/{$payload['sessionId']}/start", $payload['appApiKey'], [
            'connectMode' => $payload['connectMode'] ?? 'qr',
            'phoneNumber' => $payload['phoneNumber'] ?? '',
        ]);

        return response()->json($startResponse->json(), $startResponse->status());
    }

    public function reconnect(Request $request, string $sessionId): JsonResponse
    {
        $payload = $request->validate([
            'connectMode' => ['nullable', 'string'],
            'phoneNumber' => ['nullable', 'string'],
        ]);

        return $this->jsonFromResponse($this->api->adminRequest('POST', "/admin/sessions/{$sessionId}/reconnect", $payload));
    }

    public function disconnect(string $sessionId): JsonResponse
    {
        return $this->jsonFromResponse($this->api->adminRequest('POST', "/admin/sessions/{$sessionId}/disconnect"));
    }

    public function deleteSession(string $sessionId): JsonResponse
    {
        return $this->jsonFromResponse($this->api->adminRequest('DELETE', "/admin/sessions/{$sessionId}"));
    }

    public function updateSession(Request $request, string $sessionId): JsonResponse
    {
        $payload = $request->validate(['label' => ['nullable', 'string']]);

        return $this->jsonFromResponse($this->api->adminRequest('PUT', "/admin/sessions/{$sessionId}", $payload));
    }

    public function sessionToken(string $sessionId): JsonResponse
    {
        return $this->jsonFromResponse($this->api->adminRequest('GET', "/admin/sessions/{$sessionId}/token"));
    }

    public function sessionQr(Request $request, string $sessionId): JsonResponse
    {
        $appApiKey = (string) $request->query('appApiKey', '');
        if (!$appApiKey) {
            return response()->json(['message' => 'appApiKey wajib.'], 422);
        }

        return $this->jsonFromResponse($this->api->appRequest('GET', "/sessions/{$sessionId}/qr", $appApiKey));
    }

    public function phonebook(Request $request): JsonResponse
    {
        $appId = (string) $request->query('appId', '');
        return $this->jsonFromResponse($this->api->adminRequest('GET', '/admin/phonebook', [], ['appId' => $appId]));
    }

    public function storePhonebook(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'appId' => ['required', 'string'],
            'name' => ['nullable', 'string'],
            'phone' => ['required', 'string'],
            'note' => ['nullable', 'string'],
        ]);

        return $this->jsonFromResponse($this->api->adminRequest('POST', '/admin/phonebook', $payload));
    }

    public function deletePhonebook(string $id): JsonResponse
    {
        return $this->jsonFromResponse($this->api->adminRequest('DELETE', "/admin/phonebook/{$id}"));
    }

    public function groups(Request $request): JsonResponse
    {
        $sessionId = (string) $request->query('sessionId', '');
        return $this->jsonFromResponse($this->api->adminRequest('GET', '/admin/groups', [], ['sessionId' => $sessionId]));
    }

    public function messages(Request $request): JsonResponse
    {
        $query = Arr::only($request->query(), ['appId', 'sessionId', 'limit']);
        return $this->jsonFromResponse($this->api->adminRequest('GET', '/admin/messages', [], $query));
    }

    public function deleteMessage(string $id): JsonResponse
    {
        return $this->jsonFromResponse($this->api->adminRequest('DELETE', "/admin/messages/{$id}"));
    }

    public function deleteMessageBulk(Request $request): JsonResponse
    {
        $payload = $request->validate(['ids' => ['required', 'array']]);
        return $this->jsonFromResponse($this->api->adminRequest('POST', '/admin/messages/delete-bulk', $payload));
    }

    public function deleteAllMessages(): JsonResponse
    {
        return $this->jsonFromResponse($this->api->adminRequest('DELETE', '/admin/messages'));
    }

    public function exportMessages(Request $request): StreamedResponse
    {
        $query = Arr::only($request->query(), ['appId', 'sessionId']);
        $response = $this->api->adminRequest('GET', '/admin/messages/export.csv', [], $query);

        return response()->streamDownload(function () use ($response): void {
            echo $response->body();
        }, 'message-history.csv', ['Content-Type' => 'text/csv']);
    }

    public function sendBulk(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'appApiKey' => ['required', 'string'],
            'sessionId' => ['required', 'string'],
            'message' => ['nullable', 'string'],
            'imageUrl' => ['nullable', 'url'],
            'targets' => ['required', 'array'],
        ]);

        if (blank($payload['message'] ?? null) && blank($payload['imageUrl'] ?? null)) {
            return response()->json(['message' => 'message atau imageUrl wajib.'], 422);
        }

        return $this->jsonFromResponse(
            $this->api->appRequest('POST', '/messages/send-bulk', $payload['appApiKey'], [
                'sessionId' => $payload['sessionId'],
                'message' => $payload['message'],
                'imageUrl' => $payload['imageUrl'] ?? null,
                'targets' => $payload['targets'],
            ])
        );
    }

    private function jsonFromResponse($response): JsonResponse
    {
        return response()->json($response->json() ?? [], $response->status());
    }
}
