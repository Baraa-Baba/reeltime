<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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

        // Check if email is verified
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email before logging in. Check your inbox for a verification link.',
                'requires_verification' => true,
                'email' => $user->email,
            ], 403);
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
                'role' => $user->role, 
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

        // Send email verification
        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully! Please check your email to verify your account.',
            'requires_verification' => true,
            'user' => [
                'id' => $user->user_id,
                'username' => $user->username,
                'email' => $user->email,
                'img' => $user->profile_image,
                'since' => now()->year,
                'role' => $user->role,
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
                    'role' => $user->role,
                ],
            ]);
        }

        return response()->json([
            'authenticated' => false,
        ]);
    }

    /**
     * Send reset password link via email.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink([
            'email' => $request->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'If this email exists, a reset link has been sent.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __($status),
        ], 422);
    }

    /**
     * Redirect reset links back into the auth modal flow.
     */
    public function showResetPassword(Request $request, string $token)
    {
        $email = $request->query('email', '');

        return redirect()->route('home', [
            'reset_token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Update password using reset token.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset successful. You can now log in.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __($status),
        ], 422);
    }

    /**
     * Verify the user's email address.
     */
    public function verifyEmail(Request $request)
    {
        try {
            $user = User::findOrFail($request->route('id'));
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Invalid verification link.');
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect('/')->with('error', 'Invalid or expired verification link. Please request a new one.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('info', 'Email already verified.');
        }

        $user->markEmailAsVerified();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('success', 'Email verified successfully! Welcome to ReelTime!');
    }

    /**
     * Resend the email verification notification.
     */
    public function resendVerificationEmail(Request $request)
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email is already verified.',
            ], 422);
        }

        Auth::user()->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Verification link sent! Please check your email.',
        ]);
    }
}
