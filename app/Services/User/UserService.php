<?php

namespace App\Services\User;

use App\Jobs\WelcomeEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function register(array $data)
    {
        try {
            $user = User::create($data);
            WelcomeEmailJob::dispatch($user->email);
            return response()->json([
                'status'  => 201,
                'message' => 'User registered successfully.',
                'error'   => false,
                'data'    => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'User registration failed.',
                'error'   => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function login(array $data)
    {
        try {
            $user = User::where('email', $data['email'])->first();

            // Check Valid User | Valid Password
            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'status'    => 401,
                    'message'   => 'Invalid email or password.',
                    'error'     => false,
                    'data'      => null
                ], 401);
            }

            // Revoke previous tokens
            $user->tokens()->delete();

            $token = $user->createToken('auth_token');

            return response()->json([
                'status'    => 200,
                'message'   => 'Login successful.',
                'error'     => false,
                'data'      => [
                    'user'  => $user,
                    'token' => $token->plainTextToken
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Login failed.',
                'error'   => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function logout(object $user)
    {
        try {
            // Revoke token
            $user->currentAccessToken()->delete();

            return response()->json([
                'status'    => 200,
                'message'   => 'Logout successful.',
                'error'     => false,
                'data'      => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Logout failed.',
                'error'   => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'status'    => 404,
                    'message'   => 'User not found.',
                    'error'     => false,
                    'data'      => null,
                ], 404);
            }

            return response()->json([
                'status'    => 200,
                'message'   => 'User retrieved successfully.',
                'error'     => false,
                'data'      => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Failed to retrieve user.',
                'error'   => $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }
}
