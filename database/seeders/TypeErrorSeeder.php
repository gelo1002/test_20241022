<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeErrorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_errors')->insert([
            ['name' => "Error general"],
            ['name' => "Error en extension"],
            ['name' => "Error en cabecera"],
            ['name' => "Error en datos"],
        ]);
    }
}
