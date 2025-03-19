<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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

            $category = Category::paginate(
                perPage: $request->perPage ?? 10,
                page: $request->page ?? 1,
            );

            return response($category);
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
                'name' => 'required|string|min:1'
            ]);

            if ($validateRequest->fails()) {
                return response()->json([
                    'message' => 'Request validation failed',
                ], 401);
            }

            Category::create([
                'name' => $request->name
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
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
            $category = Category::find($id);

            return response()->json([
                'status' => true,
                'message' => 'Category retrieved successfully',
                'data' => $category
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
    /* public function update(Request $request, $id)
    {
        try {
            $validateRequest = Validator::make($request->all(), [
                'name' => 'required|string|min:1'
            ]);

            if ($validateRequest->fails()) {
                return response()->json([
                    'message' => 'Request validation failed',
                ], 401);
            }

            $category = Category::find($id);

            if ($category->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'User is not authorized to update category',
                ], 401);
            }

            $category->update([
                'name' => $request->name ?? $category->name,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully',
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid Request',
            ], 401);
        }
    } */

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     try {
    //         $category = Category::find($id);

    //         if ($category->user_id != Auth::user()->id) {
    //             return response()->json([
    //                 'message' => 'User is not authorized to delete category',
    //             ], 401);
    //         }

    //         $category->delete();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Category deleted successfully',
    //         ], 200);
    //     }
    //     catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Invalid Request',
    //         ], 401);
    //     }
    // }
}
