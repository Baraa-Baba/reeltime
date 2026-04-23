<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Get authenticated user profile
     */
    public function profile(Request $request)
    {
        $user = $request->user()->load('watchlists.movie', 'bookings', 'ratings');
        
        return response()->json($user);
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        try {
        $user = $request->user();
        
        $validated = $request->validate([
            'username' => 'string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'email' => 'email|unique:users,email,' . $user->user_id . ',user_id',
            
     ]);

            if (isset($validated['username'])) $user->username = $validated['username'];
            if (isset($validated['email'])) $user->email = $validated['email'];
            $user->save();
            $imageUrl = $user->profile_image;
            if (!$imageUrl || !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $imageUrl = $imageUrl ? asset($imageUrl) : $this->defaultAvatar($user->username);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile updated.',
                'user' => [
                    'id' => $user->user_id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'img' => $imageUrl,
                    'since' => $user->member_since?->year ?? $user->created_at->year,
                    'role' => $user->role,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

        /**
         * Upload a new profile image
         */
        public function uploadProfileImage(Request $request)
    {
        try {
            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $user = $request->user();
             if ($user->profile_image && !filter_var($user->profile_image, FILTER_VALIDATE_URL)) {
                $oldPath = str_replace('/storage/', '', $user->profile_image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('profile_image');
            $filename = time() . '_' . Str::slug($user->username) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profile_images', $filename, 'public');
            $url = Storage::url($path); 

            $user->profile_image = $url;
            $user->save();

        return response()->json([
                'success' => true,
                'message' => 'Profile image updated.',
                'profile_image' => $url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

        private function defaultAvatar($username)
        {
            return 'https://robohash.org/' . urlencode($username);
        }


    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        try {
            $user = $request->user();
            
            $validated = $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed',
            ]);
            
           
            if (!\Illuminate\Support\Facades\Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect.'
                ], 422);
            }
            
           
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['new_password']);
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to change password: ' . $e->getMessage()
            ], 500);
        }
    }
}
