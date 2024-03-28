<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Module;

use Laravel\Passport\RefreshToken;
use Spatie\Permission\Models\Role;

use App\Helpers\CustomResponse;


class AuthController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function login(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'username' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validacion->fails()) {
            return response(['errors' => $validacion->errors()->all()], 403);
        }

        $user = User::where('email', $request->username)->first();

        if ($user) {
            if (!$user->active) {
                return CustomResponse::error(__('messages.account.inactive'));
            }

            if (Hash::check($request->password, $user->password)) {
                $tokenResult = $user->createToken('BACK-IS');
                $token = $tokenResult->token;

                $token->expires_at = Carbon::now()->addDays(1);

                $token->save();
                $getRoles = $this->rolesToArray($user->id);

                return response()->json([
                    'id' => $user->id,
                    'name' => $user->name,
                    'first_name' => $user->firstLastName,
                    'second_name' => $user->secondLastName,
                    'email' => $user->email,
                    // 'puesto' => $user->puesto,
                    'structure' => $user->structure ?? null,
                    'roles' => $getRoles['roles'],
                    'permissions' => $getRoles['permissions'],
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_in' => Carbon::parse($token->expires_at)->toDateTimeString(),
                ], 200);
            } else {
                return CustomResponse::error(__('messages.account.invalids'));
            }
        } else {
            return CustomResponse::error(__('messages.account.invalids'));
        }
    }

    public function logout(Request $request)
    {
        $bearerToken =  $request->bearerToken(); //Obtiene por medio del header...

        if (!$bearerToken) {
            return response()->json(['message' => __('messages.jwt.token_not_provided')], Response::HTTP_BAD_REQUEST);
        }

        // $user = auth()->user();

        $token = null;

        $token = $token ?? auth()->user()->token(); // Token usado...

        RefreshToken::where('access_token_id', '=', $token->id)->update(['revoked' => 1]);  //Refresh, si existe, se desactiva

        $token->revoke(); //deshabilita su uso...
        return response()->json(['message' => 'Token eliminado']);
    }
}
