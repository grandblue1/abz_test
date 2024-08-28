<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('demonstration');
});
Route::get('/form', function ( ) {
    return view('form_submissions');
});
