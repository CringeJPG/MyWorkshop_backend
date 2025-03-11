<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeUserInfoRequest;
use App\Http\Requests\DeactivateUserRequest;
use App\Models\User;
use App\Models\UserFollowsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

            $user->name = $request->name ?? $user->name;
            $user->email = $request->email ?? $user->email;

            if ($request->password) {
                $user->password = Hash::make($request->password.$salt);
                $user->salt = $salt;
            }

            $user->save();
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

    public function followUser(Request $request, $id) {
        // Checks if user is already following user
        $following = UserFollowsUser::where([
            'user_id' => Auth::user()->id,
            'followed_user_id' => $id
        ])->first();

        if (!$following && Auth::user()->id != $id) {
            UserFollowsUser::create([
                'user_id' => Auth::user()->id,
                'followed_user_id' => $id
            ]);

            return response()->json(['Successfully followed user'], 200);
        }
        else if ($following && Auth::user()->id != $id) {
            UserFollowsUser::where([
                'user_id' => Auth::user()->id,
                'followed_user_id' => $id
            ])->delete();

            return response()->json(['Successfully unfollowed user'], 200);

        }

        return response()->json(['Could not perform the requested action on user'], 500);
    }

    public function checkIfFollowingUser(Request $request, $id) {
        // Checks if user is already following user
        $following = UserFollowsUser::where([
            'user_id' => Auth::user()->id,
            'followed_user_id' => $id
        ])->first();

        if ($following) {
            return response()->json(true, 200);
        }
        else {
            return response()->json(false, 200);
        }
    }

    public function followerCount(Request $request, $id) {
        $followers = UserFollowsUser::where([
            'followed_user_id' => $id
        ])->count();

        return response()->json($followers, 200);
    }
}
