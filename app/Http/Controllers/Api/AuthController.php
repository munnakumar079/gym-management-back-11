<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GymDetail;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // ================= REGISTER =================
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'owner_name' => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'mobile'     => 'required|digits:10|unique:users,mobile',
            'gym_name'   => 'required|string|max:255',
            'city'       => 'required|string|max:255',
            'address'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {

            // Create User (Default Active = 1)
            $user = User::create([
                'name'     => $request->owner_name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'mobile'   => $request->mobile,
                'status'   => 1 // 👈 Default Active
            ]);

            // Create Gym Detail
            GymDetail::create([
                'user_id'  => $user->id,
                'gym_name' => $request->gym_name,
                'city'     => $request->city,
                'address'  => $request->address,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data'    => $user->load('gymDetail')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ================= LOGIN =================
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        // ❌ User Not Found
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // ❌ Blocked User
        if ($user->status == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is blocked by admin'
            ], 403);
        }

        // 🔐 Attempt Login
        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user'  => auth()->user()->load('gymDetail'),
                'token' => $token,
                'type'  => 'bearer'
            ]
        ]);
    }

    // ================= PROFILE =================
    public function profile()
    {
        return response()->json([
            'success' => true,
            'message' => 'Profile fetched successfully',
            'data'    => auth()->user()->load('gymDetail')
        ]);
    }

    // ================= LOGOUT =================
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }
}