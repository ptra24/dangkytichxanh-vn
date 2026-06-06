<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/swagger', function () {
    return view('swagger');
});

Route::get('{any}', function () {
    return view('welcome');
})->where('any', '^(?!api|swagger).*$');

