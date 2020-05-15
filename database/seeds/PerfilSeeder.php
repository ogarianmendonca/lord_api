<?php

use Illuminate\Database\Seeder;
use App\Entities\Perfil;

class PerfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Gera perfis padrão
         */
        Perfil::create([
            'descricao' => 'ADMINISTRADOR',
            'status' => true
        ]);

        Perfil::create([
            'descricao' => 'COORDENADOR',
            'status' => true
        ]);

        Perfil::create([
            'descricao' => 'USUARIO',
            'status' => true
        ]);
    }
}
