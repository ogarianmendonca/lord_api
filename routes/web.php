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

// Grupo de rotas AUTH
$router->group(['prefix' => 'auth'], function () use ($router) {
    // Rota "/auth/login"
    $router->post('login', 'AuthController@login');

    // Rota "/auth/criar-usuario-mobile"
    $router->post('criar-usuario-mobile', 'AuthController@criarUsuarioMobile');

    // Rota "/auth/login"
    $router->get('logout', 'AuthController@logout');
});

// Grupo de rotas da API
$router->group(['prefix' => 'api', 'middleware' => 'jwt.auth'], function () use ($router) {
    // Grupo de rotas do USUARIO
    $router->group(['prefix' => 'usuario'], function () use ($router) {
        // Rota "/api/usuario/cadastrar"
        $router->post('cadastrar', 'AuthController@cadastrar');

        // Rota "/api/usuario/get-user"
        $router->get('get-user', 'AuthController@getUser');

        //Rota "/api/usuario/buscar-usuarios"
        $router->get('buscar-usuarios', 'UsuarioController@buscarUsuarios');

        //Rota "/api/usuario/visualizar-usuario/id
        $router->get('visualizar-usuario/{id}', 'UsuarioController@visualizarUsuario');

        //Rota "/api/usuario/editar/id"
        $router->put('editar/{id}', 'UsuarioController@editar');

        //Rota "/api/usuario/alterar-status/id"
        $router->put('alterar-status/{id}', 'UsuarioController@alterarStatus');

        //Rota "/api/usuario/excluir-perfil/id"
        $router->delete('excluir-perfil/{id}', 'UsuarioController@excluirPerfil');

        //Rota "/api/usuario/upload"
        $router->post('upload', 'UsuarioController@upload');
    });

    // Grupo de rotas do PEFIL
    $router->group(['prefix' => 'perfil'], function () use ($router) {
        //Rota "/api/perfil/buscar-perfis"
        $router->get('buscar-perfis', 'PerfilController@buscarPerfis');
    });
});
