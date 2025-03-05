<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $validateRequest = Validator::make($request->all(), [
                'perPage' => 'nullable|numeric|min:1',
                'page' => 'nullable|numeric|min:1',
                'user_id' => 'nullable|numeric|min:1'
            ]);

            if ($validateRequest->fails()) {
                return response()->json([
                    'message' => 'Request validation failed',
                ], 401);
            }

            if (!$request->user_id) {
                $posts = Post::paginate(
                    perPage: $request->perPage ?? 10,
                    page: $request->page ?? 1,
                );

                return response($posts);
            }
            else {
                $posts = Post::where('user_id', $request->user_id)->paginate(
                    perPage: $request->perPage ?? 10,
                    page: $request->page ?? 1,
                );

                return response($posts);
            }
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid Request',
            ], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validateRequest = Validator::make($request->all(), [
                'title' => 'required|string|min:1',
                'content' => 'required|string|min:1',
                'group_id' => 'nullable|numeric|min:1'
            ]);

            if ($validateRequest->fails()) {
                return response()->json([
                    'message' => 'Request validation failed',
                ], 401);
            }

            Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => Auth::user()->id,
                'group_id' => $request->group_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Post created successfully',
            ], 201);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid Request',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $post = Post::find($id);

            return response()->json([
                'status' => true,
                'message' => 'Post retrieved successfully',
                'data' => $post
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid Request',
            ], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validateRequest = Validator::make($request->all(), [
                'title' => 'nullable|string|min:1',
                'content' => 'nullable|string|min:1',
            ]);

            if ($validateRequest->fails()) {
                return response()->json([
                    'message' => 'Request validation failed',
                ], 401);
            }

            $post = Post::find($id);

            if ($post->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'Post is not owned by user',
                ], 401);
            }

            $post->update([
                'title' => $request->title ?? $post->title,
                'content' => $request->content ?? $post->content,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Post updated successfully',
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid Request',
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $post = Post::find($id);

            if ($post->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'Post is not owned by user',
                ], 401);
            }

            $post->delete();

            return response()->json([
                'status' => true,
                'message' => 'Post deleted successfully',
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid Request',
            ], 401);
        }
    }
}
