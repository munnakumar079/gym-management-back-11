<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    // 🔹 Get All Users With Gym Details
    public function userList()
    {
        $users = User::with('gymDetail')
            ->select('id', 'name', 'email', 'mobile', 'status')
            ->latest()
            ->get()
            ->map(function ($user) {
                return [
                    'id'         => $user->id,
                    'owner_name' => $user->name,
                    'email'      => $user->email,
                    'mobile'     => $user->mobile,
                    'status'     => $user->status,
                    'gym_name'   => optional($user->gymDetail)->gym_name,
                    'city'       => optional($user->gymDetail)->city,
                    'address'    => optional($user->gymDetail)->address,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'User list fetched successfully',
            'data'    => $users
        ]);
    }

    // 🔹 View Single User With Gym Details
    public function viewUser($id)
    {
        $user = User::with('gymDetail')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'         => $user->id,
                'owner_name' => $user->name,
                'email'      => $user->email,
                'mobile'     => $user->mobile,
                'status'     => $user->status,
                'gym_name'   => optional($user->gymDetail)->gym_name,
                'city'       => optional($user->gymDetail)->city,
                'address'    => optional($user->gymDetail)->address,
            ]
        ]);
    }

    // 🔹 Block / Unblock User
    public function updateStatus(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:0,1'
        ]);

        $user->status = $request->status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $request->status == 0
                ? 'User blocked successfully'
                : 'User activated successfully'
        ]);
    }

    // 🔹 Delete User (with Gym Detail)
    public function deleteUser($id)
    {
        $user = User::with('gymDetail')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Delete gym detail first
        if ($user->gymDetail) {
            $user->gymDetail->delete();
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}