<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = [
        'code',
        'name', 
        'full_name',
        'description',
        'image',
        'category',
        'student_count',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'student_count' => 'integer',
        'sort_order' => 'integer'
    ];

    // Scope untuk jurusan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk mengurutkan berdasarkan sort_order
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessor untuk mendapatkan URL gambar yang benar
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // Jika path mengarah ke public/images dan file memang ada di public
        if (str_starts_with($this->image, 'images/')) {
            $publicPath = public_path($this->image);
            if (file_exists($publicPath)) {
                // Gambar berada di public/images
                return asset($this->image);
            }

            // Jika file tidak ada di public/images, berarti disimpan via disk 'public'
            // dan harus diakses melalui /storage/images/...
            return asset('storage/' . $this->image);
        }

        // Default: anggap path relatif terhadap disk 'public'
        return asset('storage/' . $this->image);
    }
}
