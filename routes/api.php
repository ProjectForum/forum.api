<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

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

//Route::any('/graph/{action}', 'Graph\BaseGraphController@action')->where('action', '[A-Za-z0-9]+');

// 论坛
Route::middleware('auth:api')->prefix('/forum')->group(function (Router $router) {
    $router->any('/graph', 'Graph\ForumController@action');
    $router->get('/test', function () {
        return [auth()->id()];
    });
});

// 后台
Route::middleware([])->prefix('/admin')->group(function (Router $router) {
    $router->any('/graph', 'Graph\AdminController@action');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
