<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\GalleryComment;
use Illuminate\Http\Request;

class GalleryInteractionController extends Controller
{
    // Increment view count
    public function incrementView($id)
    {
        $gallery = GalleryItem::findOrFail($id);
        $gallery->increment('views_count');
        
        return response()->json([
            'success' => true,
            'views_count' => $gallery->views_count
        ]);
    }

    // Like gallery
    public function like($id)
    {
        $gallery = GalleryItem::findOrFail($id);
        $gallery->increment('likes_count');
        
        return response()->json([
            'success' => true,
            'likes_count' => $gallery->likes_count
        ]);
    }

    // Dislike gallery
    public function dislike($id)
    {
        $gallery = GalleryItem::findOrFail($id);
        $gallery->increment('dislikes_count');
        
        return response()->json([
            'success' => true,
            'dislikes_count' => $gallery->dislikes_count
        ]);
    }

    // Add comment
    public function addComment(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'comment' => 'required|string'
        ]);

        $gallery = GalleryItem::findOrFail($id);
        
        $comment = GalleryComment::create([
            'gallery_item_id' => $gallery->id,
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'comment' => $validated['comment'],
            'is_approved' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar Anda telah dikirim dan menunggu persetujuan admin',
            'comment' => $comment
        ]);
    }

    // Get comments
    public function getComments($id)
    {
        $gallery = GalleryItem::findOrFail($id);
        $comments = $gallery->approvedComments()->latest()->get();
        
        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
}
