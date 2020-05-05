<?php

namespace App\Services;

use App\Entities\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Class UsuarioService
 * @package App\Services
 */
class UsuarioService
{
    /**
     * @var User
     */
    private $usuario;

    /**
     * UsuarioService constructor.
     * @param User $usuario
     */
    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Busca na base todos os usuários cadastrados
     *
     * @return User[]|Builder[]|Collection
     */
    public function buscaUsuarios()
    {
        return $this->usuario->with('perfil')->get();
    }

    /**
     * Busca na base o usuário por id
     *
     * @param $id
     * @return User|User[]|Builder|Builder[]|Collection|Model
     */
    public function buscaUsuarioSelecionado($id)
    {
        return $this->usuario->with('perfil')->findOrFail($id);
    }

    /**
     * Edita usuário por id
     *
     * @param $id
     * @param $dados
     * @return JsonResponse
     * @throws Exception
     */
    public function editarUsuario($id, $dados)
    {
        $usuario = $this->usuario->findOrFail(intval($id));

        if($usuario) {
            DB::beginTransaction();

            $editaUsuario['name'] = $dados['name'];
            $editaUsuario['email'] = $dados['email'];
            $editaUsuario['perfil_id'] = intval($dados['perfil_id']);
            $editaUsuario['status'] = intval($dados['status']);

            if(!empty($dados['imagem'])){
                $editaUsuario['imagem'] = $dados['imagem'];
            }

            if(!empty($dados['password'])){
                $editaUsuario['password'] = app('hash')->make($dados['password']);
            } else {
                unset($editaUsuario['password']);
            }

            $this->usuario->find($id)->update($editaUsuario);

            DB::commit();
            return $editaUsuario;
        } else {
            throw new Exception();
        }
    }

    /**
     * Altera status de usuario selecionado
     *
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function alterarStatusUsuario($id)
    {
        $usuario = $this->usuario->with('perfil')->find($id);

        if($usuario) {
            DB::beginTransaction();

            if($usuario['status']){
                $novoStatus = false;
            } else {
                $novoStatus = true;
            }

            $alterarDado['status'] =  $novoStatus;
            $this->usuario->find($id)->update($alterarDado);

            DB::commit();
            return $alterarDado['status'];
        } else {
            throw new Exception();
        }
    }

    /**
     * Upload da imagem do usuário
     */
    public function upload ($dadosArquivo)
    {
        if($dadosArquivo->hasFile('imagem')) {
            $imagem = $dadosArquivo->file('imagem');

            $ext = $imagem->guessClientExtension();
            $diretorio = "img/uploads/perfil/";
            $nomeImg = $diretorio . 'imagem_perfil_' . rand(11111,99999) . '.' . $ext;
            $imagem->move($diretorio, $nomeImg);

            return $nomeImg;
        } else {
            throw new Exception();
        }
    }

}