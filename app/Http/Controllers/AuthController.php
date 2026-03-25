<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle login via AJAX.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Try to authenticate with username
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password. Please try again.',
            ], 401);
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'user' => [
                'id' => $user->user_id,
                'username' => $user->username,
                'email' => $user->email,
                'img' => $user->profile_image ?? 'https://robohash.org/' . urlencode($user->username),
                'since' => $user->member_since ? $user->member_since->year : $user->created_at->year,
            ],
        ]);
    }

    /**
     * Handle registration via AJAX.
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|min:3|max:30|unique:users,username|regex:/^[a-zA-Z0-9_]+$/',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ], [
                'username.unique' => 'This username is already taken.',
                'username.regex' => 'Username can only contain letters, numbers, and underscores.',
                'username.min' => 'Username must be at least 3 characters.',
                'email.unique' => 'This email is already registered.',
                'email.email' => 'Please enter a valid email address.',
                'password.min' => 'Password must be at least 6 characters.',
                'password.confirmed' => 'Passwords do not match.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors(),
            ], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password, // Auto-hashed via model cast
            'profile_image' => 'https://robohash.org/' . urlencode($request->username),
            'member_since' => now(),
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully! Welcome to ReelTime!',
            'user' => [
                'id' => $user->user_id,
                'username' => $user->username,
                'email' => $user->email,
                'img' => $user->profile_image,
                'since' => now()->year,
            ],
        ]);
    }

    /**
     * Handle logout via AJAX or regular request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully.',
            ]);
        }

        return redirect('/');
    }

    /**
     * Get the currently authenticated user data (for JS).
     */
    public function currentUser(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => $user->user_id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'img' => $user->profile_image ?? 'https://robohash.org/' . urlencode($user->username),
                    'since' => $user->member_since ? $user->member_since->year : $user->created_at->year,
                ],
            ]);
        }

        return response()->json([
            'authenticated' => false,
        ]);
    }
}
