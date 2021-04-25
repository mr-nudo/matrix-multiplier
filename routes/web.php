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
    return 'Welcome to the Matrix Multiplication API </br> Lumen Version : ' . $router->app->version();
});


$router->group(['domain' => 'api.' . env('APP_DOMAIN'), 'prefix' => 'v1'], function () use ($router) {
    $router->post('/signup', 'AuthController@signup');

    $router->group(['middleware' => ['auth']], function () use ($router) {

	    $router->get('users', 'ExampleController@test');
	});
});