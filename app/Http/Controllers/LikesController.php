<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserLikesPost;
use Illuminate\Support\Facades\Auth;

class LikesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getLikesById($id)
    {
        $likes = UserLikesPost::where('post_id', $id)->count();

        return response()->json($likes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $like = new UserLikesPost();
        $like->user_id = Auth::user()->id;
        $like->post_id = $id;
        $like->save();

        return response()->json($like);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserLikesPost $userLikesPost, $id)
    {
        $like = UserLikesPost::where('user_id', Auth::user()->id)->where('post_id', $id)->first();
        $like->delete();

        return response()->json([
            'message' => 'Like removed successfully',
        ]);
    }
}
