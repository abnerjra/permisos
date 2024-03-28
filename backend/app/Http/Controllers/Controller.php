<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Module;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->validatePermission();
    }

    /**
     * Declaración de métodos setter & getter
     */
    protected $permiso = "";
    protected function setPermission($permiso)
    {
        $this->permiso = $permiso;
    }

    protected function getPermission()
    {
        return $this->permiso;
    }

    /**
     * Validación de roles y permisos
     * @return true|false
     */
    protected function validatePermission()
    {
        $checkPermission = $this->getPermission();
        if (!empty($checkPermission)) {
            $prefix = self::getPrefix();
            
            $userSession = auth()->user();

            if ($userSession) {
                $id = $userSession->id;

                $getUser = User::find($id);
                $roles = $getUser->roles;
                
                $array_pemissions = [];
                $roles_validate = [];
                foreach ($roles as $rol) {
                    $rol_name = Str::lower($rol->name);
                    $roles_validate[] = $rol_name . "." . $prefix . "." . $checkPermission;
                }

                $permissions = $this->getPermissions($roles);
                
                foreach ($roles_validate as $rolPermiso) {
                    if (in_array($rolPermiso, $permissions)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
    }

    /**
     * Retorna un array con los permisos asociados al rol
     * @param array $roles
     * @return array $array_pemissions
     */
    private function getPermissions($roles)
    {
        $array_pemissions = [];
        foreach ($roles as $rol) {
            $getRole = Role::find($rol->id);
            $permissions = $getRole->permissions;
            foreach ($permissions as $permission) {
                array_push($array_pemissions, $permission->name);
            }
        }

        return $array_pemissions;
    }

    /** 
     * Retorna el prefijo de la ruta actual
     * @return string 
     */
    public function getPrefix()
    {
        $prefix = Route::getCurrentRoute()->getAction()['prefix'];
        $aux = explode('/', $prefix);
        $prefix = $aux[1];

        return $prefix;
    }

    /**
     * Obtenemos lista de roles y permisos asociados al usuario
     * @param int $usuarioId
     * 
     * @return array
     */
    public function rolesToArray($usuarioId): array
    {
        $userSession = User::find($usuarioId);
        $roleNames = $userSession->getRoleNames();
        $array_roles = [];
        $array_pemissions = [];
        foreach ($roleNames as $rol) {
            array_push($array_roles, $rol);
            $role = Role::findByName($rol);
            $array_pemissions = $this->permisosRol($role->id);
        }
        return [
            'roles' => $array_roles,
            'permissions' => $array_pemissions
        ];
    }

    /** 
     * Obtencion de lista de permisos agrupados por modulo
     * @param int $rolId
     * 
     * @return array
     */
    private function permisosRol($rolId)
    {
        $lista_modulos = [];
        $modulos = Module::where('catalog', false)->get();
        if ($rolId == 1) {
            $modulos = Module::all();
        }
        
        foreach ($modulos as $modulo) {
            $lista_modulos[] = [
                'id' => $modulo->id,
                'key' => $modulo->key
            ];
        }
        
        $rol = Role::findById($rolId);
        $permisos = $rol->permissions;
        
        $lista_permisos = [];
        foreach ($lista_modulos as $modulo) {
            foreach ($permisos as $permiso) {
                if ($modulo['id'] == $permiso->module_id) {
                    $lista_permisos[$modulo['key']][] = [
                        'description' => $permiso->description,
                        'action' => $permiso->action
                    ];
                }
            }
        }
        return $lista_permisos;
    }
}
