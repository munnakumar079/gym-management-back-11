<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GymDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

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

            $user = User::create([
                'name'     => $request->owner_name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'mobile'   => $request->mobile,
                'status'   => 1
            ]);

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
                'message' => 'Registration failed',
                'error'   => $e->getMessage()
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

        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = auth('api')->user();

        // ❌ Blocked user login not allowed
        if ($user->status == 0) {
            auth('api')->logout();

            return response()->json([
                'success' => false,
                'message' => 'Your account has been blocked by Admin'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user'  => $user->load('gymDetail'),
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
            'data'    => auth('api')->user()->load('gymDetail')
        ]);
    }

    // ================= LOGOUT =================
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }
}