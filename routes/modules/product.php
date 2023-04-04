<?php

$router->group(['prefix' => 'product'], function() use ($router) {
    $router->post('/create', 'ProductController@create');
});