# Galeri Sekolah Enay - Website Sekolah dengan API

Website sekolah modern yang dibangun dengan Laravel 12 dan menyediakan API untuk manajemen data sekolah.

## 🚀 Fitur Utama

### Frontend Website
- **Halaman Beranda** - Dashboard dengan statistik sekolah dan berita terbaru
- **Halaman Berita** - Sistem manajemen berita dengan kategori dan pencarian
- **Halaman Tentang** - Informasi lengkap tentang sekolah, visi, misi, dan fasilitas
- **Halaman Kontak** - Form kontak dan informasi kontak sekolah
- **Halaman Data Siswa** - Tampilan data siswa dengan filter dan pencarian
- **Responsive Design** - Tampilan yang optimal di semua perangkat

### Backend API
- **RESTful API** - API lengkap untuk semua entitas sekolah
- **Authentication** - Sistem autentikasi menggunakan Laravel Sanctum
- **Data Management** - CRUD operations untuk semua data sekolah
- **Filtering & Search** - Pencarian dan filter data yang advanced
- **Pagination** - Pagination untuk performa optimal

## 📊 Entitas Data

### 1. Students (Siswa)
- Informasi lengkap siswa (nama, NIS, email, telepon, dll)
- Data orang tua/wali
- Status siswa (aktif, tidak aktif, lulus)
- Relasi dengan kelas

### 2. Teachers (Guru)
- Data guru lengkap (nama, NIP, kualifikasi, spesialisasi)
- Relasi dengan mata pelajaran
- Relasi dengan kelas yang diampu

### 3. Class Rooms (Kelas)
- Informasi kelas (nama, kode, kapasitas, level)
- Wali kelas
- Relasi dengan siswa

### 4. Subjects (Mata Pelajaran)
- Data mata pelajaran (nama, kode, deskripsi, kredit)
- Level pendidikan (SD, SMP, SMA)
- Relasi dengan guru

### 5. Grades (Nilai)
- Sistem penilaian siswa
- Relasi dengan siswa, mata pelajaran, dan guru
- Semester dan tahun ajaran

### 6. Attendance (Kehadiran)
- Sistem absensi siswa
- Status kehadiran (hadir, tidak hadir, terlambat, izin)
- Waktu check-in dan check-out

### 7. News (Berita)
- Sistem manajemen berita sekolah
- Kategori berita (akademik, olahraga, acara, pengumuman, umum)
- Status publikasi (draft, published, archived)

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 12, PHP 8.2+
- **Database**: MySQL
- **Frontend**: Bootstrap 5, jQuery, Font Awesome
- **API**: RESTful API dengan Laravel
- **Authentication**: Laravel Sanctum

## 📋 Persyaratan Sistem

- PHP 8.2 atau lebih tinggi
- Composer
- MySQL 5.7 atau lebih tinggi
- Node.js (untuk asset compilation)

## 🚀 Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd galeri-sekolah-enay
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   - Buat database MySQL
   - Update konfigurasi database di `.env`
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=username
   DB_PASSWORD=password
   ```

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Start Development Server**
   ```bash
   php artisan serve
   ```

## 📚 API Documentation

### Base URL
```
http://localhost:8000/api/v1
```

### Public Endpoints (Tidak memerlukan authentication)

#### News
- `GET /news` - Daftar berita
- `GET /news/latest` - Berita terbaru
- `GET /news/{id}` - Detail berita

#### Subjects
- `GET /subjects` - Daftar mata pelajaran
- `GET /subjects/{id}` - Detail mata pelajaran

#### Class Rooms
- `GET /class-rooms` - Daftar kelas
- `GET /class-rooms/{id}` - Detail kelas

### Protected Endpoints (Memerlukan authentication)

#### Students
- `GET /students` - Daftar siswa
- `POST /students` - Tambah siswa
- `GET /students/{id}` - Detail siswa
- `PUT /students/{id}` - Update siswa
- `DELETE /students/{id}` - Hapus siswa

#### Teachers
- `GET /teachers` - Daftar guru
- `POST /teachers` - Tambah guru
- `GET /teachers/{id}` - Detail guru
- `PUT /teachers/{id}` - Update guru
- `DELETE /teachers/{id}` - Hapus guru

#### Grades
- `GET /grades` - Daftar nilai
- `POST /grades` - Tambah nilai
- `GET /grades/{id}` - Detail nilai
- `PUT /grades/{id}` - Update nilai
- `DELETE /grades/{id}` - Hapus nilai

#### Attendance
- `GET /attendances` - Daftar kehadiran
- `POST /attendances` - Tambah kehadiran
- `GET /attendances/{id}` - Detail kehadiran
- `PUT /attendances/{id}` - Update kehadiran
- `DELETE /attendances/{id}` - Hapus kehadiran

### Query Parameters

#### Filtering
- `class_room_id` - Filter berdasarkan kelas
- `status` - Filter berdasarkan status
- `category` - Filter berita berdasarkan kategori
- `search` - Pencarian berdasarkan nama/kata kunci

#### Pagination
- `page` - Halaman yang diminta
- `limit` - Jumlah data per halaman

### Response Format
```json
{
    "success": true,
    "data": {
        // Data response
    },
    "message": "Success message"
}
```

## 🎨 Frontend Features

### Homepage
- Statistik sekolah real-time
- Berita terbaru
- Informasi visi dan misi
- Fitur unggulan sekolah

### News System
- Daftar berita dengan pagination
- Filter berdasarkan kategori
- Pencarian berita
- Detail berita dengan berita terkait

### Data Management
- Tampilan data siswa dengan filter
- Responsive card layout
- Search functionality
- Status indicators

## 🔧 Development

### Menambah Fitur Baru
1. Buat model dan migration
2. Buat controller dengan API methods
3. Tambahkan routes di `routes/api.php`
4. Buat seeder untuk data testing
5. Update dokumentasi

### Testing API
Gunakan tools seperti Postman atau curl untuk testing API:

```bash
# Get all students
curl -X GET http://localhost:8000/api/v1/students

# Get latest news
curl -X GET http://localhost:8000/api/v1/news/latest
```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📞 Support

Untuk pertanyaan atau dukungan, silakan hubungi:
- Email: info@sekolah-enay.ac.id
- Phone: (021) 1234-5678

---

**Galeri Sekolah Enay** - Membangun generasi yang cerdas, berkarakter, dan berprestasi untuk masa depan yang gemilang.