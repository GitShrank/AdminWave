<?php

use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
Route::get('/log-test', function () {
    Log::info('This is a test log entry.');
    return 'Log entry has been created.';
});

Route::get('/db-test', function () {
    try {
        \DB::connection()->getPdo();
        return 'Database connection is OK';
    } catch (\Exception $e) {
        return 'Could not connect to the database. Please check your configuration.';
    }
});