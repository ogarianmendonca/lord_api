<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

/**
 * Class AutorizacaoService
 * @package App\Services
 */
class AutorizacaoService
{
    /**
     * AutorizacaoService constructor.
     */
    public function __construct()
    {
    }

    /**
     * Verifica se usuário tem permissão administrador
     */
    public function verificarPerfilAdministrador()
    {
        $usuarioLogado = Auth::user();
        $usuarioLogado->perfil = $usuarioLogado->perfil()->get();

        if($usuarioLogado->perfil[0]->descricao !== 'ADMINISTRADOR') {
//            return abort(403, 'Usuário não tem permissão de administrador!');
//            return response()->json(['Usuário não tem permissão de administrador!'], 403);
//            throw new \Exception("dfasfdsaffasd", 403);
        }
    }

}
