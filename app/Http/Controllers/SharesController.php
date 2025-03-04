<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSharesPost;
use Illuminate\Support\Facades\Auth;

class SharesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getSharesById($id)
    {
        $shares = UserSharesPost::where('post_id', $id)->count();

        return response()->json($shares);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {
        UserSharesPost::create([
            'user_id' => Auth::user()->id,
            'post_id' => $id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post shared successfully',
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $share = UserSharesPost::where('user_id', Auth::user()->id)->where('post_id', $id)->first()->delete();

        return response()->json([
            'message' => 'Share removed successfully',
        ]);
    }
}
