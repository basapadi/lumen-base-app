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
            $router->post('/auth/login', 'AuthController@login');
            $router->post('/auth/register', 'AuthController@register');
            $router->get('/auth/register/verify', 'AuthController@verify');
            $router->group(['middleware' => ['auth', 'auth.header']], function() use ($router) {
                $router->group(['prefix' => 'auth'], function() use ($router) {
                    $router->post('logout', 'AuthController@logout');

                    /**
                     * Write here for authorized routes
                     */
                    
                });
            });

            /**
             * Testing
             */
            include_once 'modules/test.php';
    });
});
