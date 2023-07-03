<?php

use App\Http\Controllers\Api_Dashboard\Auth\AuthContoller;
use App\Http\Controllers\Api_Dashboard\DelegateController;
use App\Http\Controllers\Api_Dashboard\ShopController;
use App\Http\Controllers\Api_Dashboard\StateController;
use App\Http\Controllers\Api_Dashboard\ZoneController;
use App\Http\Controllers\Api_mobile\Auth\ShopContoller;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    return response('this is content');
});

Route::post('login', [AuthContoller::class,'login']);

Route::middleware('auth:admin')->group(function () {
    Route::post('logout', [AuthContoller::class,'logout']);
    Route::post('refresh', [AuthContoller::class,'refresh']);

    /* ----------------------------- routes of states ---------------------------- */
    Route::controller(StateController::class)->prefix('states')->group(function () {
        Route::get('get_all_states', 'get_all_states');
        Route::get('get_state_with_zones/{id}', 'get_state_with_zones');
    });

    /* ----------------------------- routes of zones ---------------------------- */

    Route::controller(ZoneController::class)->prefix('zones')->group(function () {
        Route::get('get_all_zones', 'get_all_zones');
        Route::get('get_zone/{id}', 'get_zone');
        Route::get('get_zone_with_delegates/{id}', 'get_zone_with_delegates');
        Route::get('get_zone_with_shops/{id}', 'get_zone_with_shops');

        Route::post('store', 'store');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'delete');
        Route::post('update_toggle_status/{id}', 'update_toggle_status');
    });

    /* ----------------------------- routes of delegates ---------------------------- */


    Route::controller(DelegateController::class)->prefix('delegates')->group(function () {
        Route::get('get_all_delegates', 'get_all_delegates');
        Route::get('get_delegate/{id}', 'get_delegate');

        Route::post('store', 'store');
        Route::post('update/{id}', 'update');
        Route::post('change_password/{id}', 'change_password');
        Route::post('delete/{id}', 'delete');
        Route::post('update_toggle_status/{id}', 'update_toggle_status');
    });

    /* ----------------------------- routes of Shops ---------------------------- */

    Route::controller(ShopController::class)->prefix('shops')->group(function () {
        Route::get('get_all_shops', 'get_all_shops');
        Route::get('get_shop/{id}', 'get_shop');
        Route::get('get_delegates_for_assign', 'get_delegates_for_assign');
        Route::post('store', 'store');
        Route::post('update/{id}', 'update');
        Route::post('delete/{id}', 'delete');
        Route::post('update_toggle_status/{id}', 'update_toggle_status');
        Route::post('make_approved/{id}', 'make_approved');
        Route::post('make_assign_with_delegate/{id}', 'make_assign_with_delegate');
    });

});
