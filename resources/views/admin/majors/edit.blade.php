@extends('layouts.app')

@section('title', 'Edit Program Keahlian - Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Program Keahlian: {{ $major->name }}</h4>
                    <a href="{{ route('admin.majors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.majors.update', $major) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Kode Program <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $major->code) }}" 
                                           placeholder="Contoh: PPLG, TJKT" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Singkat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $major->name) }}" 
                                           placeholder="Contoh: PPLG" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                   id="full_name" name="full_name" value="{{ old('full_name', $major->full_name) }}" 
                                   placeholder="Contoh: Pengembangan Perangkat Lunak dan Gim" required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Deskripsi program keahlian..." required>{{ old('description', $major->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="technology" {{ old('category', $major->category) == 'technology' ? 'selected' : '' }}>Teknologi</option>
                                        <option value="network" {{ old('category', $major->category) == 'network' ? 'selected' : '' }}>Jaringan</option>
                                        <option value="multimedia" {{ old('category', $major->category) == 'multimedia' ? 'selected' : '' }}>Multimedia</option>
                                        <option value="business" {{ old('category', $major->category) == 'business' ? 'selected' : '' }}>Bisnis</option>
                                        <option value="other" {{ old('category', $major->category) == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_count" class="form-label">Jumlah Siswa <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('student_count') is-invalid @enderror" 
                                           id="student_count" name="student_count" value="{{ old('student_count', $major->student_count) }}" 
                                           min="0" required>
                                    @error('student_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Urutan Tampil <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $major->sort_order) }}" 
                                           min="0" required>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Gambar Program</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*" onchange="previewNewImage(this)">
                                    <div class="form-text">Format: JPG, PNG, GIF. Maksimal 10MB. Kosongkan jika tidak ingin mengubah gambar.</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($major->image_url)
                                        <div class="mt-2">
                                            <label class="form-label">Gambar Saat Ini:</label>
                                            <div>
                                                <img src="{{ $major->image_url }}" 
                                                     alt="{{ $major->name }}" 
                                                     class="img-thumbnail" 
                                                     style="width: 150px; height: 150px; object-fit: cover;">
                                            </div>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                                <label class="form-check-label text-danger" for="remove_image">
                                                    Hapus gambar ini
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Preview New Image -->
                                    <div id="newImagePreview" class="mt-3" style="display: none;">
                                        <label class="form-label">Preview Gambar Baru:</label>
                                        <div>
                                            <img id="previewNewImg" src="" alt="Preview" 
                                                 class="img-thumbnail" 
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', $major->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Program Aktif
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.majors.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewNewImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes
        
        // Check file size
        if (file.size > maxSize) {
            alert('Ukuran file terlalu besar. Maksimal 10MB.');
            input.value = '';
            document.getElementById('newImagePreview').style.display = 'none';
            return;
        }
        
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
            input.value = '';
            document.getElementById('newImagePreview').style.display = 'none';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewNewImg').src = e.target.result;
            document.getElementById('newImagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('newImagePreview').style.display = 'none';
    }
}
</script>
@endsection
