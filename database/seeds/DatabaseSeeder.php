<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Carrega seeders criados
         */
        $this->call(UserSeeder::class);
        $this->call(PerfilSeeder::class);
    }
}
