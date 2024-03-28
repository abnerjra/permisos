<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\PermissionList;

class PermissionListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!PermissionList::where('key', 'create')->exists()) {
            PermissionList::create(['name' => 'Crear', 'key' => 'create', 'active' => true]);
        }

        if (!PermissionList::where('key', 'read')->exists()) {
            PermissionList::create(['name' => 'Leer', 'key' => 'read', 'active' => true]);
        }

        if (!PermissionList::where('key', 'update')->exists()) {
            PermissionList::create(['name' => 'Editar', 'key' => 'update', 'active' => true]);
        }

        if (!PermissionList::where('key', 'delete')->exists()) {
            PermissionList::create(['name' => 'Borrar', 'key' => 'delete', 'active' => true]);
        }
    }
}
