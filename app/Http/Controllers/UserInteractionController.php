<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\GalleryReaction;
use App\Models\GalleryUserComment;
use App\Models\News;
use App\Models\NewsReaction;
use App\Models\NewsUserComment;
use App\Models\Teacher;
use App\Models\TeacherReaction;
use App\Models\TeacherComment;
use App\Models\Download;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserInteractionController extends Controller
{
    protected function getRecaptchaService()
    {
        return app(RecaptchaService::class);
    }

    // ============ GALLERY INTERACTIONS ============
    
    /**
     * Toggle like/dislike on gallery item
     */
    public function toggleGalleryReaction(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:like,dislike'
        ]);

        $galleryItem = GalleryItem::findOrFail($id);
        $userId = Auth::id();
        $type = $request->type;

        $reaction = GalleryReaction::where('user_id', $userId)
            ->where('gallery_item_id', $id)
            ->first();

        $userReaction = null;

        if ($reaction) {
            if ($reaction->type === $type) {
                // Remove reaction if clicking same button
                $reaction->delete();
                $userReaction = null; // No reaction after delete
                $message = 'Reaksi dihapus';
            } else {
                // Change reaction type
                $reaction->update(['type' => $type]);
                $userReaction = $type; // Return new type
                $message = 'Reaksi diubah menjadi ' . $type;
            }
        } else {
            // Create new reaction
            GalleryReaction::create([
                'user_id' => $userId,
                'gallery_item_id' => $id,
                'type' => $type
            ]);
            $userReaction = $type; // Return new type
            $message = 'Reaksi ' . $type . ' ditambahkan';
        }

        $likes = GalleryReaction::where('gallery_item_id', $id)->where('type', 'like')->count();
        $dislikes = GalleryReaction::where('gallery_item_id', $id)->where('type', 'dislike')->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'likes' => $likes,
            'dislikes' => $dislikes,
            'userReaction' => $userReaction
        ]);
    }

    /**
     * Add comment to gallery item
     */
    public function addGalleryComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $galleryItem = GalleryItem::findOrFail($id);

        $comment = \App\Models\Comment::create([
            'user_id' => Auth::id(),
            'commentable_type' => 'App\\Models\\GalleryItem',
            'commentable_id' => $id,
            'comment' => $request->comment,
            'is_approved' => true // Auto-approve for authenticated users
        ]);

        $comment->load('user');

        // Get updated comment count
        $commentsCount = \App\Models\Comment::where('commentable_type', 'App\\Models\\GalleryItem')
            ->where('commentable_id', $id)
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan',
            'comment' => [
                'id' => $comment->id,
                'user_name' => $comment->user->name,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->diffForHumans(),
                'can_delete' => true
            ],
            'commentsCount' => $commentsCount
        ]);
    }

    /**
     * Get gallery comments
     */
    public function getGalleryComments($id)
    {
        // Use new Comment model with polymorphic relationship
        // Only show approved comments or comments by current user
        $comments = \App\Models\Comment::where('commentable_type', 'App\\Models\\GalleryItem')
            ->where('commentable_id', $id)
            ->where(function($query) {
                $query->where('is_approved', true)
                      ->orWhere('user_id', Auth::id()); // Show own comments even if not approved
            })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'user_name' => $comment->user ? $comment->user->name : 'Unknown',
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'can_delete' => Auth::id() === $comment->user_id
                ];
            });

        return response()->json([
            'success' => true,
            'comments' => $comments,
            'count' => $comments->count()
        ]);
    }

    /**
     * Delete gallery comment
     */
    public function deleteGalleryComment($id)
    {
        $comment = \App\Models\Comment::findOrFail($id);

        if (Auth::id() !== $comment->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus komentar ini'
            ], 403);
        }

        $galleryItemId = $comment->commentable_id;
        $comment->delete();

        // Get updated comment count
        $commentsCount = \App\Models\Comment::where('commentable_type', 'App\\Models\\GalleryItem')
            ->where('commentable_id', $galleryItemId)
            ->where(function($query) {
                $query->where('is_approved', true)
                      ->orWhere('user_id', Auth::id());
            })
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus',
            'commentsCount' => $commentsCount
        ]);
    }

    /**
     * Download gallery image
     */
    public function downloadGalleryImage(Request $request, $id)
    {
        // CAPTCHA already verified in modal
        if (!$request->has('captcha_verified')) {
            return back()->with('error', 'Verifikasi CAPTCHA diperlukan.');
        }

        $galleryItem = GalleryItem::findOrFail($id);

        if (!$galleryItem->image) {
            return back()->with('error', 'Gambar tidak tersedia');
        }

        // Track download
        Download::create([
            'user_id' => Auth::id(),
            'downloadable_type' => GalleryItem::class,
            'downloadable_id' => $id,
            'file_path' => $galleryItem->image
        ]);

        $filePath = storage_path('app/public/' . $galleryItem->image);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        return response()->download($filePath, 'gallery-' . $id . '-' . basename($filePath));
    }

    // ============ NEWS INTERACTIONS ============
    
    /**
     * Toggle like/dislike on news
     */
    public function toggleNewsReaction(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:like,dislike'
        ]);

        $news = News::findOrFail($id);
        $userId = Auth::id();
        $type = $request->type;

        $reaction = NewsReaction::where('user_id', $userId)
            ->where('news_id', $id)
            ->first();

        $userReaction = null;

        if ($reaction) {
            if ($reaction->type === $type) {
                $reaction->delete();
                $userReaction = null; // No reaction after delete
                $message = 'Reaksi dihapus';
            } else {
                $reaction->update(['type' => $type]);
                $userReaction = $type; // Return new type
                $message = 'Reaksi diubah menjadi ' . $type;
            }
        } else {
            NewsReaction::create([
                'user_id' => $userId,
                'news_id' => $id,
                'type' => $type
            ]);
            $userReaction = $type; // Return new type
            $message = 'Reaksi ' . $type . ' ditambahkan';
        }

        $likes = NewsReaction::where('news_id', $id)->where('type', 'like')->count();
        $dislikes = NewsReaction::where('news_id', $id)->where('type', 'dislike')->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'likes' => $likes,
            'dislikes' => $dislikes,
            'userReaction' => $userReaction
        ]);
    }

    /**
     * Add comment to news
     */
    public function addNewsComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $news = News::findOrFail($id);

        $comment = \App\Models\Comment::create([
            'user_id' => Auth::id(),
            'commentable_type' => 'App\\Models\\News',
            'commentable_id' => $id,
            'comment' => $request->comment,
            'is_approved' => true // Auto-approve for authenticated users
        ]);

        $comment->load('user');

        // Get updated comment count
        $commentsCount = \App\Models\Comment::where('commentable_type', 'App\\Models\\News')
            ->where('commentable_id', $id)
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan',
            'comment' => [
                'id' => $comment->id,
                'user_name' => $comment->user->name,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->diffForHumans(),
                'can_delete' => true
            ],
            'commentsCount' => $commentsCount
        ]);
    }

    /**
     * Get news comments
     */
    public function getNewsComments($id)
    {
        // Use new Comment model with polymorphic relationship
        // Only show approved comments or comments by current user
        $comments = \App\Models\Comment::where('commentable_type', 'App\\Models\\News')
            ->where('commentable_id', $id)
            ->where(function($query) {
                $query->where('is_approved', true)
                      ->orWhere('user_id', Auth::id()); // Show own comments even if not approved
            })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'user_name' => $comment->user ? $comment->user->name : 'Unknown',
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'can_delete' => Auth::id() === $comment->user_id
                ];
            });

        return response()->json([
            'success' => true,
            'comments' => $comments,
            'count' => $comments->count()
        ]);
    }

    /**
     * Delete news comment
     */
    public function deleteNewsComment($id)
    {
        $comment = \App\Models\Comment::findOrFail($id);

        if (Auth::id() !== $comment->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus komentar ini'
            ], 403);
        }

        $newsId = $comment->commentable_id;
        $comment->delete();

        // Get updated comment count
        $commentsCount = \App\Models\Comment::where('commentable_type', 'App\\Models\\News')
            ->where('commentable_id', $newsId)
            ->where(function($query) {
                $query->where('is_approved', true)
                      ->orWhere('user_id', Auth::id());
            })
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus',
            'commentsCount' => $commentsCount
        ]);
    }

    /**
     * Download news image
     */
    public function downloadNewsImage(Request $request, $id)
    {
        // CAPTCHA already verified in modal
        if (!$request->has('captcha_verified')) {
            return back()->with('error', 'Verifikasi CAPTCHA diperlukan.');
        }

        $news = News::findOrFail($id);

        if (!$news->image) {
            return back()->with('error', 'Gambar tidak tersedia');
        }

        // Track download
        Download::create([
            'user_id' => Auth::id(),
            'downloadable_type' => News::class,
            'downloadable_id' => $id,
            'file_path' => $news->image
        ]);

        $filePath = storage_path('app/public/' . $news->image);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        return response()->download($filePath, 'news-' . $id . '-' . basename($filePath));
    }

    // ============ TEACHER INTERACTIONS ============
    
    /**
     * Toggle like/dislike on teacher
     */
    public function toggleTeacherReaction(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:like,dislike'
        ]);

        $teacher = Teacher::findOrFail($id);
        $userId = Auth::id();
        $type = $request->type;

        $reaction = TeacherReaction::where('user_id', $userId)
            ->where('teacher_id', $id)
            ->first();

        $userReaction = null;

        if ($reaction) {
            if ($reaction->type === $type) {
                $reaction->delete();
                $userReaction = null; // No reaction after delete
                $message = 'Reaksi dihapus';
            } else {
                $reaction->update(['type' => $type]);
                $userReaction = $type; // Return new type
                $message = 'Reaksi diubah menjadi ' . $type;
            }
        } else {
            TeacherReaction::create([
                'user_id' => $userId,
                'teacher_id' => $id,
                'type' => $type
            ]);
            $userReaction = $type; // Return new type
            $message = 'Reaksi ' . $type . ' ditambahkan';
        }

        $likes = TeacherReaction::where('teacher_id', $id)->where('type', 'like')->count();
        $dislikes = TeacherReaction::where('teacher_id', $id)->where('type', 'dislike')->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'likes' => $likes,
            'dislikes' => $dislikes,
            'userReaction' => $userReaction
        ]);
    }

    /**
     * Add comment to teacher
     */
    public function addTeacherComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $teacher = Teacher::findOrFail($id);

        $comment = \App\Models\Comment::create([
            'user_id' => Auth::id(),
            'commentable_type' => 'App\\Models\\Teacher',
            'commentable_id' => $id,
            'comment' => $request->comment,
            'is_approved' => true // Auto-approve for authenticated users
        ]);

        $comment->load('user');

        // Get updated comment count
        $commentsCount = \App\Models\Comment::where('commentable_type', 'App\\Models\\Teacher')
            ->where('commentable_id', $id)
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan',
            'comment' => [
                'id' => $comment->id,
                'user_name' => $comment->user->name,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->diffForHumans(),
                'can_delete' => true
            ],
            'commentsCount' => $commentsCount
        ]);
    }

    /**
     * Get teacher comments
     */
    public function getTeacherComments($id)
    {
        // Use new Comment model with polymorphic relationship
        // Only show approved comments or comments by current user
        $comments = \App\Models\Comment::where('commentable_type', 'App\\Models\\Teacher')
            ->where('commentable_id', $id)
            ->where(function($query) {
                $query->where('is_approved', true)
                      ->orWhere('user_id', Auth::id()); // Show own comments even if not approved
            })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'user_name' => $comment->user ? $comment->user->name : 'Unknown',
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'can_delete' => Auth::id() === $comment->user_id
                ];
            });

        return response()->json([
            'success' => true,
            'comments' => $comments,
            'count' => $comments->count()
        ]);
    }

    /**
     * Delete teacher comment
     */
    public function deleteTeacherComment($id)
    {
        $comment = \App\Models\Comment::findOrFail($id);

        if (Auth::id() !== $comment->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus komentar ini'
            ], 403);
        }

        $teacherId = $comment->commentable_id;
        $comment->delete();

        // Get updated comment count
        $commentsCount = \App\Models\Comment::where('commentable_type', 'App\\Models\\Teacher')
            ->where('commentable_id', $teacherId)
            ->where(function($query) {
                $query->where('is_approved', true)
                      ->orWhere('user_id', Auth::id());
            })
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus',
            'commentsCount' => $commentsCount
        ]);
    }
}
