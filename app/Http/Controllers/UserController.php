<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeUserInfoRequest;
use App\Http\Requests\DeactivateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function getCurrentUser() {
        // Gets user based on the authenticated user from which the request came from.
        $user = User::where(['id' => Auth::user()->id])->first();

        if ($user) {
            return response()->json($user, 200);
        }

        return response()->json(['User not found'], 404);
    }

    public function getUserById($id) {
        // Gets user based on id provided.
        $user = User::where(['id' => $id])->first();

        if ($user) {
            return response()->json($user, 200);
        }

        return response()->json(['User not found'], 400);
    }

    public function changeUserInfo(ChangeUserInfoRequest $request, $id) {
        $user = User::where(['id' => $id])->first();

        $salt = Str::random(64);

        if ($user) {
            $user->update([
                'name' => $request->name ?? $user->name,
                'email' => $request->email ?? $user->email,
                'password' => $request->password.Str::random(64) ?? $user->password,
                'salt' => $request->password ? $salt : $user->salt
            ]);

            return response()->json($user, 200);
        }

        return response()->json(['User not found'], 404);

    }

    public function deactivateUser(DeactivateUserRequest $request, $id) {
        $user = User::where(['id' => $id])->first();

        if ($user) {
            $user->delete();
            return response()->json(['User has been deleted'], 200);
        }

        return response()->json(['User not found'], 404);
    }
}
