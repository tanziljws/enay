<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsComment;
use Illuminate\Http\Request;

class NewsInteractionController extends Controller
{
    // Increment view count
    public function incrementView($id)
    {
        $news = News::findOrFail($id);
        $news->increment('views_count');
        
        return response()->json([
            'success' => true,
            'views_count' => $news->views_count
        ]);
    }

    // Like news
    public function like($id)
    {
        $news = News::findOrFail($id);
        $news->increment('likes_count');
        
        return response()->json([
            'success' => true,
            'likes_count' => $news->likes_count
        ]);
    }

    // Dislike news
    public function dislike($id)
    {
        $news = News::findOrFail($id);
        $news->increment('dislikes_count');
        
        return response()->json([
            'success' => true,
            'dislikes_count' => $news->dislikes_count
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

        $news = News::findOrFail($id);
        
        $comment = NewsComment::create([
            'news_id' => $news->id,
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
        $news = News::findOrFail($id);
        $comments = $news->approvedComments()->latest()->get();
        
        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
}
