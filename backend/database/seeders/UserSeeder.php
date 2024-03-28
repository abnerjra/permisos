<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', '=', 'root@test.com')->exists() ) {
            $usuarioRoot = User::create([
                'name' => 'root',
                'first_last_name' => 'Admin',
                'email' => 'root@test.com',
                'password' => Hash::make('ro0t.t3st'),
                'active' => true,
                'structure_id' => 1
            ]);

            $rolRoot = Role::where('name', 'ROL_ROOT')->first();
            $usuarioRoot->assignRole($rolRoot);
        }

        if (!User::where('email', '=', 'capturista@test.com')->exists() ) {
            $usuarioRoot = User::create([
                'name' => 'Capturista',
                'first_last_name' => 'Test',
                'email' => 'capturista@test.com',
                'password' => Hash::make('cap.t3st'),
                'active' => true,
                'structure_id' => 2
            ]);

            $rolRoot = Role::where('name', 'ROL_CAPTURISTA')->first();
            $usuarioRoot->assignRole($rolRoot);
        }
    }
}
