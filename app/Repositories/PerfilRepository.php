<?php

namespace App\Repositories;

use App\Entities\Perfil;
use App\Interfaces\PerfilInterface;

class PerfilRepository implements PerfilInterface
{
/**
     * @var Perfil
     */
    private $perfil;

    /**
     * PerfilRepository constructor.
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
