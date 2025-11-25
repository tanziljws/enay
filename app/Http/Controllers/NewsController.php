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
        $news = News::with(['newsReactions', 'comments.user'])
            ->withCount([
                'newsReactions as likes_count' => function($query) {
                    $query->where('type', 'like');
                },
                'newsReactions as dislikes_count' => function($query) {
                    $query->where('type', 'dislike');
                },
                'comments as comments_count' => function($query) {
                    $query->where('is_approved', true);
                }
            ])
            ->findOrFail($id);
        
        // user_reaction is automatically available via accessor
        
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
