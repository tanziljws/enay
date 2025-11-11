<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendancePhoto;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class AttendancePhotoAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendancePhoto::with('classRoom')->orderByDesc('created_at');
        if ($request->level) $query->where('level', $request->level);
        if ($request->major) $query->where('major', $request->major);
        $photos = $query->paginate(12)->withQueryString();
        return view('admin.attendance.index', compact('photos'));
    }

    public function create()
    {
        $classes = ClassRoom::orderBy('name')->get(['id','name']);
        return view('admin.attendance.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'level' => 'required|in:X,XI,XII',
            'major' => 'required|in:PPLG,TJKT,TO,TP',
            'image' => 'required|image|max:10240'
        ]);
        $path = $request->file('image')->store('attendance', 'public');
        $validated['image'] = $path;
        AttendancePhoto::create($validated);
        return redirect()->route('admin.attendance.index')->with('success','Foto absen ditambahkan');
    }

    public function destroy(AttendancePhoto $attendance)
    {
        $attendance->delete();
        return redirect()->route('admin.attendance.index')->with('success','Foto absen dihapus');
    }
}


