<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogInRequest;
use App\Http\Requests\LogOutRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
    public function signUp(SignUpRequest $request) {
        // Checks if user already exists.
        if (Auth::user()) {
            return response()->json(['User already logged in'], 400);
        }

        $salt = Str::random(64);

        // Tries to create user with supplied credentials. If the 'unique' constraint is violated, an exception is thrown, and the function returns a 400.
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'salt' => $salt,
                'password' => Hash::make($request->password.$salt), // The salt is stored in its own field on the user, and also appended to the password before it is hashed.
            ]);
        } catch (UniqueConstraintViolationException $e) {
            return response()->json(['User already exists'], 400);
        }

        return response()->json(['User created successfully'], 201);
    }

    public function logIn(LogInRequest $request) {
        // Fetches user based on the supplied email.
        $user = User::where([
            'email' => $request->email
        ])->first();

        // If no user exists with the supplied email, this block is skipped, and 404 is thrown.
        if ($user) {
            // If user exists, supplied password is hashed and compared to password in user record.
            if (Hash::check($request->password.$user->salt, $user->password)) {
                // Deletes existing access tokens on user.
                $user->tokens()->delete();

                // Create new access token on user.
                $token = $user->createToken('WEBAPP-TOKEN')->plainTextToken;

                // Token is returned.
                return response()->json(['token' => $token], 201);
            }
        }

        return response()->json(['Email or password not found'], 404);
    }

    public function logOut(LogOutRequest $request)
    {
        // Deletes users current access token.
        Auth::user()->tokens()->delete();

        return response()->json(['Logged out'], 200);
    }
}
