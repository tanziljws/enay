<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MajorAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $majors = Major::ordered()->get();
        return view('admin.majors.index', compact('majors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.majors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:majors,code',
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'category' => 'required|string|max:255',
            'student_count' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'required|integer|min:0'
        ], [
            'image.max' => 'Ukuran gambar maksimal 10MB',
            'image.mimes' => 'Format gambar harus JPG, PNG, atau GIF',
            'image.image' => 'File harus berupa gambar'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('images/majors', 'public');
        }

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        Major::create($validated);

        return redirect()->route('admin.majors.index')
            ->with('success', 'Program keahlian berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Major $major)
    {
        return view('admin.majors.show', compact('major'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Major $major)
    {
        return view('admin.majors.edit', compact('major'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:majors,code,' . $major->id,
            'name' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'category' => 'required|string|max:255',
            'student_count' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'required|integer|min:0'
        ], [
            'image.max' => 'Ukuran gambar maksimal 10MB',
            'image.mimes' => 'Format gambar harus JPG, PNG, atau GIF',
            'image.image' => 'File harus berupa gambar'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists and is not a default image
            if ($major->image && !str_starts_with($major->image, 'images/')) {
                Storage::disk('public')->delete($major->image);
            }
            $validated['image'] = $request->file('image')->store('images/majors', 'public');
        }

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            // Delete old image if it exists and is not a default image
            if ($major->image && !str_starts_with($major->image, 'images/')) {
                Storage::disk('public')->delete($major->image);
            }
            $validated['image'] = null;
        }

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        $major->update($validated);

        return redirect()->route('admin.majors.index')
            ->with('success', 'Program keahlian berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Major $major)
    {
        // Delete image if exists
        if ($major->image) {
            Storage::disk('public')->delete($major->image);
        }

        $major->delete();

        return redirect()->route('admin.majors.index')
            ->with('success', 'Program keahlian berhasil dihapus!');
    }
}
