<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('api')->namespace('App\Http\Controllers\Api')->group(function(){
    Route::post('/shorten-link', 'UrlController@createShortenLink');
});
