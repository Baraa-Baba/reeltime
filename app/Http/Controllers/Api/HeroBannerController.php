<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HeroBannerController extends Controller
{
    /**
     * Get all hero banners
     */
    public function index()
    {
        $banners = HeroBanner::orderBy('position')->get();
        $activeCount = HeroBanner::where('is_active', true)->count();
        
        return response()->json([
            'success' => true,
            'data' => $banners,
            'active_count' => $activeCount,
            'max_active' => 3
        ]);
    }

    /**
     * Store a new hero banner.
     */
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'cta_label' => 'nullable|string|max:100',
        'cta_route_name' => 'nullable|string|max:100',
        'background_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'position' => 'nullable|integer|min:0',
    ]);

    try {
        $file = $request->file('background_image');
        $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
        
        // Save directly to public/imgs/
        $file->move(public_path('imgs'), $filename);
        $imagePath = '/imgs/' . $filename;
        
        $maxPosition = HeroBanner::max('position') ?? 0;

        $activeCount = HeroBanner::where('is_active', true)->count();
        $isActive = ($activeCount == 0); 
        $banner = HeroBanner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'cta_label' => $request->cta_label,
            'cta_route_name' => $request->cta_route_name,
            'background_image' => $imagePath,
            'position' => $request->position ?? $maxPosition + 1,
            'is_active' => $isActive,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Hero banner added successfully',
            'data' => $banner
        ], 201);
    } catch (\Exception $e) {
        \Log::error('Hero banner creation failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to add banner: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Update an existing hero banner.
     */
   
public function update(Request $request, $id)
{
    $banner = HeroBanner::findOrFail($id);
    
    $request->validate([
        'title' => 'required|string|max:255',
        'subtitle' => 'nullable|string|max:255',
        'cta_label' => 'nullable|string|max:100',
        'cta_route_name' => 'nullable|string|max:100',
        'position' => 'nullable|integer|min:0',
        'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);
    
    try {
        $data = $request->only(['title', 'subtitle', 'cta_label', 'cta_route_name', 'position']);
        
        if ($request->hasFile('background_image')) {
            // Delete old image if exists
            if ($banner->background_image && file_exists(public_path($banner->background_image))) {
                unlink(public_path($banner->background_image));
            }
            
            $file = $request->file('background_image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('imgs'), $filename);
            $data['background_image'] = '/imgs/' . $filename;
        }
        
        $banner->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Hero banner updated successfully',
            'data' => $banner
        ]);
    } catch (\Exception $e) {
        \Log::error('Hero banner update failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to update banner: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Toggle active status (max 3 active).
     */
    public function toggleActive($id)
    {
        $banner = HeroBanner::findOrFail($id);
        $activeCount = HeroBanner::where('is_active', true)->count();
        if ($banner->is_active && $activeCount == 1) {
            return response()->json([
                'success' => false,
                'message' => 'At least one banner must be active. Cannot deactivate the only active banner.'
            ], 422);
        }
        if (!$banner->is_active && $activeCount >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum 3 active banners allowed. Deactivate one first.'
            ], 422);
        }
        
        $banner->is_active = !$banner->is_active;
        $banner->save();
        
        return response()->json([
            'success' => true,
            'message' => $banner->is_active ? 'Banner activated' : 'Banner deactivated',
            'is_active' => $banner->is_active
        ]);
    }

    /**
     * Delete a hero banner.
     */
    public function destroy($id)
{
    $banner = HeroBanner::findOrFail($id);
    
    $activeCount = HeroBanner::where('is_active', true)->count();
    if ($banner->is_active && $activeCount == 1) {
        return response()->json([
            'success' => false,
            'message' => 'Cannot delete the only active banner. Please activate another banner first.'
        ], 422);
    }
    // Delete image file from public/imgs/
    if ($banner->background_image && file_exists(public_path($banner->background_image))) {
        unlink(public_path($banner->background_image));
    }
    
    $banner->delete();
    
    return response()->json([
        'success' => true,
        'message' => 'Hero banner deleted successfully'
    ]);
}

    /**
     * Reorder banners (drag & drop).
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'exists:hero_banners,hero_banner_id'
        ]);
        
        try {
            foreach ($request->order as $index => $id) {
                HeroBanner::where('hero_banner_id', $id)->update(['position' => $index]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Banners reordered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder: ' . $e->getMessage()
            ], 500);
        }
    }
}