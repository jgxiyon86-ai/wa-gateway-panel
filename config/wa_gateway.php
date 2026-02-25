<?php

return [
    'node_base_url' => env('WA_NODE_BASE_URL', 'http://127.0.0.1:3210'),
    'node_panel_user' => env('WA_NODE_PANEL_USER', 'admin'),
    'node_panel_password' => env('WA_NODE_PANEL_PASSWORD', 'admin123'),
    'node_timeout_seconds' => (int) env('WA_NODE_TIMEOUT_SECONDS', 20),
    'node_retry_times' => (int) env('WA_NODE_RETRY_TIMES', 1),
    'node_retry_sleep_ms' => (int) env('WA_NODE_RETRY_SLEEP_MS', 400),
    'panel_user' => env('WA_PANEL_USER', 'admin'),
    'panel_password' => env('WA_PANEL_PASSWORD', 'admin123'),
    'panel_login_rate_limit' => (int) env('WA_PANEL_LOGIN_RATE_LIMIT', 10),
];
