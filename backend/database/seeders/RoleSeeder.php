<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\module;
use App\Models\PermissionList;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permisos del root
        $modulesRoot = module::whereIn('key', ['user', 'role', 'permission', 'structure'])->get();
        $permissionsRoot = PermissionList::whereIn('key', ['create', 'read', 'update', 'delete'])->get();
        $array_permission_root = [];
        foreach ($modulesRoot as $module) {
            foreach ($permissionsRoot as $permission) {
                $array_permission_root[] = [
                    'name' => "rol_root.".$module->key.'.'.$permission->key,
                    'group' => $module->key,
                    'description' => $permission->name,
                    'action' => $permission->key,
                    'module_id' => $module->id
                ];
            }
        }

        if (!Role::where('name', 'ROL_ROOT')->exists()) {
            $rolRoot = Role::create(['name' => 'ROL_ROOT', 'description' => 'Administrador']);
            foreach ($array_permission_root as $per => $permission) {
                $permissionRoot = Permission::create([
                    'name' => $permission['name'],
                    'group' => $permission['group'],
                    'guard_name' => 'api',
                    'description' => $permission['description'],
                    'action' => $permission['action'],
                    'module_id' => $permission['module_id']
                ]);
                $permissionRoot->assignRole($rolRoot);
            }
        }

        // Permisos del capturista
        $modulesCapturist = module::whereIn('key', ['user', 'estructura'])->get();
        $permissiosCapturist = PermissionList::whereIn('key', ['create', 'read', 'update', 'delete'])->get();
        $array_permission_capturist = [];
        foreach ($modulesCapturist as $module) {
            foreach ($permissiosCapturist as $permission) {
                $array_permission_capturist[] = [
                    'name' => "rol_capturista.".$module->key.'.'.$permission->key,
                    'group' => $module->key,
                    'description' => $permission->name,
                    'action' => $permission->key,
                    'module_id' => $module->id
                ];
            }
        }

        if (!Role::where('name', 'ROL_CAPTURISTA')->exists()) {
            $rolCapturist = Role::create(['name' => 'ROL_CAPTURISTA', 'description' => 'Capturista']);
            foreach ($array_permission_capturist as $per => $permission) {
                $permissionCapturist = Permission::create([
                    'name' => $permission['name'],
                    'group' => $permission['group'],
                    'guard_name' => 'api',
                    'description' => $permission['description'],
                    'action' => $permission['action'],
                    'module_id' => $permission['module_id']
                ]);
                $permissionCapturist->assignRole($rolCapturist);
            }
        }
    }
}
