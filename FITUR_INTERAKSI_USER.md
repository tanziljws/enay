# Dokumentasi Fitur Interaksi User

## Fitur yang Ditambahkan

Sistem interaksi user lengkap dengan autentikasi untuk galeri, berita, dan guru dengan fitur:
- ✅ **Like & Dislike** - User dapat memberikan reaksi positif atau negatif
- ✅ **Comment** - User dapat memberikan komentar
- ✅ **Download** - User dapat mengunduh foto/gambar
- 🔒 **Autentikasi Required** - Semua fitur memerlukan login

## Struktur Database

### Tabel Baru yang Dibuat:

1. **gallery_reactions** - Menyimpan like/dislike pada foto galeri
2. **gallery_user_comments** - Menyimpan komentar pada foto galeri
3. **news_reactions** - Menyimpan like/dislike pada berita
4. **news_user_comments** - Menyimpan komentar pada berita
5. **teacher_reactions** - Menyimpan like/dislike pada guru
6. **teacher_comments** - Menyimpan komentar pada guru
7. **downloads** - Tracking download file oleh user

### Kolom Tambahan:
- **users.role** - Membedakan admin dan user biasa (enum: 'admin', 'user')

## File yang Dibuat/Dimodifikasi

### Models:
- `app/Models/User.php` - Ditambahkan relasi dan method isAdmin()
- `app/Models/GalleryReaction.php` - Model baru
- `app/Models/GalleryUserComment.php` - Model baru
- `app/Models/NewsReaction.php` - Model baru
- `app/Models/NewsUserComment.php` - Model baru
- `app/Models/TeacherReaction.php` - Model baru
- `app/Models/TeacherComment.php` - Model baru
- `app/Models/Download.php` - Model baru

### Controllers:
- `app/Http/Controllers/UserAuthController.php` - Handle register, login, logout user
- `app/Http/Controllers/UserInteractionController.php` - Handle semua interaksi (like, comment, download)

### Views:
- `resources/views/auth/register.blade.php` - Form registrasi user
- `resources/views/auth/login.blade.php` - Form login user
- `resources/views/partials/interaction-buttons.blade.php` - Komponen tombol interaksi
- `resources/views/layouts/app.blade.php` - Diupdate dengan menu login/register
- `resources/views/galeri.blade.php` - Ditambahkan fitur interaksi

### Routes:
File `routes/web.php` ditambahkan:
- User authentication routes (register, login, logout)
- Gallery interaction routes
- News interaction routes
- Teacher interaction routes

## Cara Menggunakan

### 1. Registrasi User Baru
- Akses: `http://127.0.0.1:8000/register`
- Isi form: Nama, Email, Password, Konfirmasi Password
- Setelah registrasi, otomatis login

### 2. Login User
- Akses: `http://127.0.0.1:8000/login`
- Isi form: Email dan Password
- Centang "Ingat Saya" untuk tetap login

### 3. Menggunakan Fitur Interaksi

#### Di Halaman Galeri (`/galeri`):
- **Like/Dislike**: Klik tombol thumbs up/down
- **Comment**: Klik tombol comment, tulis komentar, kirim
- **Download**: Klik tombol download untuk mengunduh foto
- **Lihat Komentar**: Klik tombol comment untuk melihat semua komentar

#### Di Halaman Berita (Akan ditambahkan):
- Sama seperti galeri

#### Di Halaman Guru (Akan ditambahkan):
- Sama seperti galeri

### 4. Logout
- Klik nama user di navbar (kanan atas)
- Pilih "Logout"

## API Endpoints

### Gallery Interactions:
```
POST   /gallery/{id}/reaction      - Toggle like/dislike
POST   /gallery/{id}/comment        - Add comment
GET    /gallery/{id}/comments       - Get all comments
DELETE /gallery/comment/{id}        - Delete own comment
GET    /gallery/{id}/download       - Download image
```

### News Interactions:
```
POST   /news/{id}/reaction          - Toggle like/dislike
POST   /news/{id}/comment           - Add comment
GET    /news/{id}/comments          - Get all comments
DELETE /news/comment/{id}            - Delete own comment
GET    /news/{id}/download          - Download image
```

### Teacher Interactions:
```
POST   /teacher/{id}/reaction       - Toggle like/dislike
POST   /teacher/{id}/comment        - Add comment
GET    /teacher/{id}/comments       - Get all comments
DELETE /teacher/comment/{id}         - Delete own comment
GET    /teacher/{id}/download       - Download photo
```

## Keamanan

1. **Middleware Auth**: Semua route interaksi dilindungi middleware `auth`
2. **CSRF Protection**: Semua POST request menggunakan CSRF token
3. **Validation**: Input user divalidasi sebelum disimpan
4. **Authorization**: User hanya bisa menghapus komentar sendiri
5. **Password Hashing**: Password di-hash menggunakan bcrypt

## Perbedaan Admin vs User

### Admin:
- Login via `/admin/login`
- Akses dashboard admin
- Tidak bisa menggunakan fitur interaksi user (like, comment, download)
- Bisa mengelola semua konten

### User Biasa:
- Login via `/login`
- Tidak bisa akses dashboard admin
- Bisa menggunakan semua fitur interaksi
- Hanya bisa menghapus komentar sendiri

## Testing

### Membuat User Test:
```bash
php artisan tinker
```
```php
User::create([
    'name' => 'Test User',
    'email' => 'user@test.com',
    'password' => bcrypt('password'),
    'role' => 'user'
]);
```

### Membuat Admin Test:
```php
User::create([
    'name' => 'Admin',
    'email' => 'admin@test.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

## Troubleshooting

### Error: "Unauthenticated"
- Pastikan user sudah login
- Clear cache: `php artisan cache:clear`

### Error: "CSRF token mismatch"
- Refresh halaman
- Clear browser cache

### Tombol interaksi tidak muncul
- Pastikan sudah login sebagai user (bukan admin)
- Check console browser untuk error JavaScript

### Download tidak berfungsi
- Pastikan file ada di `storage/app/public/`
- Jalankan: `php artisan storage:link`

## Next Steps (Untuk Implementasi Penuh)

1. ✅ Galeri - Sudah diimplementasi
2. ⏳ Berita - Perlu update view `news.blade.php` dan `news-detail.blade.php`
3. ⏳ Guru - Perlu update view `teachers.blade.php` dan `teacher-detail.blade.php`
4. ⏳ Notifikasi real-time (optional)
5. ⏳ Email verification (optional)
6. ⏳ Social login (optional)

## Catatan Penting

- Semua fitur interaksi menggunakan AJAX untuk pengalaman user yang lebih baik
- Data reactions dan comments di-load secara real-time
- User dapat mengubah reaksi (dari like ke dislike atau sebaliknya)
- Satu user hanya bisa memberikan satu reaksi per item
- Komentar ditampilkan dari yang terbaru
- Download history disimpan untuk tracking

## Support

Jika ada pertanyaan atau masalah, silakan hubungi developer atau buat issue di repository.
