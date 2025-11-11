# ⚡ Quick Setup Guide

## 🚀 Setup dalam 5 Menit

### Step 1: Database Migration
```bash
cd "c:\xampp\htdocs\galeri-sekolah-enay (2)\galeri-sekolah-enay\galeri-sekolah-enay"

php artisan migrate
```

### Step 2: Storage Link
```bash
php artisan storage:link
```

### Step 3: Clear Cache
```bash
php artisan optimize:clear
```

### Step 4: Run Server
```bash
php artisan serve
```

### Step 5: Test!
Buka browser: `http://127.0.0.1:8000`

---

## 🧪 Quick Test

### 1. Test Profil User
```
1. Daftar akun baru: http://127.0.0.1:8000/register
2. Login
3. Klik nama di navbar → "Profil Saya"
4. Klik "Edit Profil"
5. Upload foto
6. Simpan
7. Cek foto muncul di navbar ✅
```

### 2. Test Interaksi Galeri
```
1. Buka: http://127.0.0.1:8000/galeri
2. Klik salah satu foto
3. Klik Like → counter +1 ✅
4. Tulis komentar → muncul ✅
5. Klik Download → CAPTCHA muncul ✅
6. Isi CAPTCHA → download ✅
```

### 3. Test Berita
```
1. Buka: http://127.0.0.1:8000/news
2. Klik "Baca Selengkapnya"
3. Test Like, Comment, Download ✅
```

### 4. Test Guru
```
1. Buka: http://127.0.0.1:8000/teachers
2. Klik salah satu guru
3. Test Like, Comment, Download ✅
```

### 5. Test Guest User
```
1. Logout
2. Buka detail foto/berita/guru
3. Alert muncul dengan tombol Login/Daftar ✅
```

---

## 🎯 Fitur yang Bisa Langsung Digunakan

### ✅ User Features
- [x] Register & Login
- [x] Upload foto profil
- [x] Edit profil
- [x] Like/Dislike konten
- [x] Komentar
- [x] Download dengan CAPTCHA
- [x] Lihat statistik aktivitas

### ✅ Admin Features
- [x] Kelola galeri
- [x] Kelola berita
- [x] Kelola guru
- [x] Lihat interaksi user
- [x] Hapus komentar

---

## 🔑 Default Admin Account

Jika sudah ada seeder:
```
Email: admin@example.com
Password: password
```

Atau buat manual di database:
```sql
INSERT INTO users (name, email, password, role) 
VALUES ('Admin', 'admin@test.com', '$2y$12$...', 'admin');
```

---

## 📁 Folder Permissions

Pastikan folder ini writable:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 775 public/storage
```

Di Windows (XAMPP):
- Klik kanan folder → Properties → Security
- Pastikan "Users" punya Full Control

---

## 🎨 Customize

### Ubah Warna Navy Blue
Edit di file:
- `resources/views/partials/interaction-buttons.blade.php`
- `resources/views/layouts/app.blade.php`

Cari: `#3d4f5d` dan ganti dengan warna lain

### Ubah Logo
Ganti file di: `public/images/logo.png`

### Ubah Nama Website
Edit: `resources/views/layouts/app.blade.php`
Cari: "Galeri Sekolah Enay"

---

## 🐛 Troubleshooting Cepat

### Foto tidak muncul?
```bash
php artisan storage:link
```

### Error 500?
```bash
php artisan optimize:clear
tail -f storage/logs/laravel.log
```

### CAPTCHA tidak muncul?
```bash
# Test route
curl http://127.0.0.1:8000/captcha/generate

# Harus return JSON
```

### Migration error?
```bash
# Reset database (HATI-HATI: hapus semua data!)
php artisan migrate:fresh

# Atau migrate specific file
php artisan migrate --path=database/migrations/2025_10_10_060842_add_profile_photo_to_users_table.php
```

---

## 📊 Database Tables

Setelah migration, tabel yang ada:
```
✅ users (dengan profile_photo, bio, phone)
✅ reactions (polymorphic)
✅ comments (polymorphic)
✅ downloads
✅ news
✅ teachers
✅ gallery_items
✅ majors
✅ activities
```

---

## 🎊 Done!

Website siap digunakan dengan fitur:
- ✅ Profil user + foto
- ✅ Like/Dislike
- ✅ Komentar
- ✅ Download + CAPTCHA
- ✅ Responsive
- ✅ Navy blue theme

**Selamat menggunakan!** 🚀

---

## 📞 Need Help?

Cek file dokumentasi:
- `FITUR_LENGKAP_WEBSITE.md` - Dokumentasi lengkap
- `README_FITUR_BARU.md` - Fitur baru
- `SOLUSI_GAMBAR_TIDAK_MUNCUL.md` - Fix gambar
- `DEBUG_DOWNLOAD.md` - Debug download

Atau cek log:
```bash
tail -f storage/logs/laravel.log
```
