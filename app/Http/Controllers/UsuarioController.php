<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Interfaces\UsuarioInterface;
use App\Services\AutorizacaoService;

/**
 * Class UsuarioController
 * @package App\Http\Controllers
 */
class UsuarioController extends Controller
{
    /**
     * @var AutorizacaoService
     */
    private $autorizacaoService;

    /**
     * @var UsuarioInterface
     */
    private $usuarioRepository;

    /**
     * UsuarioController constructor.
     * @param AutorizacaoService $autorizacaoService
     * @param UsuarioInterface $usuarioRepository
     */
    public function __construct(
        AutorizacaoService $autorizacaoService,
        UsuarioInterface $usuarioRepository
    ) {
        $this->middleware('auth');
        $this->autorizacaoService = $autorizacaoService;
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * Lista todos os usuários cadastrados
     *
     * @return JsonResponse
     */
    public function buscarUsuarios()
    {
        try {
            $usuarios = $this->usuarioRepository->buscarUsuarios();
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
            $usuario = $this->usuarioRepository->buscarUsuarioSelecionado($id);
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
            $retorno = $this->usuarioRepository->editarUsuario($id, $request->all());
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
        $this->autorizacaoService->verificarAutorizacao($request, $id);

        try {
            $retorno = $this->usuarioRepository->alterarStatusUsuario($id);
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
            $retorno = $this->usuarioRepository->upload($request);
            return response()->json(['message' => 'Imagem salva!', 'imagem' => $retorno]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao salvar imagem!'], 409);
        }
    }
}
