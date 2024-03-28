<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use Spatie\Permission\Models\Role;

use App\Helpers\Help;
use App\Helpers\CustomResponse;

class UserController extends Controller
{
    /** 
     * Declaramos las variables para validar los permisos del modulo
     */
    protected $permissionRead = "read";
    protected $permissionCreate = "create";
    protected $permissionUpdate = "update";
    protected $permissionDelete = "delete";

    /** Inicializamos contructor para heredar metodos de Controller */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->setPermission($this->permissionRead);
        if (!$this->validatePermission()) {
            return CustomResponse::error(__('messages.permission.without_permission'), []);
        }

        $getData = User::select('*');

        $total_registros_sin_paginar = $getData->count();
        $getData->orderBy('created_at', 'desc');
        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $getData->limit($limit)->offset(($page - 1) * $limit);
        $result = json_decode($getData->get());
        $total_registros = count($result);

        $paginado = [
            'total_per_page' => $total_registros,
            'total_records' => $total_registros_sin_paginar
        ];

        $results = $getData->get();

        $data = [];
        foreach ($results as $key => $user) {
            $structure = $user->structure;
            $getRoles = $this->rolesToArray($user->id);
            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'firstName' => $user->first_last_name,
                'secondName' => $user->second_last_name,
                'email' => $user->email,
                'structure' => [
                    'id' => $structure->id,
                    'name' => $structure->name
                ],
                'role' => $getRoles['roles']
            ];
        }
        
        return CustomResponse::success(__('messages.response.reads'), $data, $paginado);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->setPermission($this->permissionCreate);
        if (!$this->validatePermission()) {
            return CustomResponse::error(__('messages.permission.without_permission'), []);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastName'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'structure' => 'required|integer|exists:cat_structure,id',
            'role' => 'required|array'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return CustomResponse::error(__('messages.fails.validate'), $errors->all());
        }

        if(User::where('email', $request->email)->exists()) {
            return CustomResponse::error(__('messages.fails.duplicate_email'), []);
        }

        // print_r($request->all());
        DB::beginTransaction();
        try {
            $user = new User;
            $user->name = $request->name;
            $user->first_last_name = $request->firstLastName;
            $user->second_last_name = $request->secondLastName;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->structure_id = $request->structure;
            $user->active = true;
            if ($user->save()) {
                $this->asignarRoleUsuario($user->id, $request->role);
                DB::commit();
                return CustomResponse::success(__('messages.response.create'), $user->id);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return CustomResponse::error(__('messages.fails.register'), $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->setPermission($this->permissionRead);
        if (!$this->validatePermission()) {
            return CustomResponse::error(__('messages.permission.without_permission'), []);
        }

        $user = User::find($id);
        if (empty($user)) {
            return CustomResponse::error(__('messages.response.empty'), []);
        }
        
        $structure = $user->structure;
        $getRoles = $this->rolesToArray($user->id);
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'fisrtLast_name' => $user->first_last_name,
            'secondLast_name' => $user->second_last_name,
            'email' => $user->email,
            'structure' => [
                'id' => $structure->id,
                'name' => $structure->name
            ],
            'role' => $getRoles['roles']
        ];

        return CustomResponse::success(__('messages.response.read'), $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->setPermission($this->permissionUpdate);
        if (!$this->validatePermission()) {
            return CustomResponse::error(__('messages.permission.without_permission'), []);
        }

        $setUser = User::find($id);
        if (empty($setUser)) {
            return CustomResponse::error(__('messages.response.empty'), []);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'firstLastName'  => 'required|string|max:255',
            'secondLastName'  => 'string|max:255',
            'email' => 'required|string|email|max:255',
            'structure' => 'required|integer|exists:cat_structure,id',
            'role' => 'required|array'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return CustomResponse::error(__('messages.fails.validate'), $errors->all());
        }

        $roles = $request->role;
        $roles_eliminar = $this->diferenciaRoles($id, $roles);
        // print_r($roles_eliminar);
        // return response()->json(['message' => 'Pase']);
        DB::beginTransaction();
        try {
            $setUser->name = $request->name;
            $setUser->first_last_name = $request->firstLastName;
            $setUser->second_last_name = $request->secondLastName;
            $setUser->structure_id = $request->structure;
            if (isset($request->email)) {
                $setUser->email = $request->email;
            }

            if (isset($request->password)) {
                $setUser->password = Hash::make($request->password);
            }

            if ($setUser->update()) {
                $this->asignarRoleUsuario($id, $roles, $roles_eliminar);
                DB::commit();
                return CustomResponse::success(__('messages.response.update'), $setUser->id);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return CustomResponse::error(__('messages.fails.register'), $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->setPermission($this->permissionDeleted);
        if (!$this->validatePermission()) {
            return CustomResponse::error(__('messages.permission.without_permission'), []);
        }
    }

    /**
     * Retorna array con los ids de roles a eliminar
     * @param int $usuarioId => Id del usuario a modificar
     * @param array $roles => Contiene los ids de los roles
     * @return array
     */
    private function diferenciaRoles($usuarioId, $roles)
    {
        $user = User::find($usuarioId);

        $getRoles = $user->getRoleNames();
        $array_role = [];
        foreach ($getRoles as $key => $rol) {
            $role = Role::findByName($rol);
            array_push($array_role, $role->id);
        }

        $diferencia = [];
        foreach ($array_role as $role) {
            if (!in_array($role, $roles)) {
                $diferencia[] = $role;
            }
        }

        return $diferencia;
    }

    /**
     * Asignación de roles a un usuario en especifico
     * @param usuarioId int
     * @param roles array
     * @param roles_eliminar array|opcional
     * @return void
     */
    private function asignarRoleUsuario($usuarioId, $roles, $roles_eliminar = [])
    {
        if (!empty($roles_eliminar)) {
            $user = User::find($usuarioId);

            foreach ($roles_eliminar as $rol) {
                $role = Role::find($rol);
                if ($user->hasRole($role->name)) {
                    // echo "Se retira el rol: ".$role->name."\n";
                    $user->removeRole($role->name);
                }
            }
        }

        if (!empty($roles)) {
            $usuarioInstance = User::find($usuarioId);
            foreach ($roles as $rol) {
                $role = Role::find($rol);
                // Si no tiene el rol asignado, se agrega
                if (!$usuarioInstance->hasRole($role->name)) {
                    // echo "\nTiene el rol asignado";
                    $usuarioInstance->assignRole($role);
                }
            }
        }
    }
}
