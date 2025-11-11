# Cara Setting Link Sosial Media

## 📱 Panduan Lengkap Menghubungkan Sosial Media

File yang perlu diedit: `resources/views/contact.blade.php`

---

## 1. Instagram

### Format Link:
```
https://www.instagram.com/USERNAME_INSTAGRAM_ANDA
```

### Cara Mendapatkan Username Instagram:
1. Buka aplikasi Instagram Anda
2. Klik profil Anda
3. Username ada di bawah nama profil (contoh: @smkn4bogor)
4. Ganti `USERNAME_INSTAGRAM_ANDA` dengan username Anda (tanpa @)

### Contoh:
```html
<a href="https://www.instagram.com/smkn4bogor" target="_blank" class="btn btn-outline-danger btn-lg" title="Instagram">
    <i class="fab fa-instagram"></i>
</a>
```

---

## 2. WhatsApp

### Format Link:
```
https://wa.me/62NOMORTELPON
```

### Cara Mendapatkan Link WhatsApp:
1. Gunakan nomor WhatsApp sekolah
2. Hapus angka 0 di depan
3. Tambahkan kode negara 62 (Indonesia)
4. Contoh: 0812-3456-7890 → 6281234567890

### Contoh:
```html
<a href="https://wa.me/6281234567890" target="_blank" class="btn btn-outline-success btn-lg" title="WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
```

### Dengan Pesan Otomatis (Opsional):
```
https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20bertanya%20tentang%20sekolah
```

---

## 3. Facebook

### Format Link:
```
https://www.facebook.com/USERNAME_ATAU_PAGE_ID
```

### Cara Mendapatkan Link Facebook:
1. Buka halaman Facebook sekolah
2. Lihat URL di browser
3. Copy bagian setelah facebook.com/

### Contoh:
```html
<a href="https://www.facebook.com/smkn4kotabogor" target="_blank" class="btn btn-outline-primary btn-lg" title="Facebook">
    <i class="fab fa-facebook-f"></i>
</a>
```

---

## 4. Twitter (X)

### Format Link:
```
https://twitter.com/USERNAME_TWITTER
```

### Cara Mendapatkan Username Twitter:
1. Buka profil Twitter sekolah
2. Username ada setelah @ (contoh: @smkn4bogor)
3. Ganti `USERNAME_ANDA` dengan username (tanpa @)

### Contoh:
```html
<a href="https://twitter.com/smkn4bogor" target="_blank" class="btn btn-outline-info btn-lg" title="Twitter">
    <i class="fab fa-twitter"></i>
</a>
```

---

## 📝 Langkah-langkah Edit:

1. **Buka file:** `resources/views/contact.blade.php`

2. **Cari bagian "Ikuti Kami"** (sekitar baris 132-152)

3. **Ganti link-link berikut:**

   **Instagram:**
   ```html
   <!-- GANTI INI -->
   <a href="https://www.instagram.com/USERNAME_INSTAGRAM_ANDA" target="_blank" ...>
   
   <!-- MENJADI (contoh) -->
   <a href="https://www.instagram.com/smkn4kotabogor" target="_blank" ...>
   ```

   **WhatsApp:**
   ```html
   <!-- GANTI INI -->
   <a href="https://wa.me/62812345678900" target="_blank" ...>
   
   <!-- MENJADI (contoh dengan nomor sekolah) -->
   <a href="https://wa.me/6281234567890" target="_blank" ...>
   ```

   **Facebook:**
   ```html
   <!-- GANTI INI -->
   <a href="https://www.facebook.com/USERNAME_ANDA" target="_blank" ...>
   
   <!-- MENJADI (contoh) -->
   <a href="https://www.facebook.com/smkn4kotabogor" target="_blank" ...>
   ```

   **Twitter:**
   ```html
   <!-- GANTI INI -->
   <a href="https://twitter.com/USERNAME_ANDA" target="_blank" ...>
   
   <!-- MENJADI (contoh) -->
   <a href="https://twitter.com/smkn4bogor" target="_blank" ...>
   ```

4. **Simpan file**

5. **Refresh halaman kontak** untuk melihat perubahan

---

## ✅ Hasil Akhir

Setelah edit, ketika user klik:
- **Logo Instagram** → Langsung ke profil Instagram sekolah
- **Logo WhatsApp** → Langsung chat WhatsApp sekolah
- **Logo Facebook** → Langsung ke halaman Facebook sekolah
- **Logo Twitter** → Langsung ke profil Twitter sekolah

---

## 💡 Tips Tambahan

### Jika Tidak Punya Akun Tertentu:
Anda bisa **hapus** atau **sembunyikan** tombol yang tidak digunakan.

Contoh menghapus Twitter:
```html
<!-- Hapus atau comment bagian ini -->
<!--
<a href="https://twitter.com/USERNAME_ANDA" target="_blank" class="btn btn-outline-info btn-lg" title="Twitter">
    <i class="fab fa-twitter"></i>
</a>
-->
```

### Menambah Sosial Media Lain:

**TikTok:**
```html
<a href="https://www.tiktok.com/@username_tiktok" target="_blank" class="btn btn-outline-dark btn-lg" title="TikTok">
    <i class="fab fa-tiktok"></i>
</a>
```

**YouTube:**
```html
<a href="https://www.youtube.com/@channelname" target="_blank" class="btn btn-outline-danger btn-lg" title="YouTube">
    <i class="fab fa-youtube"></i>
</a>
```

**Email:**
```html
<a href="mailto:info@sekolah.ac.id" class="btn btn-outline-secondary btn-lg" title="Email">
    <i class="fas fa-envelope"></i>
</a>
```

---

## 🔍 Cara Test:

1. Buka halaman Kontak: `http://127.0.0.1:8000/contact`
2. Scroll ke bagian "Ikuti Kami"
3. Klik setiap logo
4. Pastikan membuka halaman yang benar di tab baru

---

## 📞 Contoh Lengkap untuk SMK Negeri 4 Bogor:

```html
<div class="d-flex gap-3">
    <a href="https://www.facebook.com/smkn4kotabogor" target="_blank" class="btn btn-outline-primary btn-lg" title="Facebook">
        <i class="fab fa-facebook-f"></i>
    </a>
    <a href="https://twitter.com/smkn4bogor" target="_blank" class="btn btn-outline-info btn-lg" title="Twitter">
        <i class="fab fa-twitter"></i>
    </a>
    <a href="https://www.instagram.com/smkn4kotabogor" target="_blank" class="btn btn-outline-danger btn-lg" title="Instagram">
        <i class="fab fa-instagram"></i>
    </a>
    <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-outline-success btn-lg" title="WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
</div>
```

---

Selamat mencoba! 🎉
