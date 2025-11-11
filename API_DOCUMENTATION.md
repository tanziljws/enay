# API Documentation - Galeri Sekolah Enay

## Overview
API untuk sistem manajemen sekolah yang menyediakan endpoint untuk mengelola data siswa, guru, kelas, mata pelajaran, nilai, kehadiran, dan berita.

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication
API menggunakan Laravel Sanctum untuk autentikasi. Untuk endpoint yang memerlukan autentikasi, sertakan token dalam header:
```
Authorization: Bearer {token}
```

## Response Format
Semua response menggunakan format JSON dengan struktur:
```json
{
    "success": true|false,
    "data": {}, // atau []
    "message": "Success/Error message"
}
```

## Error Handling
Error response format:
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

---

## Public Endpoints

### News

#### GET /news
Mendapatkan daftar berita dengan pagination dan filter.

**Query Parameters:**
- `page` (integer): Nomor halaman (default: 1)
- `category` (string): Filter berdasarkan kategori (academic, sports, events, announcements, general)
- `search` (string): Pencarian berdasarkan judul atau konten
- `status` (string): Filter berdasarkan status (draft, published, archived)

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "title": "Judul Berita",
                "content": "Konten berita...",
                "image": "path/to/image.jpg",
                "author": "Penulis",
                "category": "academic",
                "status": "published",
                "published_at": "2024-01-01T00:00:00.000000Z",
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "first_page_url": "http://localhost:8000/api/v1/news?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://localhost:8000/api/v1/news?page=1",
        "links": [...],
        "next_page_url": null,
        "path": "http://localhost:8000/api/v1/news",
        "per_page": 10,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

#### GET /news/latest
Mendapatkan berita terbaru.

**Query Parameters:**
- `limit` (integer): Jumlah berita (default: 5)

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Judul Berita Terbaru",
            "content": "Konten berita...",
            "image": "path/to/image.jpg",
            "author": "Penulis",
            "category": "academic",
            "status": "published",
            "published_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

#### GET /news/{id}
Mendapatkan detail berita berdasarkan ID.

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Judul Berita",
        "content": "Konten lengkap berita...",
        "image": "path/to/image.jpg",
        "author": "Penulis",
        "category": "academic",
        "status": "published",
        "published_at": "2024-01-01T00:00:00.000000Z",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

### Subjects

#### GET /subjects
Mendapatkan daftar mata pelajaran.

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "Matematika",
                "code": "MTK",
                "description": "Mata pelajaran matematika",
                "credits": 4,
                "level": "SMA",
                "status": "active",
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-01T00:00:00.000000Z"
            }
        ]
    }
}
```

#### GET /subjects/{id}
Mendapatkan detail mata pelajaran.

### Class Rooms

#### GET /class-rooms
Mendapatkan daftar kelas.

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "X IPA 1",
                "code": "XIPA1",
                "description": "Kelas X IPA 1",
                "capacity": 30,
                "teacher_id": 1,
                "level": "SMA",
                "status": "active",
                "teacher": {
                    "id": 1,
                    "name": "Dr. Ahmad Wijaya, M.Pd"
                },
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-01T00:00:00.000000Z"
            }
        ]
    }
}
```

#### GET /class-rooms/{id}
Mendapatkan detail kelas.

---

## Protected Endpoints (Requires Authentication)

### Students

#### GET /students
Mendapatkan daftar siswa dengan filter dan pagination.

