# Troubleshooting Galeri & Fitur Interaksi

## ✅ Perbaikan yang Sudah Dilakukan

1. **Path gambar diperbaiki** - Dari `image_path` ke `image`
2. **Storage link sudah ada** - Symlink ke storage/app/public
3. **Query reactions dan comments sudah benar**
4. **Fitur interaksi sudah terintegrasi**

## 🔍 Cara Cek Masalah

### 1. Cek Apakah Ada Data Galeri

Jalankan di terminal:
```bash
php artisan tinker
```

Kemudian:
```php
\App\Models\GalleryItem::count()
// Harus return angka > 0

\App\Models\GalleryItem::first()
// Lihat data item pertama
```

### 2. Cek Path Gambar

```php
$item = \App\Models\GalleryItem::first();
echo $item->image;
// Contoh output: gallery/abc123.jpg

$fullPath = storage_path('app/public/' . $item->image);
echo $fullPath;
// Check apakah file exist

file_exists($fullPath);
// Harus return true
```

### 3. Cek Storage Link

```bash
# Windows
dir public\storage

# Harus ada symlink ke ..\storage\app\public
```

## 🖼️ Masalah: Gambar Tidak Muncul

### Penyebab 1: File Tidak Ada
**Solusi:**
1. Pastikan admin sudah upload gambar via dashboard
2. Check folder `storage/app/public/gallery/`
3. Pastikan file ada di folder tersebut

### Penyebab 2: Storage Link Tidak Ada
**Solusi:**
```bash
php artisan storage:link
```

### Penyebab 3: Permission Issue (Linux/Mac)
**Solusi:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Penyebab 4: Path Salah
**Cek di browser:**
- Klik kanan pada area gambar → Inspect Element
- Lihat src attribute
- Seharusnya: `http://127.0.0.1:8000/storage/gallery/filename.jpg`
- Jika berbeda, ada masalah dengan path

**Solusi:**
View sudah diperbaiki menggunakan `$item->image`

## 🔘 Masalah: Tombol Like/Dislike Tidak Berfungsi

### Cek 1: User Sudah Login?
Fitur interaksi hanya untuk user yang sudah login.

**Test:**
1. Buka `/register` atau `/login`
2. Buat akun user baru (bukan admin!)
3. Login dengan akun tersebut
4. Kembali ke halaman galeri

### Cek 2: JavaScript Error?
**Buka Console Browser:**
- Tekan `F12` → Tab Console
- Lihat apakah ada error merah
- Error umum:
  - `CSRF token mismatch` → Refresh halaman
  - `404 Not Found` → Route belum terdaftar
  - `Unauthenticated` → User belum login

### Cek 3: Route Sudah Terdaftar?
```bash
php artisan route:list | findstr gallery
```

Harus ada:
- `POST /gallery/{id}/reaction`
- `POST /gallery/{id}/comment`
- `GET /gallery/{id}/comments`
- `GET /gallery/{id}/download`

### Cek 4: CSRF Token
Pastikan ada di layout:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

## 💬 Masalah: Comment Tidak Muncul

### Cek Database:
```bash
php artisan tinker
```
```php
\App\Models\GalleryUserComment::count()
// Cek jumlah komentar

\App\Models\GalleryUserComment::with('user')->get()
// Lihat semua komentar dengan user
```

### Cek API Endpoint:
Buka browser console, klik tombol comment, lihat Network tab:
- Request ke `/gallery/{id}/comments` harus return JSON
- Response harus berisi array comments

## 📥 Masalah: Download Tidak Berfungsi

### Penyebab 1: CAPTCHA Tidak Dikonfigurasi
**Solusi:**
Set di `.env`:
```env
RECAPTCHA_SKIP_LOCAL=true
```

Atau dapatkan keys dari Google reCAPTCHA.

### Penyebab 2: File Tidak Ada
**Cek:**
```php
$item = \App\Models\GalleryItem::first();
$path = storage_path('app/public/' . $item->image);
file_exists($path); // Harus true
```

### Penyebab 3: Permission
File harus readable oleh web server.

## 🔧 Quick Fixes

### Clear All Cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Restart Server:
```bash
# Stop server (Ctrl+C)
php artisan serve
```

### Check Logs:
```bash
# Lihat error logs
tail -f storage/logs/laravel.log

# Windows
type storage\logs\laravel.log
```

## 🧪 Test Fitur Step by Step

### 1. Test Tampilan Galeri
```
URL: http://127.0.0.1:8000/galeri
Expected: Foto muncul, tombol like/dislike/comment/download ada
```

### 2. Test Like (Harus Login Dulu)
1. Login sebagai user
2. Klik tombol thumbs up
3. Angka like harus bertambah
4. Tombol berubah warna (filled)

### 3. Test Comment
1. Klik tombol comment
2. Form comment muncul
3. Tulis komentar
4. Klik send
5. Komentar muncul di list

### 4. Test Download
1. Klik tombol download
2. Modal CAPTCHA muncul (jika enabled)
3. Selesaikan CAPTCHA
4. Klik download
5. File terunduh

## 📊 Debug Mode

Tambahkan di controller untuk debug:

```php
// Di UserInteractionController
public function toggleGalleryReaction(Request $request, $id)
{
    \Log::info('Reaction request', [
        'user_id' => auth()->id(),
        'item_id' => $id,
        'type' => $request->type
    ]);
    
    // ... rest of code
}
```

Lihat logs:
```bash
tail -f storage/logs/laravel.log
```

## 🆘 Masih Bermasalah?

### Langkah Terakhir:

1. **Cek Requirements:**
   - PHP >= 8.1
   - MySQL running
   - Apache/Nginx running
   - Composer installed

2. **Re-migrate Database:**
   ```bash
   php artisan migrate:fresh
   php artisan db:seed --class=AdminSeeder
   php artisan db:seed --class=TeacherSeeder
   ```

3. **Test dengan Data Baru:**
   - Login sebagai admin
   - Upload foto baru via dashboard
   - Logout admin
   - Register user baru
   - Login sebagai user
   - Test fitur interaksi

4. **Check Browser:**
   - Clear browser cache
   - Try incognito mode
   - Try different browser

## 📞 Informasi Debug

Saat melaporkan masalah, sertakan:
1. Screenshot error
2. Browser console log (F12)
3. Laravel log (`storage/logs/laravel.log`)
4. PHP version: `php -v`
5. Laravel version: `php artisan --version`

## ✅ Checklist Fitur Berfungsi

- [ ] Foto galeri muncul
- [ ] Tombol like/dislike ada
- [ ] Tombol comment ada
- [ ] Tombol download ada
- [ ] User bisa login
- [ ] Like berfungsi (angka bertambah)
- [ ] Dislike berfungsi
- [ ] Comment bisa dikirim
- [ ] Comment muncul di list
- [ ] Download berfungsi (file terunduh)
- [ ] CAPTCHA muncul (jika enabled)

Jika semua checklist ✅, fitur sudah berfungsi sempurna!
