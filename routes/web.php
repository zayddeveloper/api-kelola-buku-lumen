<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->get('/', function () use ($router){
        return response()->json([
            'app_name' => env('APP_NAME'),
            'version' => env('APP_VERSION')
        ], 200);
    });

    // users routing
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('/login', 'UsersController@login');
        $router->post('/register', 'UsersController@store');
        $router->get('/(id)', 'UsersController@getByID');
    });

    // books routing
    $router->group(['prefix' => 'books'], function () use ($router) {
        $router->get('/', 'BooksController@index');
        $router->post('/', 'BooksController@store');
        $router->get('/{id}', 'BooksController@getByID');
        $router->post('/{id}', 'BooksController@getByID');
        $router->delete('/{id}', 'BooksController@getByID');
        $router->delete('/', 'BooksController@deleteAll');
    });

});
