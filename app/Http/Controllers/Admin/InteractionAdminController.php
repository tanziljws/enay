<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryReaction;
use App\Models\NewsReaction;
use App\Models\TeacherReaction;
use App\Models\Download;
use Illuminate\Http\Request;

class InteractionAdminController extends Controller
{
    /**
     * Dashboard - Overview semua interaksi
     */
    public function dashboard()
    {
        // Total stats
        $stats = [
            'total_gallery_likes' => GalleryReaction::where('type', 'like')->count(),
            'total_gallery_dislikes' => GalleryReaction::where('type', 'dislike')->count(),
            'total_gallery_comments' => \App\Models\Comment::where('commentable_type', 'App\\Models\\GalleryItem')->count(),
            'total_news_likes' => NewsReaction::where('type', 'like')->count(),
            'total_news_dislikes' => NewsReaction::where('type', 'dislike')->count(),
            'total_news_comments' => \App\Models\Comment::where('commentable_type', 'App\\Models\\News')->count(),
            'total_teacher_likes' => TeacherReaction::where('type', 'like')->count(),
            'total_teacher_dislikes' => TeacherReaction::where('type', 'dislike')->count(),
            'total_teacher_comments' => \App\Models\Comment::where('commentable_type', 'App\\Models\\Teacher')->count(),
            'total_downloads' => Download::count(),
            'total_users' => \App\Models\User::where('role', 'user')->count(),
        ];
        
        // Weekly stats (last 7 days)
        $weekAgo = now()->subDays(7);
        $weeklyStats = [
            'gallery_likes' => GalleryReaction::where('type', 'like')->where('created_at', '>=', $weekAgo)->count(),
            'gallery_dislikes' => GalleryReaction::where('type', 'dislike')->where('created_at', '>=', $weekAgo)->count(),
            'gallery_comments' => \App\Models\Comment::where('commentable_type', 'App\\Models\\GalleryItem')->where('created_at', '>=', $weekAgo)->count(),
            'news_likes' => NewsReaction::where('type', 'like')->where('created_at', '>=', $weekAgo)->count(),
            'news_dislikes' => NewsReaction::where('type', 'dislike')->where('created_at', '>=', $weekAgo)->count(),
            'news_comments' => \App\Models\Comment::where('commentable_type', 'App\\Models\\News')->where('created_at', '>=', $weekAgo)->count(),
            'teacher_likes' => TeacherReaction::where('type', 'like')->where('created_at', '>=', $weekAgo)->count(),
            'teacher_dislikes' => TeacherReaction::where('type', 'dislike')->where('created_at', '>=', $weekAgo)->count(),
            'teacher_comments' => \App\Models\Comment::where('commentable_type', 'App\\Models\\Teacher')->where('created_at', '>=', $weekAgo)->count(),
            'downloads' => Download::where('created_at', '>=', $weekAgo)->count(),
            'new_users' => \App\Models\User::where('role', 'user')->where('created_at', '>=', $weekAgo)->count(),
        ];
        
        // Recent comments
        $recentGalleryComments = \App\Models\Comment::where('commentable_type', 'App\\Models\\GalleryItem')
            ->with(['user', 'commentable'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $recentNewsComments = \App\Models\Comment::where('commentable_type', 'App\\Models\\News')
            ->with(['user', 'commentable'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $recentTeacherComments = \App\Models\Comment::where('commentable_type', 'App\\Models\\Teacher')
            ->with(['user', 'commentable'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.interactions.dashboard', compact(
            'stats',
            'weeklyStats',
            'recentGalleryComments',
            'recentNewsComments',
            'recentTeacherComments'
        ));
    }
    
    /**
     * Gallery Interactions
     */
    public function galleryInteractions()
    {
        $reactions = GalleryReaction::with(['user', 'galleryItem'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $comments = \App\Models\Comment::where('commentable_type', 'App\\Models\\GalleryItem')
            ->with(['user', 'commentable'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.interactions.gallery', compact('reactions', 'comments'));
    }
    
    /**
     * News Interactions
     */
    public function newsInteractions()
    {
        $reactions = NewsReaction::with(['user', 'news'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $comments = \App\Models\Comment::where('commentable_type', 'App\\Models\\News')
            ->with(['user', 'commentable'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.interactions.news', compact('reactions', 'comments'));
    }
    
    /**
     * Teacher Interactions
     */
    public function teacherInteractions()
    {
        $reactions = TeacherReaction::with(['user', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $comments = \App\Models\Comment::where('commentable_type', 'App\\Models\\Teacher')
            ->with(['user', 'commentable'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.interactions.teachers', compact('reactions', 'comments'));
    }
    
    /**
     * Downloads History
     */
    public function downloads()
    {
        $downloads = Download::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.interactions.downloads', compact('downloads'));
    }
    
    /**
     * Delete Comment (Gallery)
     */
    public function deleteGalleryComment($id)
    {
        $comment = \App\Models\Comment::where('commentable_type', 'App\\Models\\GalleryItem')
            ->findOrFail($id);
        $comment->delete();
        
        return back()->with('success', 'Komentar berhasil dihapus');
    }
    
    /**
     * Delete Comment (News)
     */
    public function deleteNewsComment($id)
    {
        $comment = \App\Models\Comment::where('commentable_type', 'App\\Models\\News')
            ->findOrFail($id);
        $comment->delete();
        
        return back()->with('success', 'Komentar berhasil dihapus');
    }
    
    /**
     * Delete Comment (Teacher)
     */
    public function deleteTeacherComment($id)
    {
        $comment = \App\Models\Comment::where('commentable_type', 'App\\Models\\Teacher')
            ->findOrFail($id);
        $comment->delete();
        
        return back()->with('success', 'Komentar berhasil dihapus');
    }
}
