<?php

$router->group(['prefix' => 'test'], function() use ($router) {
    $router->get('/terbilang', 'TestController@bilangan');
});