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

$router->group(['prefix' => 'api'], function() use ($router) {
    $router->group([
            'prefix' => 'v1', 
        ], function() use ($router) {
            $router->post('/login', 'AuthController@login');

            $router->group(['middleware' => ['auth', 'auth.header']], function() use ($router) {
                $router->group(['prefix' => 'auth'], function() use ($router) {
                    $router->post('logout', 'AuthController@logout');

                    
                    
                });
                $router->get('test', 'TestController@respon');
            });

            /**
             * Testing
             */
            include_once 'modules/test.php';
    });
});
