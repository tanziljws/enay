<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsComment;
use App\Models\GalleryComment;
use Illuminate\Http\Request;

class CommentAdminController extends Controller
{
    // News Comments
    public function newsComments()
    {
        $comments = NewsComment::with('news')->latest()->paginate(20);
        return view('admin.comments.news', compact('comments'));
    }

    public function approveNewsComment($id)
    {
        $comment = NewsComment::findOrFail($id);
        $comment->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Komentar berhasil disetujui');
    }

    public function deleteNewsComment($id)
    {
        $comment = NewsComment::findOrFail($id);
        $comment->delete();
        return redirect()->back()->with('success', 'Komentar berhasil dihapus');
    }

    // Gallery Comments
    public function galleryComments()
    {
        $comments = GalleryComment::with('galleryItem')->latest()->paginate(20);
        return view('admin.comments.gallery', compact('comments'));
    }

    public function approveGalleryComment($id)
    {
        $comment = GalleryComment::findOrFail($id);
        $comment->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Komentar berhasil disetujui');
    }

    public function deleteGalleryComment($id)
    {
        $comment = GalleryComment::findOrFail($id);
        $comment->delete();
        return redirect()->back()->with('success', 'Komentar berhasil dihapus');
    }

    // Statistics
    public function statistics(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $newsStats = \App\Models\News::selectRaw('
            SUM(views_count) as total_views,
            SUM(likes_count) as total_likes,
            SUM(dislikes_count) as total_dislikes,
            COUNT(*) as total_news
        ')
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->first();

        $galleryStats = \App\Models\GalleryItem::selectRaw('
            SUM(views_count) as total_views,
            SUM(likes_count) as total_likes,
            SUM(dislikes_count) as total_dislikes,
            COUNT(*) as total_items
        ')
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->first();

        $newsComments = NewsComment::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $galleryComments = GalleryComment::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        return view('admin.statistics', compact(
            'newsStats', 
            'galleryStats', 
            'newsComments', 
            'galleryComments',
            'month',
            'year'
        ));
    }
}
