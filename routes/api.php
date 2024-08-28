<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PositionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/users', [UserController::class,'register']);
Route::middleware('withStatusCollection')->get('/users', UserController::class);
Route::middleware('withStatus:user')->get('/users/{id}', [UserController::class, 'getUser']);
Route::middleware('withStatus:positions')->get('/positions', PositionController::class);
Route::middleware('withStatus:token')->get('/token', [UserController::class,'generateToken']);

Route::get('/seeders', function () {
    \Illuminate\Support\Facades\Artisan::call('db:seed');
});
