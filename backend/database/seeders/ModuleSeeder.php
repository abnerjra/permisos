<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Module::where('key', 'user')->exists()) {
            Module::create(['name' => 'Usuarios', 'key' => 'user', 'description' => 'Módulo de usuarios', 'active' => true, 'catalog' => false]);
        }

        if (!Module::where('key', 'role')->exists()) {
            Module::create(['name' => 'Roles y permisos', 'key' => 'role', 'description' => 'Módulo de roles y permisos', 'active' => true, 'catalog' => false]);
        }

        if (!Module::where('key', 'permission')->exists()) {
            Module::create(['name' => 'Lista de permisos', 'key' => 'permission', 'description' => 'Listado de permisos', 'active' => true, 'catalog' => true]);
        }

        if (!Module::where('key', 'structure')->exists()) {
            Module::create(['name' => 'Estructuras', 'key' => 'structure', 'description' => 'Module de estructuras', 'active' => true, 'catalog' => false]);
        }
    }
}
