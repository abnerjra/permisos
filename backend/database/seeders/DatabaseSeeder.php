<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionListSeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(StructureSeeder::class);
        $this->call(UserSeeder::class);

        Model::reguard();
    }
}
