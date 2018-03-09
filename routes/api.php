<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return \App\DevModel::all();
});
// 所有商户
Route::get('/allSHInfos', 'Controller@allSHInfos');
/// 通过商户获取客户
Route::get('/customer', 'Controller@customer');
/// 通过客户获取产品类型
Route::get('/dev_model', 'Controller@dev_model');

/// 获取 设备型号 中的版本
Route::get('/model_versions', 'Controller@model_versions');
