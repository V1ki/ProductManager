<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('orders',OrderController::class);
    $router->resource('devices',DeviceController::class);
    $router->resource('devModels',DevModelController::class);

    $router->resource('shAdmins',SHAdminController::class);
});
