<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('test', function () {
    return view('test');
});


Route::get('home', function () {
    return view('home');
});


Route::get('/header', function () {
    return view('components.header');
});