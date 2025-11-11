<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendancePhoto;
use Illuminate\Http\Request;

class AttendancePhotoController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendancePhoto::query();
        if ($request->level) $query->where('level', $request->level);
        if ($request->major) $query->where('major', $request->major);
        if ($request->search) {
            $term = $request->search;
            $query->where(function($q) use ($term){
                $q->where('level','like','%'.$term.'%')
                  ->orWhere('major','like','%'.$term.'%')
                  ->orWhereRaw('CAST(class_room_id AS CHAR) like ?', ['%'.$term.'%'])
                  ->orWhereHas('classRoom', function($qq) use ($term){
                      $qq->where('name','like','%'.$term.'%');
                  });
            });
        }
        $photos = $query->orderByDesc('created_at')->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $photos
        ]);
    }
}


