<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('status', 'published')
            ->latest('published_at')
            ->paginate(12);
        
        return view('news.index', compact('news'));
    }
    
    public function show($id)
    {
        $news = News::with(['reactions', 'comments.user'])
            ->withCount(['reactions as likes_count' => function($query) {
                $query->where('type', 'like');
            }])
            ->withCount(['reactions as dislikes_count' => function($query) {
                $query->where('type', 'dislike');
            }])
            ->withCount('comments')
            ->findOrFail($id);
        
        // Get user's reaction if authenticated
        if (auth()->check()) {
            $userReaction = $news->reactions()
                ->where('user_id', auth()->id())
                ->first();
            $news->user_reaction = $userReaction ? $userReaction->type : null;
        } else {
            $news->user_reaction = null;
        }
        
        // Get related news
        $relatedNews = News::where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();
        
        return view('news.show', compact('news', 'relatedNews'));
    }
}
