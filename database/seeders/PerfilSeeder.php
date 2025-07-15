<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("perfil")->insert([
            [
                'nombre_perfil' => 'Estudiante',
                'id_estatus' => 1,
            ],
            [
                'nombre_perfil' => 'Administrador',
                'id_estatus' => 2,
            ],
        ]);
    }
}
