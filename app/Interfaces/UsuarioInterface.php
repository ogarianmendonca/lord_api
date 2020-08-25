<?php

namespace App\Interfaces;

use App\Entities\User;
use Illuminate\Support\Collection;

interface UsuarioInterface
{
    /**
     * @return Collection<User>
     */
    public function buscarUsuarios(): Collection;

    /**
     * @param $id
     * @return User
     */
    public function buscarUsuarioSelecionado($id): User;

    /**
     * @param $params
     * @return User
     */
    public function criarUsuarioAplicacao($params): User;

    /**
     * @param $params
     * @return User
     */
    public function criarUsuarioMobile($params): User;

    /**
     * @param $id
     * @param $dados
     * @return User
     */
    public function editarUsuario($id, $dados): User;

    /**
     * @param $id
     * @return bool
     */
    public function alterarStatusUsuario($id): bool;

    /**
     * @param $dadosArquivo
     * @return string
     */
    public function upload($dadosArquivo): string;
}
