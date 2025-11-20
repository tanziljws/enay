# Fix Session Cookie di Railway - WAJIB SET!

## 🔥 Masalah: Cookie Session Tidak Tersimpan

Jika CAPTCHA selalu salah meskipun input benar, kemungkinan besar **cookie session tidak tersimpan** di browser.

## ✅ Solusi: Set Environment Variables di Railway

Di Railway Dashboard → Settings → Variables, **WAJIB SET**:

```env
APP_ENV=production
APP_URL=https://enay-production.up.railway.app
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

**CATATAN PENTING:**
- `SESSION_DOMAIN` **JANGAN DI-SET** (biarkan kosong/null) - Laravel akan auto-detect
- `SESSION_SAME_SITE=lax` (bukan `none`) - `lax` lebih aman dan cukup untuk same-site requests
- `SESSION_SECURE_COOKIE=true` - WAJIB untuk HTTPS

## 🧪 Cara Test Setelah Set

1. **Clear cache di Railway:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   ```

2. **Buka browser → DevTools → Application → Cookies**
   - Domain: `https://enay-production.up.railway.app`
   - **HARUS ADA:**
     - `laravel_session` (atau `galeri-sekolah-enay-session`)
     - `XSRF-TOKEN`

3. **Jika cookie TIDAK ADA:**
   - Cek kembali environment variables di Railway
   - Pastikan `APP_URL` menggunakan `https://`
   - Pastikan `SESSION_SECURE_COOKIE=true`
   - Clear cache lagi

## 🔍 Debug Session

Jika masih bermasalah, cek logs di Railway:

```bash
# Cek apakah session tersimpan
tail -f storage/logs/laravel.log | grep -i session
```

Atau test langsung di browser console:
```javascript
// Cek cookies
document.cookie
// Harus ada: laravel_session=... dan XSRF-TOKEN=...
```

## ⚠️ Jangan Set Ini (SALAH):

```env
SESSION_DOMAIN=enay-production.up.railway.app  # ❌ JANGAN SET INI
SESSION_SAME_SITE=none  # ❌ Tidak perlu, pakai 'lax'
SESSION_SECURE_COOKIE=false  # ❌ HARUS true untuk HTTPS
```

## ✅ Yang Benar:

```env
APP_URL=https://enay-production.up.railway.app
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
SESSION_DOMAIN=  # Kosongkan atau hapus
```

---

**Setelah set environment variables → Deploy ulang → Clear cache → Test!**

