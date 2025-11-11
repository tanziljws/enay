<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = News::query();

        // Filter by status (default to published for public access)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'published');
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Search by title or content
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Sort by published date (newest first)
        $query->orderBy('published_at', 'desc');

        $news = $query->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|string',
                'author' => 'required|string|max:255',
                'category' => 'required|in:academic,sports,events,announcements,general',
                'status' => 'nullable|in:draft,published,archived',
                'published_at' => 'nullable|date'
            ]);

            // Set published_at if status is published and published_at is not provided
            if ($validated['status'] === 'published' && empty($validated['published_at'])) {
                $validated['published_at'] = now();
            }

            $news = News::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'News created successfully',
                'data' => $news
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $news = News::findOrFail($id);

        // Only show published news to public, unless it's an admin request
        if ($news->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $news = News::findOrFail($id);

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'content' => 'sometimes|string',
                'image' => 'nullable|string',
                'author' => 'sometimes|string|max:255',
                'category' => 'sometimes|in:academic,sports,events,announcements,general',
                'status' => 'sometimes|in:draft,published,archived',
                'published_at' => 'nullable|date'
            ]);

            // Set published_at if status is being changed to published
            if (isset($validated['status']) && $validated['status'] === 'published' && empty($validated['published_at'])) {
                $validated['published_at'] = now();
            }

            $news->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'News updated successfully',
                'data' => $news
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $news = News::findOrFail($id);
        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'News deleted successfully'
        ]);
    }

    /**
     * Get latest news for homepage
     */
    public function latest(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 5);
        
        $news = News::where('status', 'published')
                    ->orderBy('published_at', 'desc')
                    ->limit($limit)
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }
}
