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
        $router->resource('user', 'UserController');
        $router->resource('session', 'SessionController');
    });

    $router->namespace('Forum\Forum')->group(function (Router $router) {
        $router->resource('board', 'BoardController');
        $router->resource('board/{boardId}/topic', 'TopicController');

    });
});
