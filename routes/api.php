<?php

use App\Http\Controllers\LineController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::any('/', function (Request $request) {
    Log::info(['api.php', 'GET', '/']);
    return 'ok';
});

Route::post('/webhook', [LineController::class, 'post']);
