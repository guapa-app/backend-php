<?php

namespace App\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\GiftCardBackground;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Admin\V1\GiftCardBackgroundResource;

class GiftCardBackgroundController extends BaseApiController
{
    /**
     * Display a listing of gift card backgrounds.
     */
    public function index(Request $request): JsonResponse
    {
        $query = GiftCardBackground::with('media', 'uploadedBy');

        // Apply filters
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $backgrounds = $query->latest()->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'message' => __('api.success'),
            'data' => GiftCardBackgroundResource::collection($backgrounds->items()),
            'pagination' => [
                'current_page' => $backgrounds->currentPage(),
                'last_page' => $backgrounds->lastPage(),
                'per_page' => $backgrounds->perPage(),
                'total' => $backgrounds->total(),
            ]
        ]);
    }

    /**
     * Store a newly created gift card background.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'background_image' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // 5MB max
                'is_active' => 'boolean',
            ]);

            $background = GiftCardBackground::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'uploaded_by' => auth()->id(),
            ]);

            // Handle image upload
            if ($request->hasFile('background_image')) {
                $background->addMediaFromRequest('background_image')
                    ->toMediaCollection('gift_card_backgrounds');
            }

            $background->load('media', 'uploadedBy');

            return response()->json([
                'success' => true,
                'message' => __('api.created'),
                'data' => new GiftCardBackgroundResource($background)
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.validation_error'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified gift card background.
     */
    public function show($id): JsonResponse
    {
        try {
            $background = GiftCardBackground::with('media', 'uploadedBy')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => __('api.success'),
                'data' => new GiftCardBackgroundResource($background)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.not_found'),
            ], 404);
        }
    }

    /**
     * Update the specified gift card background.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $background = GiftCardBackground::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string|max:500',
                'background_image' => 'sometimes|file|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
                'is_active' => 'sometimes|boolean',
            ]);

            $background->update([
                'name' => $validated['name'] ?? $background->name,
                'description' => $validated['description'] ?? $background->description,
                'is_active' => $validated['is_active'] ?? $background->is_active,
            ]);

            // Handle image upload if provided
            if ($request->hasFile('background_image')) {
                // Remove old image
                $background->clearMediaCollection('gift_card_backgrounds');

                // Add new image
                $background->addMediaFromRequest('background_image')
                    ->toMediaCollection('gift_card_backgrounds');
            }

            $background->load('media', 'uploadedBy');

            return response()->json([
                'success' => true,
                'message' => __('api.updated'),
                'data' => new GiftCardBackgroundResource($background)
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.validation_error'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified gift card background.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $background = GiftCardBackground::findOrFail($id);

            // Remove associated media
            $background->clearMediaCollection('gift_card_backgrounds');

            $background->delete();

            return response()->json([
                'success' => true,
                'message' => __('api.deleted'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle the active status of a gift card background.
     */
    public function toggleStatus($id): JsonResponse
    {
        try {
            $background = GiftCardBackground::findOrFail($id);
            $background->update(['is_active' => !$background->is_active]);

            return response()->json([
                'success' => true,
                'message' => __('api.updated'),
                'data' => [
                    'id' => $background->id,
                    'is_active' => $background->is_active,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get active gift card backgrounds for public use.
     */
    public function active(): JsonResponse
    {
        $backgrounds = GiftCardBackground::active()
            ->with('media')
            ->get()
            ->map(function ($background) {
                return [
                    'id' => $background->id,
                    'name' => $background->name,
                    'description' => $background->description,
                    'image_url' => $background->image_url,
                    'thumbnail_url' => $background->thumbnail_url,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => __('api.success'),
            'data' => $backgrounds
        ]);
    }
}
