<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PanelApiController;
use App\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/panel');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:panel-login')->name('login.post');
});

Route::middleware('panel.auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/panel', [PanelController::class, 'index'])->name('panel.index');

    Route::prefix('/panel/api')->group(function (): void {
        Route::get('/apps', [PanelApiController::class, 'apps']);
        Route::post('/apps', [PanelApiController::class, 'storeApp']);
        Route::put('/apps/{appId}/blast-settings', [PanelApiController::class, 'updateBlastSetting']);
        Route::put('/apps/{appId}/webhook-settings', [PanelApiController::class, 'updateWebhookSetting']);

        Route::get('/sessions', [PanelApiController::class, 'sessions']);
        Route::post('/sessions', [PanelApiController::class, 'createSession']);
        Route::post('/sessions/{sessionId}/reconnect', [PanelApiController::class, 'reconnect']);
        Route::post('/sessions/{sessionId}/disconnect', [PanelApiController::class, 'disconnect']);
        Route::delete('/sessions/{sessionId}', [PanelApiController::class, 'deleteSession']);
        Route::put('/sessions/{sessionId}', [PanelApiController::class, 'updateSession']);
        Route::get('/sessions/{sessionId}/token', [PanelApiController::class, 'sessionToken']);
        Route::get('/sessions/{sessionId}/qr', [PanelApiController::class, 'sessionQr']);

        Route::get('/phonebook', [PanelApiController::class, 'phonebook']);
        Route::post('/phonebook', [PanelApiController::class, 'storePhonebook']);
        Route::delete('/phonebook/{id}', [PanelApiController::class, 'deletePhonebook']);
        Route::get('/groups', [PanelApiController::class, 'groups']);

        Route::get('/messages', [PanelApiController::class, 'messages']);
        Route::delete('/messages/{id}', [PanelApiController::class, 'deleteMessage']);
        Route::post('/messages/delete-bulk', [PanelApiController::class, 'deleteMessageBulk']);
        Route::delete('/messages', [PanelApiController::class, 'deleteAllMessages']);
        Route::get('/messages/export.csv', [PanelApiController::class, 'exportMessages']);

        Route::post('/send-bulk', [PanelApiController::class, 'sendBulk']);
    });
});
