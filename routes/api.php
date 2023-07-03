<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api_mobile\Auth\AuthContoller;
use App\Http\Controllers\Api_mobile\Auth\ShopContoller;
// Assma  => make here the api of api
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'App\Http\Controllers\Api_mobile\Auth\AuthContoller@login');
Route::post('register', 'App\Http\Controllers\Api_mobile\Auth\AuthContoller@register');

Route::group([

    'middleware' => ['auth:api'],
    'namespace'=>'App\Http\Controllers\Api_mobile\Auth'
], function ($router) {
//authication user
    Route::post('updateProfile', 'AuthContoller@updateProfile');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

    //shops
    Route::get('detailshop', 'ShopContoller@detailshop');
    Route::get('listallshop', 'ShopContoller@listallshop');
    Route::get('listallzone', 'ShopContoller@listallzone');
    Route::post('addshop', 'ShopContoller@addshop');
    Route::post('updateShop/{id}', 'ShopContoller@updateShop');


});
