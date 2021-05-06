<?php

use Illuminate\Support\Facades\Route;
use Junges\Pix\Http\Controllers\PixController;

Route::get('laravel-pix/pix/create', [PixController::class, 'create'])
    ->name('laravel-pix.qr-code.create')
    ->middleware(config('laravel-pix.create_qr_code_route_middleware'));
