<?php

namespace App\Interfaces;

interface UsuarioInterface 
{
    public function buscarUsuarios();

    public function buscarUsuarioSelecionado($id);

    public function criarUsuarioAplicacao($params);

    public function criarUsuarioMobile($params);

    public function editarUsuario($id, $dados);

    public function alterarStatusUsuario($id);

    public function upload($dadosArquivo);
}