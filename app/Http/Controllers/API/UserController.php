<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            
            // Validate Request
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // Find User by Email
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials))
            {
                return ResponseFormatter::error('Unauthorized', 401);
            }

            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password))
            {
                throw new Exception('Invalid Password');
            }

            // Generate Token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // Return Response
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ],'Login Success');
        } catch (Exception $e) {
            return ResponseFormatter::error('Authentication Failed');
        }
    }

    public function register()
    {

        try {
            
        // Validate Request

        // Create User

        // Generate Token

        // Return Response
        
        } catch (Exception $error) {
            
            // Return Error Response

        }
        
    }
}
