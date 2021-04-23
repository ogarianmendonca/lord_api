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

        return $usuarioLogado;
    }

    /**
     * Verificação de permissão para usuários com perfil ADMINISTRADOR ou COORDENADOR
     *
     * @param $request
     * @param null $id
     */
    public function verificarAutorizacao($request, $id = null)
    {
        if ($request->method() === "POST") {
            if ($this->usuarioLogado()->perfil[0]->descricao !== 'ADMINISTRADOR' &&
               $this->usuarioLogado()->perfil[0]->descricao !== 'COORDENADOR') {
                return response()
                    ->json(['Usuário não tem permissão para esta ação!'], 403)
                    ->throwResponse();
            }
        }

        if ($request->method() === "PUT" && intval($id) !== $this->usuarioLogado()->id) {
            if ($this->usuarioLogado()->perfil[0]->descricao !== 'ADMINISTRADOR' &&
               $this->usuarioLogado()->perfil[0]->descricao !== 'COORDENADOR') {
                return response()
                    ->json(['Usuário não tem permissão para esta ação!'], 403)
                    ->throwResponse();
            }
        }

        if ($request->method() === "DELETE" && intval($id) !== $this->usuarioLogado()->id) {
            if ($this->usuarioLogado()->perfil[0]->descricao !== 'ADMINISTRADOR' &&
                $this->usuarioLogado()->perfil[0]->descricao !== 'COORDENADOR') {
                return response()
                    ->json(['Usuário não tem permissão para esta ação!'], 403)
                    ->throwResponse();
            }
        }
    }
}
