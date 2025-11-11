<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentAdminController extends Controller
{
    public function index()
    {
        $students = Student::with('classRoom')->orderBy('name')->paginate(15);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_number' => 'required|unique:students,student_number',
            'name' => 'required',
            'email' => 'required|email|unique:students,email',
            'class_room_id' => 'required|exists:class_rooms,id',
            'status' => 'required|in:active,inactive,graduated',
            'photo' => 'nullable|image|max:10240'
        ]);
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students', 'public');
            $validated['photo'] = 'storage/' . $path;
        }
        Student::create($validated + $request->only(['phone','date_of_birth','gender','address','parent_name','parent_phone']));
        return redirect()->route('admin.students.index')->with('success','Siswa ditambahkan');
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_number' => 'sometimes|required|unique:students,student_number,'.$student->id,
            'email' => 'sometimes|required|email|unique:students,email,'.$student->id,
            'class_room_id' => 'sometimes|exists:class_rooms,id',
            'status' => 'sometimes|in:active,inactive,graduated',
            'photo' => 'nullable|image|max:10240'
        ]);
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students', 'public');
            $validated['photo'] = 'storage/' . $path;
        }
        $student->update($validated + $request->only(['name','phone','date_of_birth','gender','address','parent_name','parent_phone']));
        return redirect()->route('admin.students.index')->with('success','Siswa diperbarui');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.students.index')->with('success','Siswa dihapus');
    }
}


