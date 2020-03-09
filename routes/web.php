<?php

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

// Grupo de rotas da API
$router->group(['prefix' => 'api'], function () use ($router) {
    // Rota "/api/login
    $router->post('login', 'AuthController@login');
});

// Grupo de rotas da API
$router->group(['prefix' => 'api', 'middleware' => 'jwt.auth'], function() use ($router) {
    // Rota "/api/register
    $router->post('register', 'AuthController@register');

    // Rota "/api/profile
    $router->get('profile', 'UsuarioController@profile');
});