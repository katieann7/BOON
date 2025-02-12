<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/schedule-config', function () {
    return view('deliveries.scheduleConfig');
});

Route::get('/order', function () {
    return view('deliveries.order');
});

Route::get('/', function () {
    return view('auth.login');
});
