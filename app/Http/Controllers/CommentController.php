<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getCommentsById(Request $request, $id)
    {
        try {
            $validateRequest = Validator::make($request->all(), [
                'id' => 'required|numeric|min:1',
                'perPage' => 'nullable|numeric|min:1',
                'page' => 'nullable|numeric|min:1'
            ]);

            if ($validateRequest->fails()) {
                return response()->json([
                    'message' => 'Request validation failed',
                ], 401);
            }

            $comments = Comment::where('post_id', $id)->paginate(
                perPage: $request->perPage ?? 10,
                page: $request->page ?? 1,
            );

            return response($comments);

        } catch (\Throwable $th) {
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
                'post_id' => 'required|numeric|min:1',
                'user_id' => 'required|numeric|min:1',
                'content' => 'required|string|min:1'
            ]);

            if ($validateRequest->fails()) {
                return response()->json([
                    'message' => 'Request validation failed',
                ], 401);
            }

            Comment::create([
                'post_id' => $request->post_id,
                'user_id' => Auth::user()->id,
                'content' => $request->content
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Comment created successfully',
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
            $comment = Comment::find($id);

            return response()->json([
                'status' => true,
                'message' => 'Comment retrieved successfully',
                'data' => $comment
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
                'content' => 'nullable|string|min:1'
            ]);

            if ($validateRequest->fails()) {
                return response()->json([
                    'message' => 'Request validation failed',
                ], 401);
            }

            $comment = Comment::find($id);

            if ($comment->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'Comment is not owned by user',
                ], 401);
            }

            $comment->update([
                'content' => $request->content ?? $comment->content,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Comment updated successfully',
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
            $comment = Comment::find($id);

            if ($comment->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'Comment is not owned by user',
                ], 401);
            }

            $comment->delete();

            return response()->json([
                'status' => true,
                'message' => 'Comment deleted successfully',
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid Request',
            ], 401);
        }
    }
}
