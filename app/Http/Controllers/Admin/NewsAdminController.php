<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NewsAdminController extends Controller
{
    /**
     * Sync storage files to public (for Windows php artisan serve)
     */
    private function syncStorageToPublic($folder = 'news')
    {
        $source = storage_path("app/public/{$folder}");
        $destination = public_path("storage/{$folder}");
        
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }
        
        if (File::exists($source)) {
            $files = File::files($source);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                $destFile = $destination . '/' . $filename;
                File::copy($file->getPathname(), $destFile);
            }
        }
    }
    
    public function index()
    {
        $news = News::orderByDesc('published_at')->orderByDesc('created_at')->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:academic,sports,events,announcements,general',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Ensure published_at is set
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = Carbon::now();
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
            $validated['image'] = $imagePath;
            $this->syncStorageToPublic('news');
        }

        $validated['author'] = Auth::check() ? Auth::user()->name : 'Admin';
        $news = News::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:academic,sports,events,announcements,general',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Ensure published_at is set when publishing
        if ($validated['status'] === 'published' && empty($news->published_at)) {
            $validated['published_at'] = Carbon::now();
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($news->image) {
                $oldImagePath = storage_path('app/public/' . $news->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
            
            $imagePath = $request->file('image')->store('news', 'public');
            $validated['image'] = $imagePath;
            $this->syncStorageToPublic('news');
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        if ($news->image) {
            $imagePath = storage_path('app/public/' . $news->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }
}