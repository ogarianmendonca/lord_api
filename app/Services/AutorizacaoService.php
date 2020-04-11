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
     * Retorna perfil do usuário logado
     * @return mixed
     */
    private function usuarioLogado()
    {
        $usuarioLogado = Auth::user();
        $usuarioLogado->perfil = $usuarioLogado->perfil()->get();

        return $usuarioLogado->perfil[0]->descricao;
    }

    /**
     * Verificação de permissão para usuários com perfil ADMINISTRADOR ou COORDENADOR
     * @param $metodoRequisicao
     */
    public function verificarAutorizacao($metodoRequisicao)
    {
        if($metodoRequisicao === "POST" || $metodoRequisicao === "PUT"){
            if($this->usuarioLogado() !== 'ADMINISTRADOR' && $this->usuarioLogado() !== 'COORDENADOR') {
                return response()
                    ->json(['Usuário não tem permissão para essa ação!'], 403)
                    ->throwResponse();
            }
        }
    }

}
