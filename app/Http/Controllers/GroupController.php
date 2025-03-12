<?php

namespace App\Http\Controllers;

use App\Models\UserFollowsGroup;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
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
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $group = Group::with('image')->paginate(
                perPage: $request->perPage ?? 10,
                page: $request->page ?? 1,
            );

            return response($group);

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
                'category_id' => 'required|numeric|min:1',
                'image_id' => 'nullable|numeric|min:1',
                'name' => 'required|string|min:1',
                'description' => 'required|string|min:1',
                'image' => 'nullable|image|mimes:jpg,jpeg,png',
            ]);

            if ($validateRequest->fails()) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $imageId = null;
            if ($request->image) {
                $imageId = Image::create([
                    'base64_data' => base64_encode(file_get_contents($request->file('image')))
                ])->id;
            }

            Group::create([
                'user_id' => Auth::user()->id,
                'category_id' => $request->category_id,
                'image_id' => $imageId ?? null,
                'name' => $request->name,
                'description' => $request->description
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Group created successfully',
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
            $group = Group::with('image')->find($id);

            return response()->json([
                'status' => true,
                'message' => 'Group retrieved successfully',
                'data' => $group
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
                'image_id' => 'nullable|numeric|min:1',
                'category_id' => 'nullable|numeric|min:1',
                'name' => 'nullable|string|min:1',
                'description' => 'nullable|string|min:1',
                'image' => 'nullable|image|mimes:jpg,jpeg,png',
            ]);

            if ($validateRequest->fails()) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $group = Group::find($id);

            if ($group->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'Group is not owned by user',
                ], 401);
            }

            $imageId = null;
            if ($request->image) {
                $imageId = Image::create([
                    'base64_data' => base64_encode(file_get_contents($request->file('image')))
                ])->id;
            }

            $group->image_id = $imageId ?? $group->image_id;
            $group->category_id = $request->category_id ?? $group->category_id;
            $group->description = $request->description ?? $group->description;
            $group->name = $request->name ?? $group->name;
            $group->save();

            return response()->json([
                'status' => true,
                'message' => 'Group updated successfully',
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                $e->getMessage()
                // 'message' => 'Invalid Request',
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $group = Group::find($id);

            if ($group->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'Group is not owned by user',
                ], 401);
            }

            $group->delete();

            return response()->json([
                'status' => true,
                'message' => 'Group deleted successfully',
            ], 200);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid Request',
            ], 401);
        }
    }

    public function followGroup(Request $request, $id) {
        // Checks if user is already following user
        $following = UserFollowsGroup::where([
            'user_id' => Auth::user()->id,
            'group_id' => $id
        ])->first();

        if (!$following) {
            UserFollowsGroup::create([
                'user_id' => Auth::user()->id,
                'group_id' => $id
            ]);

            return response()->json(['Successfully followed group'], 200);
        }
        else if ($following) {
            UserFollowsGroup::where([
                'user_id' => Auth::user()->id,
                'group_id' => $id
            ])->delete();

            return response()->json(['Successfully unfollowed group'], 200);

        }

        return response()->json(['Could not perform the requested action on user'], 500);
    }

    public function checkIfFollowingGroup(Request $request, $id) {
        // Checks if user is already following user
        $following = UserFollowsGroup::where([
            'user_id' => Auth::user()->id,
            'group_id' => $id
        ])->first();

        if ($following) {
            return response()->json(true, 200);
        }
        else {
            return response()->json(false, 200);
        }
    }

    public function groupFollowerCount(Request $request, $id) {
        $followers = UserFollowsGroup::where([
            'group_id' => $id
        ])->count();

        return response()->json($followers, 200);
    }
}
