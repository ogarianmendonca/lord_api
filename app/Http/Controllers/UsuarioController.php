<?php

namespace App\Http\Controllers;

use App\Services\AutorizacaoService;
use Exception;
use App\Services\UsuarioService;
use Illuminate\Http\Request;

/**
 * Class UsuarioController
 * @package App\Http\Controllers
 */
class UsuarioController extends Controller
{
    /**
     * @var UsuarioService
     */
    private $service;

    /**
     * @var AutorizacaoService
     */
    private $autorizacaoService;

    /**
     * UsuarioController constructor.
     * @param UsuarioService $service
     */
    public function __construct(UsuarioService $service, AutorizacaoService $autorizacaoService)
    {
        $this->middleware('auth');
        $this->service = $service;
        $this->autorizacaoService = $autorizacaoService;
    }

    /**
     * Lista todos os usuários cadastrados
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarTodos()
    {
        try {
            $usuarios = $this->service->buscaUsuarios();
            return response()->json(compact('usuarios'));
        } catch (Exception $e) {
            return response()->json(['message' => 'Listagem de usuários não disponível!'], 409);
        }
    }

    /**
     * Busca usuário selecionado
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function visualizar($id)
    {
        try {
            $usuario = $this->service->buscaUsuarioSelecionado($id);
            return response()->json(compact('usuario'));
        } catch (Exception $e) {
            return response()->json(['message' => 'Usuário não encontrado!'], 409);
        }
    }

    /**
     * Edita usuário selecionado
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editar($id, Request $request)
    {
//        $this->autorizacaoService->verificarPerfilAdministrador();

        try {
            $retorno = $this->service->editarUsuario($id, $request->all());
            return response()->json(['message' => 'Usuário editado!', 'usuario' => $retorno]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Usuário não editado!'], 409);
        }
    }

    /**
     * Alterar status de usuário selecionado
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function alterarStatus($id)
    {
        try {
            $retorno = $this->service->alterarStatusUsuario($id);
            return response()->json(['message' => 'Status alterado!', 'status' => $retorno]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Status não alterado!'], 409);
        }
    }

}
