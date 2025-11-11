<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

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
        $news = News::orderByDesc('published_at')->paginate(10);
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
            'author' => 'required|string|max:255',
            'category' => 'required|in:academic,sports,events,announcements,general',
            'status' => 'required|in:draft,published,archived',
            'image' => 'nullable|image|max:10240',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $validated['image'] = $path;
            
            // Auto-sync to public folder
            $this->syncStorageToPublic('news');
        }

        if (($validated['status'] ?? null) === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        News::create($validated);
        return redirect()->route('admin.news.index')->with('success', 'News created');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'author' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|in:academic,sports,events,announcements,general',
            'status' => 'sometimes|required|in:draft,published,archived',
            'image' => 'nullable|image|max:10240',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $validated['image'] = $path;
            
            // Auto-sync to public folder
            $this->syncStorageToPublic('news');
        }

        if (($validated['status'] ?? null) === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $news->update($validated);
        return redirect()->route('admin.news.index')->with('success', 'News updated');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'News deleted');
    }
}


