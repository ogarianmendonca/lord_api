<?php

namespace App\Http\Controllers;

use App\Services\AutorizacaoService;
use Exception;
use App\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
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
     * @param AutorizacaoService $autorizacaoService
     *
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
     * @return JsonResponse
     */
    public function buscarUsuarios()
    {
        try {
            $usuarios = $this->service->buscarUsuarios();
            return response()->json($usuarios);
        } catch (Exception $e) {
            return response()->json(['message' => 'Listagem não disponível!'], 409);
        }
    }

    /**
     * Busca usuário selecionado
     *
     * @param $id
     * @return JsonResponse
     */
    public function visualizarUsuario($id)
    {
        try {
            $usuario = $this->service->buscarUsuarioSelecionado($id);
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
     * @return JsonResponse
     */
    public function editar($id, Request $request)
    {
        $this->autorizacaoService->verificarAutorizacao($request, $id);

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
     * @return JsonResponse
     */
    public function alterarStatus($id, Request $request)
    {
        $this->autorizacaoService->verificarAutorizacao($request);

        try {
            $retorno = $this->service->alterarStatusUsuario($id);
            return response()->json(['message' => 'Status alterado!', 'status' => $retorno]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Status não alterado!'], 409);
        }
    }

    /**
     * Upload de imagem do usuário
     */
    public function upload(Request $request)
    {
        try {
            $retorno = $this->service->upload($request);
            return response()->json(['message' => 'Imagem salva!', 'imagem' => $retorno]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao salvar imagem!'], 409);
        }
    }
}
