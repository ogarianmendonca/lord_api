<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Entities\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * Obtém um JWT por meio de credenciais fornecidas.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        //validar solicitação recebida
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Não autorizado!'], 401);
        }

        $user = Auth::user();
        if($user['status'] === 0 || $user['status'] === '0'){
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
        //validar solicitação recebida
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:user',
            'password' => 'required|confirmed',
            'imagem' => 'required|string',
            'perfil_id' => 'required|integer',
        ]);

        try {
            $usuario = new User;
            $usuario->name = $request->input('name');
            $usuario->email = $request->input('email');
            $plainPassword = $request->input('password');
            $usuario->password = app('hash')->make($plainPassword);
            $usuario->imagem = $request->input('imagem');
            $usuario->status = true;
            $usuario->perfil_id = $request->input('perfil_id');

            $usuario->save();

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
    public function perfil()
    {
        try {
            $usuarioLogado = Auth::user();
            $usuarioLogado->perfil = $usuarioLogado->perfil()->get();

            return response()->json(['usuario' => $usuarioLogado], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Não foi possível retornar os dados!'], 409);
        }
    }
}
