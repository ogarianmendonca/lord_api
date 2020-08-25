<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Entities\User;
use App\Interfaces\UsuarioInterface;

class UsuarioRepository implements UsuarioInterface
{
    /**
     * @var User
     */
    private User $usuario;

    /**
     * UsuarioRepository constructor.
     * @param User $usuario
     */
    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Busca na base todos os usuários cadastrados
     *
     * @return Collection<User>
     */
    public function buscarUsuarios(): Collection
    {
        return $this->usuario->with('perfil')->orderBy('name')->get();
    }

    /**
     * Busca na base o usuário por id
     *
     * @param $id
     * @return User
     */
    public function buscarUsuarioSelecionado($id): User
    {
        return $this->usuario->with('perfil')->findOrFail($id);
    }

    /**
     * Cadastra usuários da aplicação web
     *
     * @param $params
     * @return User
     */
    public function criarUsuarioAplicacao($params): User
    {
        $usuario = new User;
        $usuario->name = $params->input('name');
        $usuario->email = $params->input('email');
        $usuario->imagem = $params->input('imagem') == null ? 'sem_imagem' : $params->input('imagem');
        $usuario->status = true;
        $usuario->perfil_id = intval($params->input('perfil_id'));
        $plainPassword = intval($params->input('password'));
        $usuario->password = app('hash')->make($plainPassword);

        $usuario->save();
        return $usuario;
    }

    /**
     * Cadastra usuários do aplicativo móvel
     *
     * @param $params
     * @return User
     */
    public function criarUsuarioMobile($params): User
    {
        $usuario = new User;
        $usuario->name = $params->input('name');
        $usuario->email = $params->input('email');
        $usuario->imagem = 'sem_imagem';
        $usuario->status = true;
        $usuario->perfil_id = 3;
        $plainPassword = intval($params->input('password'));
        $usuario->password = app('hash')->make($plainPassword);

        $usuario->save();
        return $usuario;
    }

    /**
     * Edita usuário
     *
     * @param $id
     * @param $dados
     * @return User
     * @throws Exception
     */
    public function editarUsuario($id, $dados): User
    {
        $usuario = $this->usuario->findOrFail(intval($id));

        if ($usuario) {
            $usuario = User::find($id);
            $usuario->name = $dados['name'];
            $usuario->email = $dados['email'];
            $usuario->perfil_id = intval($dados['perfil_id']);

            if ($dados['status'] === 'true'
                || $dados['status'] === '1'
                || $dados['status'] === true
                || $dados['status'] === 1) {
                $usuario->status = true;
            } else {
                $usuario->status = false;
            }

            if (!empty($dados['imagem'])) {
                $usuario->imagem = $dados['imagem'];
            }

            if (!empty($dados['password'])) {
                $usuario->password = app('hash')->make($dados['password']);
            } else {
                unset($usuario->password);
            }

            $usuario->save();
            return $usuario;
        } else {
            throw new Exception();
        }
    }

    /**
     * Altera status de usuario
     *
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function alterarStatusUsuario($id): bool
    {
        $usuario = $this->usuario->with('perfil')->find($id);

        if ($usuario) {
            $dadosUsuario = User::find($id);

            if ($usuario['status']) {
                $novoStatus = false;
            } else {
                $novoStatus = true;
            }

            $dadosUsuario->status =  $novoStatus;
            $dadosUsuario->save();

            return $dadosUsuario->status;
        } else {
            throw new Exception();
        }
    }

    /**
     * Upload da imagem do usuário
     *
     * @param $dadosArquivo
     * @return string
     * @throws Exception
     */
    public function upload($dadosArquivo): string
    {
        // Salva a imagem dentro do diretorio do back-end
        // if($dadosArquivo->hasFile('imagem')) {
        //     $imagem = $dadosArquivo->file('imagem');

        //     $ext = $imagem->guessClientExtension();
        //     $diretorio = "img/uploads/perfil/";
        //     $nomeImg = $diretorio . 'imagem_perfil_' . rand(11111,99999) . '.' . $ext;
        //     $imagem->move($diretorio, $nomeImg);

        //     return $nomeImg;
        // } else {
        //     throw new Exception();
        // }

        // Salva a imagem no banco de dados no formato base64
        if ($dadosArquivo->hasFile('imagem')) {
            $imagem = $dadosArquivo->file('imagem');
            $ext = $imagem->guessClientExtension();
            $data = file_get_contents($imagem);
            return 'data:image/' . $ext . ';base64,' . base64_encode($data);
        } else {
            throw new Exception();
        }
    }
}
