<?php

use Illuminate\Database\Seeder;
use App\Entities\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Gera usuário padrão
         */
        User::create([
            'name' => 'Luigi Bros',
            'email' => 'luigi@email.com',
            'password' => app('hash')->make('123456'),
            'imagem' => 'luigi.png',
            'perfil_id' => 1,
            'status' => true
        ]);

        User::create([
            'name' => 'Mario Bros',
            'email' => 'mario@email.com',
            'password' => app('hash')->make('123456'),
            'imagem' => 'mario.png',
            'perfil_id' => 2,
            'status' => true
        ]);

        User::create([
            'name' => 'João das Neves',
            'email' => 'joao@email.com',
            'password' => app('hash')->make('123456'),
            'imagem' => 'joao.png',
            'perfil_id' => 3,
            'status' => true
        ]);
    }
}
