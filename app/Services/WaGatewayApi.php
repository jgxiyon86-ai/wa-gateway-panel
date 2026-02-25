<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WaGatewayApi
{
    public function loginNodeAdmin(): array
    {
        $response = Http::baseUrl(config('wa_gateway.node_base_url'))
            ->timeout(max(5, (int) config('wa_gateway.node_timeout_seconds', 20)))
            ->post('/auth/login', [
                'username' => config('wa_gateway.node_panel_user'),
                'password' => config('wa_gateway.node_panel_password'),
            ]);

        $response->throw();

        return [
            'token' => (string) ($response->json('token') ?? ''),
            'expires_at' => (int) ($response->json('expiresAt') ?? 0),
        ];
    }

    public function logoutNodeAdmin(?string $token = null): void
    {
        if (!$token) {
            return;
        }

        try {
            Http::baseUrl(config('wa_gateway.node_base_url'))
                ->timeout(max(5, (int) config('wa_gateway.node_timeout_seconds', 20)))
                ->withToken($token)
                ->post('/auth/logout');
        } catch (\Throwable) {
            // no-op: logout ke node tidak boleh menggagalkan logout panel.
        }
    }

    public function adminRequest(string $method, string $path, array $payload = [], array $query = []): Response
    {
        $token = $this->ensureNodeAdminToken();

        $client = Http::baseUrl(config('wa_gateway.node_base_url'))
            ->timeout(max(5, (int) config('wa_gateway.node_timeout_seconds', 20)))
            ->acceptJson()
            ->withToken($token)
            ->withQueryParameters($query);

        $response = $this->sendWithRetry($client, strtoupper($method), $path, ['json' => $payload]);

        if ($response->status() === 401) {
            $token = $this->refreshNodeAdminToken();
            $client = Http::baseUrl(config('wa_gateway.node_base_url'))
                ->timeout(max(5, (int) config('wa_gateway.node_timeout_seconds', 20)))
                ->acceptJson()
                ->withToken($token)
                ->withQueryParameters($query);
            $response = $this->sendWithRetry($client, strtoupper($method), $path, ['json' => $payload]);
        }

        return $response;
    }

    public function appRequest(string $method, string $path, string $apiKey, array $payload = [], array $query = []): Response
    {
        $client = Http::baseUrl(config('wa_gateway.node_base_url'))
            ->timeout(max(5, (int) config('wa_gateway.node_timeout_seconds', 20)))
            ->acceptJson()
            ->withHeaders(['x-api-key' => $apiKey])
            ->withQueryParameters($query);

        return $this->sendWithRetry($client, strtoupper($method), $path, ['json' => $payload]);
    }

    private function ensureNodeAdminToken(): string
    {
        $token = (string) session('wa_node_admin_token', '');
        $expiresAt = (int) session('wa_node_admin_token_expires_at', 0);
        $now = time();

        if ($token !== '' && $expiresAt > ($now + 60)) {
            return $token;
        }

        return $this->refreshNodeAdminToken();
    }

    private function refreshNodeAdminToken(): string
    {
        $login = $this->loginNodeAdmin();
        $token = (string) ($login['token'] ?? '');
        $expiresAt = (int) ($login['expires_at'] ?? 0);
        session([
            'wa_node_admin_token' => $token,
            'wa_node_admin_token_expires_at' => $expiresAt,
        ]);

        return $token;
    }

    private function sendWithRetry($client, string $method, string $path, array $options = []): Response
    {
        $attempts = max(0, (int) config('wa_gateway.node_retry_times', 1));
        $sleepMs = max(0, (int) config('wa_gateway.node_retry_sleep_ms', 400));
        $maxTries = $attempts + 1;
        $response = null;

        for ($i = 1; $i <= $maxTries; $i++) {
            $response = $client->send($method, $path, $options);
            $status = $response->status();
            $retryable = ($status === 429) || ($status >= 500 && $status <= 599);

            if (!$retryable || $i === $maxTries) {
                return $response;
            }

            usleep($sleepMs * 1000);
        }

        return $response;
    }
}
