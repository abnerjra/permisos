<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Structure;

class StructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Structure::where('acronym', 'SIS')->exists()) {
            Structure::create(['name' => 'Sistemas', 'acronym' => 'SIS', 'color' => '4cfede', 'active' => true]);
        }

        if (!Structure::where('acronym', 'RH')->exists()) {
            Structure::create(['name' => 'Recursos Humanos', 'acronym' => 'RH', 'color' => 'ee51dc', 'active' => true]);
        }
    }
}