**Query Parameters:**
- `page` (integer): Nomor halaman
- `class_room_id` (integer): Filter berdasarkan kelas
- `status` (string): Filter berdasarkan status (active, inactive, graduated)
- `search` (string): Pencarian berdasarkan nama atau NIS

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "student_number": "S001",
                "name": "Andi Pratama",
                "email": "andi.pratama@student.sekolah-enay.ac.id",
                "phone": "081234567001",
                "date_of_birth": "2007-01-15",
                "gender": "male",
                "address": "Jl. Kebon Jeruk No. 1, Jakarta",
                "parent_name": "Budi Pratama",
                "parent_phone": "081234567101",
                "class_room_id": 1,
                "photo": null,
                "status": "active",
                "class_room": {
                    "id": 1,
                    "name": "X IPA 1"
                },
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-01T00:00:00.000000Z"
            }
        ]
    }
}
```

#### POST /students
Menambah siswa baru.

**Request Body:**
```json
{
    "student_number": "S002",
    "name": "Sari Indah",
    "email": "sari.indah@student.sekolah-enay.ac.id",
    "phone": "081234567002",
    "date_of_birth": "2007-03-20",
    "gender": "female",
    "address": "Jl. Kemang Raya No. 2, Jakarta",
    "parent_name": "Siti Indah",
    "parent_phone": "081234567102",
    "class_room_id": 1,
    "photo": null,
    "status": "active"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Student created successfully",
    "data": {
        "id": 2,
        "student_number": "S002",
        "name": "Sari Indah",
        // ... other fields
    }
}
```

#### GET /students/{id}
Mendapatkan detail siswa.

#### PUT /students/{id}
Update data siswa.

#### DELETE /students/{id}
Menghapus siswa.

### Teachers

#### GET /teachers
Mendapatkan daftar guru.

**Query Parameters:**
- `page` (integer): Nomor halaman
- `status` (string): Filter berdasarkan status (active, inactive)
- `specialization` (string): Filter berdasarkan spesialisasi
- `search` (string): Pencarian berdasarkan nama atau NIP

#### POST /teachers
Menambah guru baru.

**Request Body:**
```json
{
    "teacher_number": "T003",
    "name": "John Smith, M.A",
    "email": "john.smith@sekolah-enay.ac.id",
    "phone": "081234567892",
    "date_of_birth": "1982-07-10",
    "gender": "male",
    "address": "Jl. Thamrin No. 789, Jakarta",
    "qualification": "S2 English Literature",
    "specialization": "Bahasa Inggris",
    "join_date": "2011-09-01",
    "photo": null,
    "status": "active",
    "subjects": [3] // Array of subject IDs
}
```

#### GET /teachers/{id}
Mendapatkan detail guru.

#### PUT /teachers/{id}
Update data guru.

#### DELETE /teachers/{id}
Menghapus guru.

### Grades

#### GET /grades
Mendapatkan daftar nilai.

#### POST /grades
Menambah nilai baru.

**Request Body:**
```json
{
    "student_id": 1,
    "subject_id": 1,
    "teacher_id": 1,
    "score": 85.50,
    "grade_letter": "B+",
    "notes": "Nilai bagus",
    "exam_date": "2024-01-15",
    "semester": "1",
    "academic_year": 2024
}
```

#### GET /grades/{id}
Mendapatkan detail nilai.

#### PUT /grades/{id}
Update nilai.

#### DELETE /grades/{id}
Menghapus nilai.

### Attendance

#### GET /attendances
Mendapatkan daftar kehadiran.

#### POST /attendances
Menambah data kehadiran.

**Request Body:**
```json
{
    "student_id": 1,
    "class_room_id": 1,
    "date": "2024-01-15",
    "status": "present",
    "notes": null,
    "check_in_time": "07:00:00",
    "check_out_time": "15:00:00"
}
```

#### GET /attendances/{id}
Mendapatkan detail kehadiran.

#### PUT /attendances/{id}
Update data kehadiran.

#### DELETE /attendances/{id}
Menghapus data kehadiran.

### News (Admin)

#### POST /news
Membuat berita baru (admin only).

**Request Body:**
```json
{
    "title": "Judul Berita",
    "content": "Konten berita...",
    "image": "path/to/image.jpg",
    "author": "Penulis",
    "category": "academic",
    "status": "published"
}
```

#### PUT /news/{id}
Update berita (admin only).

#### DELETE /news/{id}
Menghapus berita (admin only).

---

## Admin Endpoints

### GET /admin/dashboard/stats
Mendapatkan statistik dashboard admin.

**Response:**
```json
{
    "success": true,
    "data": {
        "total_students": 150,
        "total_teachers": 25,
        "total_classes": 12,
        "total_subjects": 15,
        "active_students": 145,
        "active_teachers": 24
    }
}
```

---

## Status Codes

- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Unprocessable Entity
- `500` - Internal Server Error

---

## Example Usage

### JavaScript/Fetch
```javascript
// Get latest news
fetch('http://localhost:8000/api/v1/news/latest')
    .then(response => response.json())
    .then(data => console.log(data));

// Get students with authentication
fetch('http://localhost:8000/api/v1/students', {
    headers: {
        'Authorization': 'Bearer your-token-here',
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => console.log(data));
```

### cURL
```bash
# Get latest news
curl -X GET http://localhost:8000/api/v1/news/latest

# Create new student
curl -X POST http://localhost:8000/api/v1/students \
  -H "Authorization: Bearer your-token-here" \
  -H "Content-Type: application/json" \
  -d '{
    "student_number": "S003",
    "name": "Budi Santoso",
    "email": "budi.santoso@student.sekolah-enay.ac.id",
    "date_of_birth": "2007-05-10",
    "gender": "male",
    "address": "Jl. Pondok Indah No. 3, Jakarta",
    "parent_name": "Ahmad Santoso",
    "parent_phone": "081234567103",
    "class_room_id": 1
  }'
```

---

## Rate Limiting
API memiliki rate limiting untuk mencegah abuse. Default limit adalah 60 requests per minute per IP address.

## CORS
API mendukung CORS untuk akses dari frontend yang berbeda domain. Konfigurasi CORS dapat diatur di `config/cors.php`.
