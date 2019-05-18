<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Forum Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Forum routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your Forum!
|
*/

Route::middleware('auth:api')->group(function (Router $router) {
    $router->namespace('Forum\User')->group(function (Router $router) {
        $router->apiResource('user', 'UserController');
        $router->apiResource('session', 'SessionController');
    });

    $router->namespace('Forum\Forum')->group(function (Router $router) {
        $router->apiResource('board', 'BoardController');
        $router->apiResource('board/{boardId}/topic', 'TopicController');
    });
});

// 程序安装
Route::middleware('installation')->namespace('Installation')->prefix('/installation')->group(function (Router $router) {
    $router->apiResource('config', 'ConfigController');
    $router->apiResource('database', 'DatabaseController');
    $router->apiResource('setting', 'SettingController');
});
