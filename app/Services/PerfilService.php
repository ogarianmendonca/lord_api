<?php

namespace App\Services;

use App\Entities\Perfil;

/**
 * Class PerfilService
 * @package App\Services
 */
class PerfilService
{
    /**
     * @var Perfil
     */
    private $perfil;

    /**
     * PerfilService constructor.
     * @param Perfil $perfil
     */
    public function __construct(Perfil $perfil)
    {
        $this->perfil = $perfil;
    }

    /**
     * @return mixed
     */
    public function buscarPerfis()
    {
        return $this->perfil->get();
    }
}
