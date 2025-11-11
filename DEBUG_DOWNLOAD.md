# 🔍 Debug Download Berita

## Checklist Troubleshooting:

### 1. Cek Modal Muncul
- Klik tombol Download
- Modal CAPTCHA harus muncul
- Jika tidak muncul → masalah di Bootstrap modal

### 2. Cek CAPTCHA
- CAPTCHA harus ter-generate
- Bisa di-refresh dengan klik
- Input harus bisa diketik

### 3. Cek Submit Form
- Buka Console (F12)
- Klik Download setelah isi CAPTCHA
- Lihat Network tab untuk request

### 4. Cek Error di Console
```javascript
// Buka Console dan ketik:
console.log('Modal ID:', document.querySelector('[id^="downloadModalNews"]'));
console.log('Form:', document.querySelector('[id^="downloadFormNews"]'));
```

### 5. Test Manual Route
Buka browser dan test:
```
http://127.0.0.1:8000/news/1/download?captcha_verified=1
```

Jika error → lihat error message

## Kemungkinan Masalah:

### A. Modal Tidak Muncul
**Solusi:**
- Pastikan Bootstrap JS loaded
- Cek ID modal cocok dengan button target
- Hard refresh: Ctrl + Shift + R

### B. CAPTCHA Tidak Muncul
**Solusi:**
- Cek route `/captcha/generate` works
- Buka: `http://127.0.0.1:8000/captcha/generate`
- Harus return JSON dengan chars

### C. Download Gagal
**Solusi:**
- Cek file exists: `storage/app/public/news/[filename]`
- Cek permissions
- Cek route `news.download` terdaftar

### D. Error "Class Download not found"
**Solusi:**
```bash
php artisan make:model Download -m
```

## Quick Fix:

### 1. Clear All Cache
```bash
php artisan optimize:clear
php artisan view:clear
```

### 2. Restart Server
```bash
# Ctrl+C
php artisan serve
```

### 3. Hard Refresh Browser
```
Ctrl + Shift + R
```

### 4. Test di Incognito
Buka browser incognito mode untuk test

## Test Step by Step:

1. ✅ Login sebagai user
2. ✅ Buka detail berita yang ada gambar
3. ✅ Klik tombol Download (navy blue)
4. ✅ Modal CAPTCHA muncul
5. ✅ Ketik kode CAPTCHA
6. ✅ Klik Download
7. ✅ File ter-download

## Jika Masih Error:

Screenshot error dan kirim:
- Error message
- Console log (F12)
- Network tab response
