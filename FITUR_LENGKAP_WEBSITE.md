# 📚 Dokumentasi Lengkap Website Galeri Sekolah

## 🎯 Ringkasan Fitur

Website Galeri Sekolah ini memiliki fitur lengkap untuk interaksi user dengan konten (galeri, berita, guru) termasuk sistem profil user dengan foto.

---

## 🌟 FITUR UTAMA

### 1. **Sistem Autentikasi** ✅
- Register user baru
- Login/Logout
- Session management
- Role-based access (Admin & User)

### 2. **Profil User** ✅
- Upload foto profil
- Edit informasi pribadi (nama, email, telepon, bio)
- Ubah password
- Statistik aktivitas (likes, comments, downloads)
- Foto profil muncul di navbar

### 3. **Interaksi Konten** ✅

#### A. Galeri Foto
- ✅ Like/Dislike foto
- ✅ Komentar pada foto
- ✅ Download foto dengan CAPTCHA
- ✅ Klik foto untuk zoom
- ✅ Modal detail dengan daftar komentar

#### B. Berita
- ✅ Like/Dislike berita
- ✅ Komentar pada berita
- ✅ Download gambar berita dengan CAPTCHA
- ✅ Klik gambar untuk zoom
- ✅ Berita terkait di sidebar

#### C. Data Guru
- ✅ Like/Dislike profil guru
- ✅ Komentar pada profil guru
- ✅ Download foto guru dengan CAPTCHA
- ✅ Klik foto untuk zoom
- ✅ Info lengkap guru

### 4. **CAPTCHA System** ✅
- CAPTCHA warna-warni untuk download
- Verifikasi server-side
- Auto-refresh CAPTCHA
- Case-insensitive validation

### 5. **Alert untuk Guest User** ✅
- Peringatan untuk user yang belum login
- Daftar fitur yang bisa digunakan setelah login
- Tombol Login & Daftar
- Tampil di semua halaman detail

---

## 📁 STRUKTUR FILE

### Controllers
```
app/Http/Controllers/
├── UserProfileController.php       # Profil user
├── NewsController.php              # Detail berita
├── TeacherController.php           # Detail guru
├── UserInteractionController.php   # Like, comment, download
├── CaptchaController.php           # Generate & verify CAPTCHA
└── Admin/
    ├── TeacherAdminController.php  # CRUD guru
    └── InteractionAdminController.php # Kelola interaksi
```

### Models
```
app/Models/
├── User.php          # User dengan foto profil
├── News.php          # Berita
├── Teacher.php       # Guru
├── GalleryItem.php   # Foto galeri
├── Reaction.php      # Like/Dislike (polymorphic)
├── Comment.php       # Komentar (polymorphic)
└── Download.php      # Track download
```

### Views
```
resources/views/
├── profile/
│   ├── show.blade.php   # Halaman profil
│   └── edit.blade.php   # Edit profil
├── news/
│   └── show.blade.php   # Detail berita
├── teachers/
│   └── show.blade.php   # Detail guru
├── partials/
│   ├── interaction-buttons.blade.php      # Tombol like, dislike, comment
│   ├── download-captcha-modal.blade.php   # Modal CAPTCHA
│   ├── download-news-modal.blade.php      # Modal download berita
│   └── download-teacher-modal.blade.php   # Modal download guru
└── layouts/
    └── app.blade.php    # Layout utama (navbar dengan foto profil)
```

### Migrations
```
database/migrations/
├── 2025_10_10_054529_create_reactions_table.php
├── 2025_10_10_054559_create_comments_table.php
├── 2025_10_10_060842_add_profile_photo_to_users_table.php
└── 2025_10_10_051444_make_teacher_fields_nullable.php
```

---

## 🎨 DESAIN & TEMA

### Warna Utama
- **Navy Blue**: `#3d4f5d` (tombol, header, accent)
- **Merah**: Untuk dislike
- **Abu-abu**: Untuk comment
- **Hijau**: Untuk success message

### Komponen UI
- Bootstrap 5
- Font Awesome icons
- Rounded corners
- Shadow effects
- Responsive design

---

## 🔐 ROUTES

### Public Routes
```
GET  /                    # Homepage
GET  /about               # Tentang
GET  /news                # List berita
GET  /news/{id}           # Detail berita
GET  /teachers            # List guru
GET  /teachers/{id}       # Detail guru
GET  /galeri              # Galeri foto
GET  /contact             # Kontak
GET  /jurusan             # Jurusan
```

### Auth Routes (User)
```
GET  /login               # Form login
POST /login               # Process login
GET  /register            # Form register
POST /register            # Process register
POST /logout              # Logout
```

### User Routes (Requires Login)
```
GET  /profile             # Lihat profil
GET  /profile/edit        # Edit profil
PUT  /profile             # Update profil

# Interactions
POST   /gallery/{id}/reaction      # Like/dislike foto
POST   /gallery/{id}/comment       # Comment foto
GET    /gallery/{id}/comments      # Get comments
DELETE /gallery/comment/{id}       # Delete comment
GET    /gallery/{id}/download      # Download foto

POST   /news/{id}/reaction         # Like/dislike berita
POST   /news/{id}/comment          # Comment berita
GET    /news/{id}/comments         # Get comments
DELETE /news/comment/{id}          # Delete comment
GET    /news/{id}/download         # Download gambar

POST   /teacher/{id}/reaction      # Like/dislike guru
POST   /teacher/{id}/comment       # Comment guru
GET    /teacher/{id}/comments      # Get comments
DELETE /teacher/comment/{id}       # Delete comment
GET    /teacher/{id}/download      # Download foto
```

