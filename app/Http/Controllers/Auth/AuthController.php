<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function handleRegister(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

      
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        Auth::login($user);

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user
        ], 201);
    }
}