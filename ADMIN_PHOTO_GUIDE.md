# Panduan Admin: Mengelola Foto Program Keahlian

## 🎯 Cara Mengubah Foto Program Keahlian

### 1. **Login Admin**
- Buka: `http://127.0.0.1:8000/admin/login`
- Email: `admin@smkn4bogor.sch.id`
- Password: `admin123`

### 2. **Akses Manajemen Program Keahlian**
- Dari dashboard admin, klik "Program Keahlian"
- Atau langsung ke: `http://127.0.0.1:8000/admin/majors`

### 3. **Edit Program yang Ada**
- Klik tombol **Edit** (ikon pensil kuning) pada program yang ingin diubah
- Scroll ke bagian "Gambar Program"

### 4. **Upload Foto Baru**
- Klik "Choose File" atau "Browse"
- Pilih foto dari komputer (Format: JPG, PNG, GIF - Maksimal 2MB)
- Foto akan muncul preview secara otomatis
- Klik "Update" untuk menyimpan

### 5. **Hapus Foto**
- Centang checkbox "Hapus gambar ini" jika ingin menghapus foto
- Klik "Update" untuk menyimpan

### 6. **Tambah Program Baru**
- Klik tombol "Tambah Program Keahlian"
- Isi semua field yang diperlukan
- Upload foto di bagian "Gambar Program"
- Klik "Simpan"

## 📋 Fitur yang Tersedia

### ✅ **Preview Gambar**
- Foto yang dipilih akan muncul preview sebelum disimpan
- Preview juga tersedia saat edit program

### ✅ **Modal Gambar**
- Klik thumbnail gambar di tabel untuk melihat gambar penuh
- Modal akan menampilkan gambar dalam ukuran besar

### ✅ **Validasi File**
- Hanya menerima format: JPG, PNG, GIF
- Maksimal ukuran file: 2MB
- Validasi otomatis saat upload

### ✅ **Penghapusan Otomatis**
- Foto lama akan otomatis dihapus saat upload foto baru
- Tidak akan menghapus foto default (images/pplg.jpg, dll)

## 🖼️ Tips Upload Foto

1. **Ukuran Optimal**: 400x300 pixel atau lebih
2. **Format Terbaik**: JPG untuk foto, PNG untuk gambar dengan transparansi
3. **Kualitas**: Gunakan foto berkualitas tinggi tapi kompres untuk web
4. **Konten**: Pastikan foto relevan dengan program keahlian

## 🔧 Troubleshooting

### Foto Tidak Muncul
- Pastikan file tidak lebih dari 2MB
- Cek format file (hanya JPG, PNG, GIF)
- Refresh halaman setelah upload

### Error Upload
- Pastikan folder `storage/app/public/images/majors/` ada
- Jalankan: `php artisan storage:link`
- Cek permission folder storage

## 📱 Tampilan User

Foto yang diupload admin akan otomatis muncul di:
- Halaman Jurusan: `http://127.0.0.1:8000/jurusan`
- Semua halaman yang menampilkan program keahlian

---

**Catatan**: Perubahan foto akan langsung terlihat di halaman user tanpa perlu restart server.
