<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class SuperAdminController extends Controller
{
public function allUsers()
{
    $authUser = auth()->user();

    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    if ($authUser->status != 3) {
        return response()->json(['message' => 'Access denied. Super Admin only.'], 403);
    }

    $users = \App\Models\User::with('gymDetail')->get();

    return response()->json([
        'success' => true,
        'data' => $users
    ]);
}

    // 🔹 Block User
    public function blockUser($id)
    {
        $authUser = auth()->user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if ($authUser->status != 3) {
            return response()->json(['message' => 'Access denied. Super Admin only.'], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->is_blocked = 1; // make sure column exists
        $user->save();

        return response()->json([
            'message' => 'User blocked successfully'
        ]);
    }

    // 🔹 Unblock User
    public function unblockUser($id)
    {
        $authUser = auth()->user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if ($authUser->status != 3) {
            return response()->json(['message' => 'Access denied. Super Admin only.'], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->is_blocked = 0; 
        $user->save();

        return response()->json([
            'message' => 'User unblocked successfully'
        ]);
    }
}