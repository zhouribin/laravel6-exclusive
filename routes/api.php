<?php

use \Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

/**
 * api接口
 */
Route::namespace('Api')->group(function () {

    /**
     * 需要签名的API
     */
    Route::middleware('apiSign')->group(function () {

    });

    /**
     * 不需要签名的API
     */
});
