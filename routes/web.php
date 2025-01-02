<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return response()->json([
        'Application' => 'Backend API',
        'Author' => 'Ilham Afandi',
        'Accessed Date' => now(),
    ]);
});