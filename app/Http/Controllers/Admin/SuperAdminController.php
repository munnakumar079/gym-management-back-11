<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class SuperAdminController extends Controller
{

    // 🔹 User List (Only Super Admin)
    public function allUsers()
    {
        $authUser = auth()->user();

        if (!$authUser) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // ✅ Sirf status = 3 dekh sakta hai
        if ($authUser->status != 3) {
            return response()->json([
                'message' => 'Only Super Admin can view users'
            ], 403);
        }

        // ❌ Super admin list me nahi aayega
        $users = User::with('gymDetail')
                    ->where('status','!=',3)
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }


    // 🔹 Block User (status = 2)
    public function blockUser($id)
    {
        $authUser = auth()->user();

        if (!$authUser || $authUser->status != 3) {
            return response()->json([
                'message' => 'Only Super Admin can block users'
            ], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        if ($user->status == 3) {
            return response()->json([
                'message' => 'Super Admin cannot be blocked'
            ], 403);
        }

        $user->status = 2; // blocked
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User blocked successfully'
        ]);
    }


    // 🔹 Unblock User (status = 1)
    public function unblockUser($id)
    {
        $authUser = auth()->user();

        if (!$authUser || $authUser->status != 3) {
            return response()->json([
                'message' => 'Only Super Admin can unblock users'
            ], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->status = 1; // active
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User unblocked successfully'
        ]);
    }

}
