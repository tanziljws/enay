# Fix: Gambar Tidak Muncul

## ✅ Perbaikan yang Sudah Dilakukan

### 1. View Galeri (`galeri.blade.php`)
- ✅ Path diperbaiki dari `$item->image_path` ke `$item->image`
- ✅ Kolom database: `image`

### 2. View Jurusan (`jurusan.blade.php`)
- ✅ Path diperbaiki dari `$major->image_url` ke `asset('storage/' . $major->image)`
- ✅ Kolom database: `image`

### 3. Controller Download
- ✅ Path diperbaiki untuk menggunakan kolom `image` yang benar

### 4. Cache
- ✅ Cache cleared
- ✅ View cache cleared

## 📊 Status Saat Ini

### Galeri:
```
✓ 1 foto tersedia
✓ File exists
✓ Path: storage/gallery/xxx.jpg
✓ Siap ditampilkan
```

### Jurusan:
```
✓ 4 jurusan tersedia
✓ Semua punya gambar
✓ Path: storage/images/majors/xxx.jpg
✓ Semua file exists
✓ Siap ditampilkan
```

## 🔍 Cara Cek Apakah Gambar Muncul

### 1. Buka Browser
```
http://127.0.0.1:8000/galeri
http://127.0.0.1:8000/jurusan
```

### 2. Jika Gambar Masih Tidak Muncul

#### A. Hard Refresh Browser
- **Windows**: `Ctrl + F5` atau `Ctrl + Shift + R`
- **Mac**: `Cmd + Shift + R`

#### B. Clear Browser Cache
1. Tekan `F12` untuk buka Developer Tools
2. Klik kanan pada tombol refresh
3. Pilih "Empty Cache and Hard Reload"

#### C. Coba Incognito/Private Mode
- **Chrome**: `Ctrl + Shift + N`
- **Firefox**: `Ctrl + Shift + P`
- **Edge**: `Ctrl + Shift + N`

### 3. Check Console untuk Error

Tekan `F12` → Tab **Console**

Lihat apakah ada error seperti:
- `404 Not Found` → File tidak ada
- `403 Forbidden` → Permission issue
- `CORS error` → Cross-origin issue

### 4. Check Network Tab

Tekan `F12` → Tab **Network** → Refresh halaman

Lihat request untuk gambar:
- Status harus `200 OK`
- Jika `404` → File tidak ditemukan
- Jika `500` → Server error

## 🛠️ Troubleshooting Step by Step

### Problem 1: Gambar Tidak Muncul di Galeri

**Cek:**
```bash
php test-galeri.php
```

**Solusi:**
1. Pastikan admin sudah upload foto via dashboard
2. Check folder: `storage/app/public/gallery/`
3. Pastikan file ada di folder tersebut

**Upload Foto Baru:**
1. Login admin: `http://127.0.0.1:8000/admin/login`
2. Menu **Gallery** → **Add New**
3. Upload foto
4. Isi title dan description
5. Klik **Save**

### Problem 2: Gambar Tidak Muncul di Jurusan

**Cek:**
```bash
php test-jurusan.php
```

**Solusi:**
1. Pastikan admin sudah upload gambar jurusan
2. Check folder: `storage/app/public/images/majors/`
3. Pastikan file ada di folder tersebut

**Upload Gambar Jurusan:**
1. Login admin: `http://127.0.0.1:8000/admin/login`
2. Menu **Majors** → Edit jurusan
3. Upload gambar
4. Klik **Save**

### Problem 3: Storage Link Tidak Ada

**Cek:**
```bash
# Windows
dir public\storage

# Harus ada symlink
```

**Solusi:**
```bash
php artisan storage:link
```

### Problem 4: File Ada Tapi Gambar Tidak Muncul

**Kemungkinan Penyebab:**
1. Browser cache
2. Path salah
3. Permission issue (Linux/Mac)

**Solusi:**

#### A. Clear All Cache
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

#### B. Hard Refresh Browser
`Ctrl + F5`

