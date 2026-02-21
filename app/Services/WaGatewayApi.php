<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WaGatewayApi
{
    public function loginNodeAdmin(): string
    {
        $response = Http::baseUrl(config('wa_gateway.node_base_url'))
            ->post('/auth/login', [
                'username' => config('wa_gateway.node_panel_user'),
                'password' => config('wa_gateway.node_panel_password'),
            ]);

        $response->throw();

        return (string) ($response->json('token') ?? '');
    }

    public function adminRequest(string $method, string $path, array $payload = [], array $query = []): Response
    {
        $token = session('wa_node_admin_token');

        if (!$token) {
            $token = $this->loginNodeAdmin();
            session(['wa_node_admin_token' => $token]);
        }

        $client = Http::baseUrl(config('wa_gateway.node_base_url'))
            ->acceptJson()
            ->withToken($token)
            ->withQueryParameters($query);

        $response = $client->send(strtoupper($method), $path, ['json' => $payload]);

        if ($response->status() === 401) {
            $token = $this->loginNodeAdmin();
            session(['wa_node_admin_token' => $token]);
            $response = Http::baseUrl(config('wa_gateway.node_base_url'))
                ->acceptJson()
                ->withToken($token)
                ->withQueryParameters($query)
                ->send(strtoupper($method), $path, ['json' => $payload]);
        }

        return $response;
    }

    public function appRequest(string $method, string $path, string $apiKey, array $payload = [], array $query = []): Response
    {
        return Http::baseUrl(config('wa_gateway.node_base_url'))
            ->acceptJson()
            ->withHeaders(['x-api-key' => $apiKey])
            ->withQueryParameters($query)
            ->send(strtoupper($method), $path, ['json' => $payload]);
    }
}

