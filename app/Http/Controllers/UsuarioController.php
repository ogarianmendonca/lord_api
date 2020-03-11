<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;
use App\Services\UsuarioService;
use Illuminate\Http\Request;

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
    public function buscarTodos()
    {
        try {
            $usuarios = $this->service->buscaUsuarios();
            return response()->json(compact('usuarios'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Listagem de usuários não disponível!'], 409);
        }
    }

    /**
     * Busca usuário selecionado
     */
    public function visualizar($id)
    {
        try {
            $usuario = $this->service->buscaUsuarioSelecionado($id);
            return response()->json(compact('usuario'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Usuário não encontrado!'], 409);
        }
    }

    /**
     * Edita usuário selecionado
     */
    public function editar($id, Request $request)
    {
        try {
            $retorno = $this->service->editarUsuario($id, $request->all());
            return $retorno;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Usuário não editado!'], 409);
        }
    }

    /**
     * Alterar status de usuário selecionado
     */
    public function alterarStatus($id)
    {
        try {
            $retorno = $this->service->alterarStatusUsuario($id);
            return $retorno;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Status não alterado!'], 409);
        }
    }
}