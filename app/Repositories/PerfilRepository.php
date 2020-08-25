<?php

namespace App\Repositories;

use App\Entities\Perfil;
use App\Interfaces\PerfilInterface;
use Illuminate\Support\Collection;

class PerfilRepository implements PerfilInterface
{
    /**
     * @var Perfil
     */
    private Perfil $perfil;

    /**
     * PerfilRepository constructor.
     * @param Perfil $perfil
     */
    public function __construct(Perfil $perfil)
    {
        $this->perfil = $perfil;
    }

    /**
     * @return Collection<Perfil>
     */
    public function buscarPerfis(): Collection
    {
        return $this->perfil->get();
    }
}
