<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class UserController extends Controller
{
    public function store(Request $request) {
        // if($request->ajax()) {
            try {
                //Validation
                // $this->validate($request, [
                //     'firstName' => 'required|string|max:255',
                //     'lastName' => 'required|string|max:255',
                //     'email' => 'required|email',
                //     'password' => 'required|string'
                // ]);

                $validator = Validator::make($request->all(), [
                    'firstName' => 'required|string|max:255',
                    'lastName' => 'required|string|max:255',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|string'
                ],
                [
                    'firstName.required' => 'Debe ingresar el nombre.',
                    'firstName.string' => 'El nombre debe ser de tipo texto.',
                    'lastName.required' => 'Debe ingresar el apellido.',
                    'lastName.string' => 'El apellido debe ser de tipo texto.',
                    'email.required' => 'Debe ingresar el email.',
                    'email.email' => 'Debe ingresar un email válido.',
                    'email.unique' => 'El correo ya existe.',
                    'password.required' => 'Debe ingresar la contaseña.',
                    'password.string' => 'La contraseña debe ser de tipo texto.',
                ]);

                if ($validator->fails()) {
                    return response()->json(['message' => $validator->errors()->first()], 400);
                }

                //Save new entry
                $user = new User();
                $user->firstName = $request->firstName;
                $user->lastName = $request->lastName;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->save();

                //Return response
                return response()->json([
                    'Message' => 'Ok',
                    'User' => $user
                ]); 
            } catch (ValidationException $error) {
                return response()->json(
                    $error->validator->errors()
                );
            }
        // }
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string'
        ], [
            'email.required' => 'Debe ingresar el email.',
            'email.email' => 'Debe ingresra un email válido.',
            'password.required' => 'Debe ingresar la contaseña.',
            'password.string' => 'La contraseña debe ser de tipo texto.'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Correo o contraseña inválidos.'
            ], 401);
        };

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');        
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addHour();
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'user' => $user
        ]);
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Su sesión se ha cerrado exitosamente'
        ]);

    }

}
