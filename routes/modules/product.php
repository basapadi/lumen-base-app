<?php

$router->group(['prefix' => 'product'], function() use ($router) {
    $router->post('/create', 'ProductController@create');
    $router->post('/edit', 'ProductController@edit');
    $router->get('/{id}',  'ProductController@detail');
    $router->delete('/hapus/{id}',  'ProductController@hapus');
});
$router->get('products',  'ProductController@list');