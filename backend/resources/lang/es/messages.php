<?php

    return [
        'response' => [
            'create'    =>  ['message'  =>  'Registro creado correctamente', 'severity' => 'success', 'code' => 201],
            'update'    =>  ['message'  =>  'Registro actualizado correctamente', 'severity' => 'success', 'code' => 200],
            'read'      =>  ['message'  =>  'Información de registro', 'severity' => 'success', 'code' => 200],
            'reads'     =>  ['message'  =>  'Información de registros', 'severity' => 'success', 'code' => 200],
            'delete'    =>  ['message'  =>  'Registro eliminado correctamente', 'severity' => 'success', 'code' => 200],
            'active'    =>  ['message'  =>  'Registro activado correctamente', 'severity' => 'success', 'code' => 200],
            'inactive'  =>  ['message'  =>  'Registro desactivado correctamente', 'severity' => 'success', 'code' => 200],
            'empty'     =>  ['message'  =>  'No hay registros', 'severity' => 'warning', 'code' => 200],
            'empty_role'     =>  ['message'  =>  'Es necesario tener por lo menos un permiso', 'severity' => 'warning', 'code' => 409],
            'inactive_structure'   =>  ['message'  =>  'Actualmente la estructura cuenta con usuarios activos y no es posible desactivarla', 'severity' => 'warning', 'code' => 409],
            'inactive_user'   =>  ['message'  =>  'No se pudo activar el usuario. La estructura del usuario no se encuentra activa', 'severity' => 'warning', 'code' => 409],
            'inactive_structure_user'   =>  ['message'  =>  'La estructura seleccionada no se encuentra activa', 'severity' => 'warning', 'code' => 409]
        ],
        'permission' => [
            'without_permission' => ['message'  =>  'No tienes permisos para ver esta sección', 'severity' => 'warning', 'code' => 403]
        ],
        'fails' => [
            'register'  => ['message'   =>  'Error al procesar la solicitud', 'severity' => 'error', 'code' => 400],
            'validate'  => ['message'   =>  'Campos inválidos', 'severity' => 'warning', 'code' => 409],
            'duplicate' => ['message'   =>  'El registro ya se encuentra en el sistema', 'severity' => 'warning', 'code' => 409],
            'duplicate_structure_name' => ['message'   =>  'El nombre o siglas seleccionado ya es usado por otra estructura', 'severity' => 'warning', 'code' => 409],
            'duplicate_structure_color' => ['message'   =>  'El color seleccionado ya es usado por otra estructura', 'severity' => 'warning', 'code' => 409],
            'duplicate_email' => ['message'   =>  'El correo ingresado ya se encuentra registrado', 'severity' => 'warning', 'code' => 409],
            'active'    => ['message'   =>  'Error al activar el registro', 'severity' => 'error', 'code' => 409],
            'inactive'  => ['message'   =>  'Error al desactivar el registro', 'severity' => 'error' ,'code' => 409],
            'email_invalid' => ['message'   =>  'El correo ingresado no se encuentra registrado en el sistema', 'severity' => 'warning', 'code' => 409],
            'email_active' => ['message'   =>  'Esta cuenta de correo ya se encuentra activada', 'severity' => 'warning', 'code' => 409],
            'account_pass' => ['message'   =>  'Las contraseñas no coinciden', 'severity' => 'warning', 'code' => 409],
            'tipo_factura' => ['message'   =>  'La factura no tiene asignado su tipo de factura', 'severity' => 'warning', 'code' => 409],
            'factura_repeat' => ['message'   =>  'La factura ya fue asociada a un oficio', 'severity' => 'warning', 'code' => 409],
            'factura_dont_edit' => ['message'   =>  'La factura no se puede editar porque ya se encuentra solicitada o en proceso de atención', 'severity' => 'warning', 'code' => 409],
            'factura_dont_edit_service' => ['message'   =>  'La factura no es de tipo "con nota de crédito" o "con anticipo".', 'severity' => 'warning', 'code' => 409],
            'factura_dont_delete' => ['message'   =>  'La factura no se puede editar porque ya se encuentra solicitada o en proceso de pago', 'severity' => 'warning', 'code' => 409],
            'validate_contrato_proveedor'  => ['message'   =>  'El proveedor seleccionado no coincide con el proveedor del contrato seleccionado', 'severity' => 'warning', 'code' => 409],
        ],
        'account' => [
            'inactive' =>   ['message' => 'Está cuenta actualmente se encuentra inactiva', 'severity' => 'warning', 'code' => '403'],
            'invalids' =>   ['message' => 'El usuario o contraseña ingresados son incorrectos', 'severity' => 'warning', 'code' => '403'],
            'reset_password'    =>  ['message'  =>  'Contraseña restablecida, verifica tu correo.', 'severity'  =>  'success',  'code'  =>  200],
            'reset_password_fail'    =>  ['message'  =>  'Se produjo un problema al restablecer la contraseña.', 'severity'  =>  'success',  'code'  =>  200]
        ],
        'upload' => [
            'file_not_extension' => ['message'   =>  'La extensión del archivo no es válida', 'severity' => 'warning', 'code' => 409],
            'file_not_upload' => ['message'   =>  'Error al cargar el archivo', 'severity' => 'error', 'code' => 400],
            'file_empty' => ['message'   =>  'No hay archivos', 'severity' => 'warning', 'code' => 409],
            'file_success' => ['message'   =>  'Archivo cargado', 'severity' => 'success', 'code' => 200],
        ]
    ];
