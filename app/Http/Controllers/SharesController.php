<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSharesPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SharesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getSharesCountByPostId($id)
    {
        $shares = UserSharesPost::where('post_id', $id)->count();

        return response()->json($shares);
    }
    
    public function getSharedByUserId(Request $request, $id)
    {
        try {
            $validateRequest = Validator::make($request->all(), [
                'perPage' => 'nullable|numeric|min:1',
                'page' => 'nullable|numeric|min:1'
            ]);

            if ($validateRequest->fails()) {
                return response()->json([
                    'message' => 'Request validation failed',
                ], 401);
            }

            $shares = UserSharesPost::where('user_id', $id)->paginate(
                perPage: $request->perPage ?? 10,
                page: $request->page ?? 1,
            );

            return response($shares);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Invalid Request',
            ], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {
        $share = UserSharesPost::where('user_id', Auth::user()->id)->where('post_id', $id)->first();

        if(!$share){
            UserSharesPost::create([
                'user_id' => Auth::user()->id,
                'post_id' => $id
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Post shared successfully',
            ], 201);
        }

        $share->delete();

        return response()->json([
            'status' => true,
            'message' => 'Share removed successfully',
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
