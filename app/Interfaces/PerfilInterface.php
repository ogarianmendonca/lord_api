<?php

namespace App\Interfaces;

use App\Entities\Perfil;
use Illuminate\Support\Collection;

interface PerfilInterface
{
    /**
     * @return Collection<Perfil>
     */
    public function buscarPerfis(): Collection;
}
