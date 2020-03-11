<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Obtém um JWT por meio de credenciais fornecidas.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        //validar solicitação recebida
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Não autorizado!'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Cadastrar um novo usuário.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
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
           
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->imagem = $request->input('imagem');
            $user->status = true;
            $user->perfil_id = $request->input('perfil_id');

            $user->save();

            return response()->json(['user' => $user, 'message' => 'Usuário cadastrado!'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Cadastro não efetuado!'], 409);
        }

    }

    /**
     * Obtém o usuário autenticado.
     *
     * @return Response
     */
    public function profile()
    {
        try {
            $usuarioLogado = Auth::user();
            $usuarioLogado->perfil = $usuarioLogado->perfil()->get();

            return response()->json(['user' => $usuarioLogado], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Não foi possível retornar os dados!'], 409);
        }
    }
}