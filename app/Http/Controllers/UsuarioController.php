<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use  App\User;
use App\Services\UsuarioService;

class UsuarioController extends Controller
{
    private $service;

    /**
     * Nova instância do UsuarioController.
     *
     * @return void
     */
    public function __construct(UsuarioService $service)
    {
        $this->middleware('auth');
        $this->service = $service;
    }

    /**
     * Lista todos os usuários cadastrados
     * 
     * @return Response
     */
    public function usuarios()
    {
        try {
            $usuarios = $this->service->buscaUsuarios();
            return response()->json(compact('usuarios'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Listagem de usuários não disponível'], 409);
        }
    }
}