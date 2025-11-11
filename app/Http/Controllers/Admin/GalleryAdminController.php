<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GalleryAdminController extends Controller
{
    /**
     * Sync storage files to public (for Windows php artisan serve)
     */
    private function syncStorageToPublic($folder = 'gallery')
    {
        $source = storage_path("app/public/{$folder}");
        $destination = public_path("storage/{$folder}");
        
        // Create destination if not exists
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }
        
        // Copy all files from source to destination
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
        $items = GalleryItem::orderByDesc('created_at')->paginate(12);
        return view('admin.gallery.index', compact('items'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:10240',
            'description' => 'nullable|string',
            'taken_at' => 'nullable|date',
            'status' => 'required|in:draft,published'
        ]);

        $path = $request->file('image')->store('gallery', 'public');
        $validated['image'] = $path;

        GalleryItem::create($validated);
        
        // Auto-sync to public folder (for Windows php artisan serve)
        $this->syncStorageToPublic('gallery');
        
        return redirect()->route('admin.gallery.index')->with('success', 'Foto ditambahkan');
    }

    public function edit(GalleryItem $gallery)
    {
        return view('admin.gallery.edit', ['item' => $gallery]);
    }

    public function update(Request $request, GalleryItem $gallery)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'image' => 'nullable|image|max:10240',
            'description' => 'nullable|string',
            'taken_at' => 'nullable|date',
            'status' => 'sometimes|required|in:draft,published'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('gallery', 'public');
            $validated['image'] = $path;
            
            // Auto-sync to public folder (for Windows php artisan serve)
            $this->syncStorageToPublic('gallery');
        }

        $gallery->update($validated);
        return redirect()->route('admin.gallery.index')->with('success', 'Foto diperbarui');
    }

    public function destroy(GalleryItem $gallery)
    {
        $gallery->delete();
        return redirect()->route('admin.gallery.index')->with('success', 'Foto dihapus');
    }
}


