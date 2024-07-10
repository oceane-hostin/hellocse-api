<?php

namespace App\Http\Controllers;



use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function login(AdminLoginRequest $request) : JsonResponse
    {
        // Request Validation
        $validatedData = $request->validated();

        // Check if admin exist and password is correct
        $admin = Admin::query()->where('username', $validatedData['username'])->first();
        if(!$admin) {
            return response()->json([
                "error" => "Please verify your username",
            ], Response::HTTP_NOT_FOUND);
        }

        $credentials = request(['username','password']);
        if(!Auth::attempt($credentials))
        {
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        }

        // Generating token sanctum
        $token = $request->user()->createToken('Personal Access Token');

        return response()->json([
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }
}
