<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $users = App\Models\User::take(6)->with('position')->get();
    return view('demonstration',compact('users'));
});
