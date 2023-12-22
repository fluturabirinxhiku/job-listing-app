<?php

use App\Controllers\UserController;

$router->get('/', 'HomeController@index');
$router->get('/listings', 'ListingController@index');
$router->get('/listings/create', 'ListingController@create', ['auth']);
$router->get('/listings/{id}', 'ListingController@show');
$router->get('/listings/edit/{id}', 'ListingController@edit', ['auth']);

$router->put('/listings/{id}', 'ListingController@update', ['auth']);
$router->post('/listings', 'ListingController@store', ['auth']);
$router->delete('/listings/{id}', 'ListingController@delete', ['auth']);

$router->get('/register', 'UserController@register', ['guest']);
$router->get('/login', 'UserController@login', ['guest']);

$router->post('/register', 'UserController@store', ['guest']);
$router->post('/login', 'UserController@authenticate', ['guest']);
$router->post('/logout', 'UserController@logout', ['auth']);
