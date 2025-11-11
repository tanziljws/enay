# Cara Edit Data Jurusan/Program Keahlian

## ✅ Masalah Sudah Diperbaiki!

Semua foto yang sudah diupload admin sekarang **sudah muncul** di halaman user (http://127.0.0.1:8000/jurusan).

### Yang Sudah Dilakukan:
1. ✅ Memperbaiki symbolic link storage (`php artisan storage:link`)
2. ✅ Membuat data major untuk semua 13 foto yang sudah diupload
3. ✅ Semua foto sekarang sudah tampil di halaman jurusan

---

## 📝 Langkah Selanjutnya untuk Admin

Saat ini, data jurusan menggunakan **nama placeholder** (Jurusan 2, Jurusan 3, dst). 
Admin perlu mengedit data ini dengan informasi yang benar.

### Cara Edit Data Jurusan:

1. **Login ke Admin Panel**
   - Buka: http://127.0.0.1:8000/admin/login
   - Masukkan username dan password admin

2. **Masuk ke Menu Program Keahlian**
   - Klik menu "Program Keahlian" di sidebar

3. **Edit Setiap Jurusan**
   - Klik tombol "Edit" pada setiap jurusan
   - Ubah data berikut:
     - **Kode**: Kode jurusan (contoh: PPLG, TJKT, TO, TP, dll)
     - **Nama Singkat**: Nama singkat jurusan (contoh: PPLG, TJKT)
     - **Nama Lengkap**: Nama lengkap program keahlian
     - **Deskripsi**: Deskripsi lengkap tentang jurusan
     - **Kategori**: Kategori jurusan (contoh: Teknologi, Bisnis, dll)
     - **Jumlah Siswa**: Total siswa di jurusan tersebut
     - **Status Aktif**: Centang jika jurusan masih aktif
     - **Urutan**: Urutan tampilan di halaman user
   - Klik "Simpan"

4. **Foto Tidak Perlu Diupload Ulang**
   - Foto sudah terhubung dengan data jurusan
   - Tidak perlu upload ulang

---

## 🔍 Daftar Jurusan yang Perlu Diedit

Berikut daftar jurusan yang perlu diedit (ID 2-13):

| ID | Nama Saat Ini | Foto | Status |
|----|---------------|------|--------|
| 1  | PPLG | ✅ Sudah lengkap | Tidak perlu edit |
| 2  | Jurusan 2 | ✅ Ada foto | ⚠️ Perlu edit |
| 3  | Jurusan 3 | ✅ Ada foto | ⚠️ Perlu edit |
| 4  | Jurusan 4 | ✅ Ada foto | ⚠️ Perlu edit |
| 5  | Jurusan 5 | ✅ Ada foto | ⚠️ Perlu edit |
| 6  | Jurusan 6 | ✅ Ada foto | ⚠️ Perlu edit |
| 7  | Jurusan 7 | ✅ Ada foto | ⚠️ Perlu edit |
| 8  | Jurusan 8 | ✅ Ada foto | ⚠️ Perlu edit |
| 9  | Jurusan 9 | ✅ Ada foto | ⚠️ Perlu edit |
| 10 | Jurusan 10 | ✅ Ada foto | ⚠️ Perlu edit |
| 11 | Jurusan 11 | ✅ Ada foto | ⚠️ Perlu edit |
| 12 | Jurusan 12 | ✅ Ada foto | ⚠️ Perlu edit |
| 13 | Jurusan 13 | ✅ Ada foto | ⚠️ Perlu edit |

---

## 💡 Tips untuk Admin

### Saat Menambah Jurusan Baru:
1. Pastikan mengisi **semua field** yang required
2. Upload foto dengan ukuran maksimal 10MB
3. Format foto: JPG, PNG, atau GIF
4. Centang "Status Aktif" agar muncul di halaman user
5. Klik "Simpan" untuk menyimpan data

### Jika Foto Tidak Muncul:
1. Pastikan field "Image" sudah terisi
2. Pastikan "Status Aktif" sudah dicentang
3. Refresh halaman jurusan (Ctrl+F5)
4. Periksa apakah file foto ada di: `storage/app/public/images/majors/`

---

## 🚀 Hasil Akhir

Setelah admin mengedit semua data:
- ✅ Semua 13 foto akan tampil dengan nama dan deskripsi yang benar
- ✅ User bisa melihat semua program keahlian di halaman jurusan
- ✅ Data terorganisir dengan baik

---

## 📞 Bantuan

Jika ada masalah atau pertanyaan, silakan hubungi developer.
