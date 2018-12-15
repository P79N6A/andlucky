<?php

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

/**
 * 以下是需要登录 的数据 接口
 */
Route::group( ['middleware' => [ 'auth:api']  ] , function( $route ) {
    
});