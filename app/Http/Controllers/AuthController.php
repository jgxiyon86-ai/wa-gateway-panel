<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
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

        return redirect()->route('panel.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->flush();

        return redirect()->route('login');
    }
}

