<?php

$router->group(['prefix' => 'test'], function() use ($router) {
    $router->get('/terbilang', 'TestController@bilangan');
    $router->post('/upload-image', 'TestController@uploadImage');
    $router->post('/upload-file', 'TestController@uploadFile');
    $router->get('/column-transformer', 'TestController@columnTransformer');
});