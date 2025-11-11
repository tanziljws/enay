# Panduan Login Admin

## 🔐 Akun Admin Default

Setelah menjalankan seeder, akun admin default sudah dibuat:

```
Email: admin@admin.com
Password: admin123
```

⚠️ **PENTING**: Ganti password setelah login pertama kali!

## 🚀 Cara Login sebagai Admin

### 1. Akses Halaman Login Admin

Buka browser dan akses:
```
http://127.0.0.1:8000/admin/login
```

### 2. Masukkan Kredensial

- **Email**: `admin@admin.com`
- **Password**: `admin123`

### 3. Klik "Login"

Anda akan diarahkan ke Dashboard Admin.

## 📝 Membuat Admin Baru

### Opsi 1: Via Tinker (Terminal)

```bash
php artisan tinker
```

Kemudian:
```php
\App\Models\User::create([
    'name' => 'Nama Admin',
    'email' => 'email@admin.com',
    'password' => bcrypt('password_anda'),
    'role' => 'admin'
]);
```

Tekan `Ctrl+C` untuk keluar.

### Opsi 2: Via Seeder

Jalankan seeder yang sudah dibuat:
```bash
php artisan db:seed --class=AdminSeeder
```

### Opsi 3: Via Database (phpMyAdmin/MySQL)

1. Buka phpMyAdmin
2. Pilih database Anda
3. Buka tabel `users`
4. Insert data baru:
   ```sql
   INSERT INTO users (name, email, password, role, created_at, updated_at) 
   VALUES (
       'Administrator',
       'admin@admin.com',
       '$2y$12$abcdefghijklmnopqrstuvwxyz...', -- hash password
       'admin',
       NOW(),
       NOW()
   );
   ```

**Untuk generate password hash:**
```bash
php artisan tinker
```
```php
bcrypt('password_anda')
```

## 🔄 Perbedaan Admin vs User

### Admin:
- ✅ Login via `/admin/login`
- ✅ Akses dashboard admin
- ✅ Kelola semua konten (berita, galeri, guru, dll)
- ✅ Kelola komentar user
- ✅ Lihat statistik
- ❌ Tidak bisa like/comment/download (fitur user)

### User Biasa:
- ✅ Login via `/login`
- ✅ Like, dislike, comment
- ✅ Download gambar/foto
- ❌ Tidak bisa akses dashboard admin

## 🔒 Keamanan

### Ganti Password Default

Setelah login pertama kali, segera ganti password:

**Via Tinker:**
```bash
php artisan tinker
```
```php
$admin = \App\Models\User::where('email', 'admin@admin.com')->first();
$admin->password = bcrypt('password_baru_yang_kuat');
$admin->save();
```

**Via Database:**
```sql
UPDATE users 
SET password = '$2y$12$hash_password_baru' 
WHERE email = 'admin@admin.com';
```

### Tips Password Kuat:
- Minimal 12 karakter
- Kombinasi huruf besar, kecil, angka, simbol
- Jangan gunakan password yang mudah ditebak
- Contoh: `Admin@2025!Secure`

## 🛠️ Troubleshooting

### Error "These credentials do not match our records"

**Penyebab:**
- Email atau password salah
- Akun admin belum dibuat
- Role bukan 'admin'

**Solusi:**
1. Pastikan email dan password benar
2. Jalankan seeder: `php artisan db:seed --class=AdminSeeder`
3. Check database apakah user ada dan role = 'admin'

### Lupa Password Admin

**Solusi:**
```bash
php artisan tinker
```
```php
$admin = \App\Models\User::where('email', 'admin@admin.com')->first();
$admin->password = bcrypt('password_baru');
$admin->save();
echo "Password berhasil direset!";
```

### Tidak Bisa Akses Dashboard

**Penyebab:**
- Belum login
- Role bukan 'admin'
- Session expired

**Solusi:**
1. Login ulang via `/admin/login`
2. Check role di database (harus 'admin')
3. Clear browser cache dan cookies

### Redirect ke Login User

**Penyebab:**
- Menggunakan `/login` bukan `/admin/login`

**Solusi:**
- Gunakan URL yang benar: `http://127.0.0.1:8000/admin/login`

## 📊 Fitur Dashboard Admin

Setelah login, Anda dapat:

### 1. Kelola Berita
- Create, Read, Update, Delete berita
- Upload gambar berita
- Set status publish/draft

### 2. Kelola Galeri
- Upload foto kegiatan
- Edit informasi foto
- Hapus foto

### 3. Kelola Guru
- Tambah data guru
- Upload foto guru
- Edit informasi guru
- Set major/jurusan

### 4. Kelola Siswa
- Tambah data siswa
- Edit informasi siswa

### 5. Kelola Jurusan/Major
- Tambah jurusan baru
- Edit informasi jurusan
- Set status active/inactive

### 6. Kelola Kegiatan
- Tambah kegiatan sekolah
- Upload foto kegiatan
- Set tanggal dan lokasi

### 7. Kelola Komentar
- Lihat semua komentar user
- Approve/reject komentar
- Hapus komentar spam

### 8. Statistik
- Lihat jumlah views
- Lihat jumlah likes
- Lihat jumlah komentar
- Lihat jumlah download

## 🔐 Multiple Admin

Untuk membuat admin tambahan:

```bash
php artisan tinker
```

```php
// Admin 1
\App\Models\User::create([
    'name' => 'Admin Galeri',
    'email' => 'galeri@admin.com',
    'password' => bcrypt('password123'),
    'role' => 'admin'
]);

// Admin 2
\App\Models\User::create([
    'name' => 'Admin Berita',
    'email' => 'berita@admin.com',
    'password' => bcrypt('password123'),
    'role' => 'admin'
]);
```

## 📱 Logout Admin

Untuk logout:
1. Klik nama admin di navbar (kanan atas)
2. Klik "Logout"

Atau akses langsung:
```
POST http://127.0.0.1:8000/admin/logout
```

## ⚙️ Production Setup

Untuk production, pastikan:

1. **Ganti semua password default**
2. **Hapus atau disable AdminSeeder** (jangan biarkan password default)
3. **Setup environment variables** untuk admin credentials
4. **Enable 2FA** (Two-Factor Authentication) - optional
5. **Setup rate limiting** untuk login attempts
6. **Monitor admin activities** via logs

## 📞 Support

Jika ada masalah:
1. Check logs: `storage/logs/laravel.log`
2. Clear cache: `php artisan cache:clear`
3. Clear config: `php artisan config:clear`
4. Restart server

---

**Happy Managing! 🎉**
