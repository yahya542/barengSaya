<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/login',
        summary: 'Login',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'email@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Unauthorized')
        ]
    )]
    public function login(Request $request) { /* logic tetap sama */ }

    #[OA\Post(
        path: '/register',
        summary: 'Register',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Muhammad Yahya'),
                    new OA\Property(property: 'email', type: 'string', example: 'yahya@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', example: 'password123'),
                    // TAMBAHAN DROPDOWN ROLE DI SWAGGER
                    new OA\Property(
                        property: 'role', 
                        type: 'string', 
                        enum: ['teacher', 'student'], 
                        example: 'student'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 422, description: 'Unprocessable Content')
        ]
    )]
    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:teacher,student', 
        ]);

        // 2. Buat User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Assign Role (Spatie)
        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Registrasi berhasil sebagai ' . $request->role,
            'user' => $user->load('roles')
        ], 201);
    }

    #[OA\Get(
        path: '/profile',
        summary: 'Profile',
        tags: ['Auth'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'OK')
        ]
    )]
    public function profile(Request $request) {
        return response()->json($request->user()->load('roles'));
    }
     
    #[OA\Post(
        path: '/logout', 
        summary: 'Logout',
        tags: ['Auth'],
        security: [['bearerAuth' => []]], 
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Unauthorized')
        ]
    )]
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }  
}