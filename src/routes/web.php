<?php

use Illuminate\Support\Facades\Route;
use vilija19\web3login\Http\Controllers\Web3AuthController;

Route::middleware(config('fortify.middleware', ['web']))->prefix('metamask')->group(function () {
    $limiter = config('fortify.limiters.metamask');

    Route::get('/ethereum/signature', [Web3AuthController::class, 'signature'])
        ->name('metamask.signature')
        ->middleware('guest:'.config('fortify.guard'));

    Route::post('/ethereum/authenticate', [Web3AuthController::class, 'authenticate'])
        ->middleware(array_filter([
            'guest:'.config('fortify.guard'),
            $limiter ? 'throttle:'.$limiter : null,
        ]))->name('metamask.authenticate');
});