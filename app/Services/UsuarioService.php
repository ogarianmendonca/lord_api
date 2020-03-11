<?php

namespace App\Services;

use App\Entities\User;
use Illuminate\Support\Facades\DB;

class UsuarioService
{
    private $usuario;

    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Busca na base todos os usuários cadastrados
     */
    public function buscaUsuarios()
    {
        $usuarios = $this->usuario->with('perfil')->get();
        return $usuarios;
    }

    /**
     * Busca na base o usuário por id
     */
    public function buscaUsuarioSelecionado($id)
    {
        $usuario = $this->usuario->with('perfil')->findOrFail($id);
        return $usuario;
    }

    /**
     * Edita usuário por id
     */
    public function editarUsuario($id, $dados)
    {
        $usuario = $this->usuario->with('perfil')->findOrFail($id);
        
        if($usuario) {
            DB::beginTransaction();
            
            $editaUsuario['name'] = $dados['name'];
            $editaUsuario['email'] = $dados['email']; 
            $editaUsuario['perfil_id'] = $dados['perfil_id']; 
            $editaUsuario['status'] = $dados['status']; 
          
            if(!empty($dados['imagem'])){
                $editaUsuario['imagem'] = $dados['imagem']; 
            }

            if(!empty($dados['senha'])){
                $editaUsuario['password'] = app('hash')->make($dados['password']);
            } else {
                unset($editaUsuario['password']);
            }

            $this->usuario->find($id)->update($editaUsuario);

            DB::commit();
            return response()->json(['message' => 'Usuário editado!']); 
        } else {            
            return response()->json(['message' => 'Usuário não editado!'], 409);
        }
    }

    /**
     * Altera statuso de usuario selecionado
     */
    public function alterarStatususuario($id)
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
            return response()->json(['message' => 'Status alterado!']); 
        } else {            
            return response()->json(['message' => 'Status não alterado!'], 409);
        }
    }

}