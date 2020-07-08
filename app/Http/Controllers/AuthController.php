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
    private $autorizacaoService;

    /**
     * @var JWTAuth
     */
    private $jwtAuth;

    /**
     * @var UsuarioInterface
     */
    private $usuarioRepository;

    /**
     * AuthController constructor.
     */
    public function __construct(
        JWTAuth $jwtAuth,
        AutorizacaoService $autorizacaoService, 
        UsuarioInterface $usuarioRepository
    )
    {
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
    public function login(Request $request)
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
            $user['status'] === false){
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
    public function cadastrar(Request $request)
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
            return response()->json(['message' => 'Cadastro não efetuado!'], 409);
        }
    }

    /**
     * Obtém o usuário autenticado.
     *
     * @return JsonResponse
     */
    public function getUser()
    {
        try {
            $usuarioLogado = Auth::user();
            $usuarioLogado->perfil = $usuarioLogado['perfil']->get();

            return response()->json(['usuario' => $usuarioLogado], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Não foi possível retornar os dados!'], 409);
        }
    }

    /**
     *  Metodo de logout
     */
    public function logout()
    {
        $token = $this->jwtAuth->getToken();
        $this->jwtAuth->invalidate($token);

        return response()->json(['logout']);
    }

    /**
     * Cadastrar usuário do aplicativo mobile
     */
    public function criarUsuarioMobile(Request $request)
    {
        try {
            $usuario = $this->usuarioRepository->criarUsuarioMobile($request);

            return response()->json(['usuario' => $usuario, 'message' => 'Usuário cadastrado!'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Cadastro não efetuado!'], 409);
        }
    }
}
