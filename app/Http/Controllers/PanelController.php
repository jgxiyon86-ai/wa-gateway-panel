<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PanelController extends Controller
{
    public function index(): View
    {
        return view('panel.index', [
            'panelUser' => session('panel_user', 'admin'),
            'nodeBaseUrl' => config('wa_gateway.node_base_url'),
        ]);
    }
}

