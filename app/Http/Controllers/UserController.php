<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request) {
        // if($request->ajax()) {
            error_log("HOLA");
            try {
                //Validation
                $this->validate($request, [
                    'firstName' => 'required|string|max:255',
                    'lastName' => 'required|string|max:255',
                    'email' => 'required|email',
                    'password' => 'required|string'
                ]);

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

}