### CAPTCHA Routes
```
GET  /captcha/generate    # Generate CAPTCHA
POST /captcha/verify      # Verify CAPTCHA
```

### Admin Routes
```
GET  /admin/login         # Admin login
GET  /admin               # Admin dashboard
GET  /admin/teachers      # Kelola guru
...
```

---

## 💾 DATABASE SCHEMA

### Table: users
```sql
- id
- name
- email
- password
- role (admin/user)
- profile_photo (nullable)
- bio (nullable)
- phone (nullable)
- created_at
- updated_at
```

### Table: reactions (Polymorphic)
```sql
- id
- user_id
- reactable_id
- reactable_type (News/Teacher/GalleryItem)
- type (like/dislike)
- created_at
- updated_at
- UNIQUE(user_id, reactable_id, reactable_type)
```

### Table: comments (Polymorphic)
```sql
- id
- user_id
- commentable_id
- commentable_type (News/Teacher/GalleryItem)
- comment
- is_approved (default: true)
- created_at
- updated_at
```

### Table: downloads
```sql
- id
- user_id
- downloadable_id
- downloadable_type
- file_path
- created_at
- updated_at
```

---

## 🧪 TESTING CHECKLIST

### ✅ Fitur Profil User
- [ ] Register user baru
- [ ] Login dengan user
- [ ] Upload foto profil
- [ ] Edit nama, email, telepon, bio
- [ ] Ubah password
- [ ] Foto muncul di navbar
- [ ] Statistik aktivitas benar
- [ ] Logout

### ✅ Fitur Interaksi Galeri
- [ ] Like foto (counter bertambah)
- [ ] Unlike foto (counter berkurang)
- [ ] Dislike foto
- [ ] Comment foto
- [ ] Lihat daftar comment
- [ ] Hapus comment sendiri
- [ ] Download foto dengan CAPTCHA
- [ ] Klik foto untuk zoom

### ✅ Fitur Interaksi Berita
- [ ] Like berita
- [ ] Dislike berita
- [ ] Comment berita
- [ ] Download gambar berita
- [ ] Klik gambar untuk zoom
- [ ] Lihat berita terkait

### ✅ Fitur Interaksi Guru
- [ ] Like profil guru
- [ ] Dislike profil guru
- [ ] Comment profil guru
- [ ] Download foto guru
- [ ] Klik foto untuk zoom

### ✅ CAPTCHA
- [ ] CAPTCHA muncul saat download
- [ ] CAPTCHA bisa di-refresh
- [ ] Verifikasi benar → download berhasil
- [ ] Verifikasi salah → error message

### ✅ Guest User
- [ ] Alert muncul untuk guest
- [ ] Tombol Login & Daftar ada
- [ ] Counter tetap terlihat
- [ ] Tidak bisa like/comment/download

---

## 🚀 DEPLOYMENT CHECKLIST

### 1. Environment
```bash
# Copy .env
cp .env.example .env

# Generate key
php artisan key:generate

# Setup database di .env
DB_DATABASE=galeri_sekolah
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Database
```bash
# Run migrations
php artisan migrate

# (Optional) Seed data
php artisan db:seed
```

### 3. Storage
```bash
# Create symlink
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
```

### 4. Optimize
```bash
# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

### 5. Server
```bash
# Development
php artisan serve

# Production (Apache/Nginx)
# Point document root to /public
```

---

## 📝 NOTES

### Warna Navy Blue
Semua tombol interaksi menggunakan warna navy blue (`#3d4f5d`) untuk konsistensi:
- Tombol Like (active)
- Tombol Download
- Header card
- Badge primary

### Polymorphic Relations
Menggunakan polymorphic relations untuk:
- Reactions (like/dislike)
- Comments
- Downloads

Ini memungkinkan satu tabel untuk multiple models (News, Teacher, GalleryItem).

### Security
- CAPTCHA untuk download
- CSRF protection
- Password hashing
- File upload validation
- SQL injection prevention (Eloquent)

---

## 🐛 TROUBLESHOOTING

### Foto tidak muncul
```bash
# Pastikan symlink ada
php artisan storage:link

# Cek permissions
chmod -R 775 storage/app/public
```

### CAPTCHA loading terus
```bash
# Clear cache
php artisan optimize:clear

# Test route
curl http://127.0.0.1:8000/captcha/generate
```

### Error 500
```bash
# Cek log
tail -f storage/logs/laravel.log

# Clear cache
php artisan optimize:clear
```

---

## 📞 SUPPORT

Jika ada masalah, cek:
1. `storage/logs/laravel.log` untuk error
2. Browser console (F12) untuk JS error
3. Network tab untuk failed requests

---

**Website Galeri Sekolah - Complete Interactive System** ✅
