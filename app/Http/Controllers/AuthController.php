<?php

namespace App\Http\Controllers;

use App\Services\WaGatewayApi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private readonly WaGatewayApi $waGatewayApi)
    {
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (
            $data['username'] !== config('wa_gateway.panel_user') ||
            $data['password'] !== config('wa_gateway.panel_password')
        ) {
            return back()->withErrors(['username' => 'Username/password salah.'])->withInput();
        }

        session([
            'panel_auth' => true,
            'panel_user' => $data['username'],
        ]);
        $request->session()->regenerate();

        return redirect()->route('panel.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->waGatewayApi->logoutNodeAdmin((string) $request->session()->get('wa_node_admin_token', ''));
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
