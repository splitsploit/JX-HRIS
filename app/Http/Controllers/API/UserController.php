<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            // Generate Token

            // Return Response
            
        } catch (Exception $e) {
            //throw $th;
        }
    }
}
