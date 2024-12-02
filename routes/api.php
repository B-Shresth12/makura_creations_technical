<?php

use App\Http\Middleware\AuthenticationMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('api')->namespace('App\Http\Controllers\Api')->group(function () {
    Route::post('/shorten-link', 'UrlController@createShortenLink');
    Route::get('/redirect/{shortCode}', 'UrlController@redirect');

    //Auth api
    Route::post('login', 'Auth\AuthController@login');
    Route::post('logout', 'Auth\AuthController@logout');

    Route::namespace('Admin')->prefix('admin')->middleware(AuthenticationMiddleware::class)->group(function () {
        Route::prefix('shorten-url')->controller('UrlController')->group(function () {
            Route::get('/', 'UrlController@index');
            Route::delete('/delete/{shortCode}', 'UrlController@destroy');
            Route::get('show/{shortCode}', 'UrlController@show');
        });
        Route::post("/lookup", "UrlController@lookUp");
        Route::post('/search-short-code', 'UrlController@search');
    });
});
