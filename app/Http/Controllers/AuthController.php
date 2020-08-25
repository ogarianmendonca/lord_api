<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\JWTAuth;
use App\Services\AutorizacaoService;
use App\Interfaces\UsuarioInterface;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @var AutorizacaoService
     */
    private AutorizacaoService $autorizacaoService;

    /**
     * @var JWTAuth
     */
    private JWTAuth $jwtAuth;

    /**
     * @var UsuarioInterface
     */
    private UsuarioInterface $usuarioRepository;

    /**
     * AuthController constructor.
     * @param JWTAuth $jwtAuth
     * @param AutorizacaoService $autorizacaoService
     * @param UsuarioInterface $usuarioRepository
     */
    public function __construct(
        JWTAuth $jwtAuth,
        AutorizacaoService $autorizacaoService,
        UsuarioInterface $usuarioRepository
    ) {
        $this->jwtAuth = $jwtAuth;
        $this->autorizacaoService = $autorizacaoService;
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * Obtém um JWT por meio de credenciais fornecidas.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        //valida solicitação recebida
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Não autorizado!'], 401);
        }

        //verifica se o usuário está ativo
        $user = Auth::user();
        if ($user['status'] === 0 ||
            $user['status'] === '0' ||
            $user['status'] === 'false' ||
            $user['status'] === false) {
            return response()->json(['message' => 'Usuário inativo!'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Cadastrar um novo usuário.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function cadastrar(Request $request): JsonResponse
    {
        $this->autorizacaoService->verificarAutorizacao($request);

        //valida solicitação recebida
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:user',
            'perfil_id' => 'required|integer',
        ]);

        try {
            $usuario = $this->usuarioRepository->criarUsuarioAplicacao($request);

            return response()->json(['usuario' => $usuario, 'message' => 'Usuário cadastrado!'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Cadastro não efetuado!'], $e->getCode());
        }
    }

    /**
     * Obtém o usuário autenticado.
     *
     * @return JsonResponse
     */
    public function getUser(): JsonResponse
    {
        try {
            $usuarioLogado = Auth::user();
            $usuarioLogado->perfil = $usuarioLogado['perfil']->get();

            return response()->json(['usuario' => $usuarioLogado], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Não foi possível retornar os dados!'], $e->getCode());
        }
    }

    /**
     * Metodo de logout
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $token = $this->jwtAuth->getToken();
        $this->jwtAuth->invalidate($token);

        return response()->json(['logout']);
    }

    /**
     * Cadastrar usuário do aplicativo mobile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function criarUsuarioMobile(Request $request): JsonResponse
    {
        try {
            $usuario = $this->usuarioRepository->criarUsuarioMobile($request);

            return response()->json(['usuario' => $usuario, 'message' => 'Usuário cadastrado!'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Cadastro não efetuado!'], $e->getCode());
        }
    }
}
