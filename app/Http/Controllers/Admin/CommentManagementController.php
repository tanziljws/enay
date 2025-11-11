<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentManagementController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        
        $query = Comment::with(['user', 'commentable'])
            ->orderBy('created_at', 'desc');
        
        // Filter by type
        if ($type !== 'all') {
            $modelMap = [
                'gallery' => 'App\Models\GalleryItem',
                'news' => 'App\Models\News',
                'teacher' => 'App\Models\Teacher',
            ];
            
            if (isset($modelMap[$type])) {
                $query->where('commentable_type', $modelMap[$type]);
            }
        }
        
        $comments = $query->paginate(20);
        
        return view('admin.comments.index', compact('comments', 'type'));
    }
    
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        
        return redirect()->back()->with('success', 'Komentar berhasil dihapus');
    }
    
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('comment_ids', []);
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada komentar yang dipilih');
        }
        
        Comment::whereIn('id', $ids)->delete();
        
        return redirect()->back()->with('success', count($ids) . ' komentar berhasil dihapus');
    }
}