#### C. Check Path di Browser
1. Klik kanan pada area gambar → **Inspect Element**
2. Lihat attribute `src`
3. Seharusnya: `http://127.0.0.1:8000/storage/gallery/xxx.jpg`
4. Copy URL tersebut
5. Paste di address bar browser
6. Jika gambar muncul → Path benar, masalah di cache
7. Jika 404 → Path salah atau file tidak ada

#### D. Permission (Linux/Mac Only)
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage
```

### Problem 5: Gambar Upload Tapi Tidak Tersimpan

**Cek:**
1. Folder `storage/app/public/` writable?
2. PHP upload_max_filesize cukup?
3. Ada error di logs?

**Solusi:**

#### A. Check PHP Settings
```bash
php -i | findstr upload_max_filesize
php -i | findstr post_max_size
```

Harus minimal:
- `upload_max_filesize = 10M`
- `post_max_size = 10M`

#### B. Check Laravel Logs
```bash
type storage\logs\laravel.log
```

#### C. Check Folder Permission (Windows)
1. Klik kanan folder `storage`
2. Properties → Security
3. Pastikan user Anda punya Full Control

## 🧪 Test Manual

### Test 1: Akses Langsung File

Jika gambar di database adalah: `gallery/abc123.jpg`

Akses di browser:
```
http://127.0.0.1:8000/storage/gallery/abc123.jpg
```

**Expected:** Gambar muncul
**Jika 404:** File tidak ada atau storage link bermasalah

### Test 2: Check File Exists

```bash
php -r "echo file_exists('storage/app/public/gallery/abc123.jpg') ? 'EXISTS' : 'NOT FOUND';"
```

### Test 3: Check Symlink

```bash
# Windows
dir public\storage

# Linux/Mac
ls -la public/storage
```

Harus ada symlink ke `../storage/app/public`

## 📝 Checklist Gambar Muncul

### Galeri:
- [ ] File ada di `storage/app/public/gallery/`
- [ ] Storage link ada di `public/storage`
- [ ] Path di view: `asset('storage/' . $item->image)`
- [ ] Cache sudah di-clear
- [ ] Browser sudah hard refresh
- [ ] Gambar muncul di halaman

### Jurusan:
- [ ] File ada di `storage/app/public/images/majors/`
- [ ] Storage link ada di `public/storage`
- [ ] Path di view: `asset('storage/' . $major->image)`
- [ ] Cache sudah di-clear
- [ ] Browser sudah hard refresh
- [ ] Gambar muncul di halaman

## 🚀 Quick Fix Commands

Jalankan semua command ini:

```bash
# Clear all cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear

# Recreate storage link
php artisan storage:link

# Test galeri
php test-galeri.php

# Test jurusan
php test-jurusan.php

# Restart server
# Ctrl+C untuk stop
php artisan serve
```

Kemudian:
1. Hard refresh browser (`Ctrl + F5`)
2. Atau buka incognito mode
3. Akses halaman galeri dan jurusan

## 📞 Masih Bermasalah?

### Langkah Terakhir:

1. **Screenshot Error**
   - Screenshot halaman
   - Screenshot console (F12)
   - Screenshot network tab

2. **Check Logs**
   ```bash
   type storage\logs\laravel.log
   ```

3. **Verify Files**
   ```bash
   dir storage\app\public\gallery
   dir storage\app\public\images\majors
   ```

4. **Test dengan Gambar Baru**
   - Upload gambar baru via admin
   - Gunakan nama file sederhana
   - Ukuran file kecil (< 2MB)

5. **Try Different Browser**
   - Chrome
   - Firefox
   - Edge

## ✅ Expected Result

Setelah semua perbaikan:

### Halaman Galeri (`/galeri`):
- ✓ Foto "Gladi Bersih P5 Tahunan" muncul
- ✓ Tombol like, dislike, comment, download ada
- ✓ Semua fitur berfungsi

### Halaman Jurusan (`/jurusan`):
- ✓ Gambar PPLG muncul
- ✓ Gambar TJKT muncul
- ✓ Gambar TPFL muncul
- ✓ Gambar TO muncul
- ✓ Semua dengan ukuran 350px height

---

**Jika masih ada masalah setelah mengikuti semua langkah di atas, kemungkinan ada issue spesifik yang perlu investigasi lebih lanjut.**
