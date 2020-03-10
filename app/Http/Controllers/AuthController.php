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
            return response()->json(['message' => 'Unauthorized'], 401);
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
        ]);

        try {
           
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->imagem = $request->input('imagem');
            $user->status = $request->input('status');
            $user->perfil_id = $request->input('perfil_id');

            $user->save();

            //retornar resposta bem sucedida
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //retornar mensagem de erro
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }

    /**
     * Obtém o usuário autenticado.
     *
     * @return Response
     */
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }
}