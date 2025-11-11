<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TeacherAdminController extends Controller
{
    /**
     * Sync storage files to public (for Windows php artisan serve)
     */
    private function syncStorageToPublic($folder = 'teachers')
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
        $teachers = Teacher::orderBy('name')->paginate(15);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:guru,staff',
            'gender' => 'nullable|in:male,female',
            'photo' => 'nullable|image|max:10240'
        ]);
        
        // Generate unique teacher_number automatically
        $validated['teacher_number'] = 'T' . str_pad(Teacher::count() + 1, 4, '0', STR_PAD_LEFT);
        
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('teachers', 'public');
            $validated['photo'] = $path; // simpan relative path, render pakai asset('storage/'.$path)
            
            // Auto-sync to public folder
            $this->syncStorageToPublic('teachers');
        }

        Teacher::create($validated);
        return redirect()->route('admin.teachers.index')->with('success','Guru ditambahkan');
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required',
            'status' => 'sometimes|in:active,inactive',
            'role' => 'sometimes|required|in:guru,staff',
            'gender' => 'nullable|in:male,female',
            'photo' => 'nullable|image|max:10240'
        ]);
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
                Storage::disk('public')->delete($teacher->photo);
            }
            
            $path = $request->file('photo')->store('teachers', 'public');
            $validated['photo'] = $path;
            
            // Auto-sync to public folder
            $this->syncStorageToPublic('teachers');
        }

        $teacher->update($validated);
        return redirect()->route('admin.teachers.index')->with('success','Guru diperbarui');
    }

    public function destroy(Teacher $teacher)
    {
        // Hapus foto jika ada
        if ($teacher->photo && Storage::disk('public')->exists($teacher->photo)) {
            Storage::disk('public')->delete($teacher->photo);
        }
        
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success','Guru dihapus');
    }
}


