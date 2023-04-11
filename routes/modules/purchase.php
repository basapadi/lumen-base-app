<?php

$router->group(['prefix' => 'purchase'], function() use ($router) {
    $router->post('/create', 'PurchaseController@create');
    $router->get('/list', 'PurchaseController@list');
});