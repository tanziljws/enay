# Setup CAPTCHA untuk Download

## Fitur CAPTCHA

Sistem CAPTCHA telah ditambahkan untuk melindungi fitur download dari bot dan abuse. User harus menyelesaikan verifikasi CAPTCHA sebelum dapat mengunduh gambar/foto.

## Teknologi yang Digunakan

- **Google reCAPTCHA v2** - "I'm not a robot" checkbox
- Verifikasi server-side untuk keamanan maksimal
- Skip CAPTCHA di environment local untuk kemudahan development

## Cara Setup

### 1. Dapatkan API Keys dari Google

1. Buka [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Login dengan akun Google Anda
3. Klik "+" untuk membuat site baru
4. Isi form:
   - **Label**: Nama website Anda (contoh: "Galeri Sekolah Enay")
   - **reCAPTCHA type**: Pilih **reCAPTCHA v2** → "I'm not a robot" Checkbox
   - **Domains**: Tambahkan domain Anda
     - Untuk local: `localhost` atau `127.0.0.1`
     - Untuk production: `yourdomain.com`
   - Centang "Accept the reCAPTCHA Terms of Service"
5. Klik **Submit**
6. Copy **Site Key** dan **Secret Key** yang diberikan

### 2. Konfigurasi di Laravel

Tambahkan keys ke file `.env`:

```env
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
RECAPTCHA_VERSION=v2
RECAPTCHA_SKIP_LOCAL=true
```

**Catatan:**
- `RECAPTCHA_SKIP_LOCAL=true` akan melewati verifikasi CAPTCHA di environment local
- Set ke `false` jika ingin test CAPTCHA di local

### 3. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Test Fitur

1. Login sebagai user (bukan admin)
2. Buka halaman galeri
3. Klik tombol "Download" pada foto
4. Modal CAPTCHA akan muncul
5. Selesaikan verifikasi "I'm not a robot"
6. Klik tombol "Download"
7. File akan terunduh

## File yang Terlibat

### Config:
- `config/recaptcha.php` - Konfigurasi reCAPTCHA

### Service:
- `app/Services/RecaptchaService.php` - Service untuk verifikasi CAPTCHA

### Middleware:
- `app/Http/Middleware/VerifyRecaptcha.php` - Middleware verifikasi (opsional)

### Controller:
- `app/Http/Controllers/UserInteractionController.php` - Updated dengan verifikasi CAPTCHA

### Views:
- `resources/views/partials/download-captcha-modal.blade.php` - Modal CAPTCHA
- `resources/views/galeri.blade.php` - Updated dengan modal dan script
- `resources/views/layouts/app.blade.php` - Updated dengan section head

## Cara Kerja

### Flow Download dengan CAPTCHA:

1. **User klik tombol Download**
   - Modal CAPTCHA muncul
   - Tombol download disabled

2. **User menyelesaikan CAPTCHA**
   - Google verifikasi user bukan robot
   - Callback `onRecaptchaSuccess` dipanggil
   - Tombol download enabled

3. **User klik tombol Download di modal**
   - Form submit dengan `g-recaptcha-response` token
   - Server verifikasi token dengan Google API
   - Jika valid: File diunduh dan tracking disimpan
   - Jika invalid: Error message ditampilkan

4. **Modal ditutup**
   - CAPTCHA direset
   - Siap untuk download berikutnya

## Keamanan

### Server-Side Verification:
```php
// Di UserInteractionController
$recaptchaResponse = $request->input('g-recaptcha-response');

if (!$this->recaptcha->verify($recaptchaResponse)) {
    return back()->with('error', 'Verifikasi CAPTCHA gagal.');
}
```

### Features:
- ✅ Verifikasi server-side (tidak bisa di-bypass dari client)
- ✅ Token sekali pakai (tidak bisa digunakan ulang)
- ✅ Timeout protection (token expire setelah 2 menit)
- ✅ IP verification (Google cek IP user)
- ✅ Skip di local environment (untuk development)

## Troubleshooting

### CAPTCHA tidak muncul
**Penyebab:**
- Site key tidak dikonfigurasi
- Script reCAPTCHA tidak load

**Solusi:**
1. Pastikan `RECAPTCHA_SITE_KEY` ada di `.env`
2. Check console browser untuk error
3. Pastikan internet connection aktif (untuk load script Google)

### Error "Verifikasi CAPTCHA gagal"
**Penyebab:**
- Secret key salah
- CAPTCHA tidak diselesaikan
- Token expired
- Domain tidak terdaftar di Google reCAPTCHA

**Solusi:**
1. Pastikan `RECAPTCHA_SECRET_KEY` benar
2. Selesaikan CAPTCHA sebelum klik download
3. Jangan tunggu terlalu lama setelah selesai CAPTCHA
4. Tambahkan domain Anda di Google reCAPTCHA admin

### CAPTCHA muncul tapi tidak bisa diselesaikan
**Penyebab:**
- Site key salah
- Domain tidak match

**Solusi:**
1. Pastikan `RECAPTCHA_SITE_KEY` benar
2. Check domain di Google reCAPTCHA admin
3. Untuk local, pastikan `localhost` atau `127.0.0.1` terdaftar

### Skip CAPTCHA di Local
Jika ingin skip CAPTCHA saat development:

```env
RECAPTCHA_SKIP_LOCAL=true
```

Atau kosongkan keys:
```env
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=
```

## Customization

### Mengubah Tema CAPTCHA

Edit modal di `download-captcha-modal.blade.php`:

```html
<div class="g-recaptcha" 
     data-sitekey="{{ config('recaptcha.site_key') }}"
     data-theme="dark"  <!-- light atau dark -->
     data-size="normal" <!-- normal atau compact -->
     data-callback="onRecaptchaSuccess">
</div>
```

### Mengubah Pesan Error

Edit di `UserInteractionController.php`:

```php
if (!$this->recaptcha->verify($recaptchaResponse)) {
    return back()->with('error', 'Pesan error custom Anda');
}
```

### Menambahkan CAPTCHA di Fitur Lain

1. Include modal di view:
```blade
@include('partials.download-captcha-modal', [
    'type' => 'news',  // atau 'teacher'
    'itemId' => $item->id
])
```

2. Tambahkan verifikasi di controller:
```php
$recaptchaResponse = $request->input('g-recaptcha-response');

if (!$this->recaptcha->verify($recaptchaResponse)) {
    return back()->with('error', 'Verifikasi CAPTCHA gagal.');
}
```

## Testing

### Test Manual:

1. **Dengan CAPTCHA aktif:**
   ```env
   RECAPTCHA_SKIP_LOCAL=false
   RECAPTCHA_SITE_KEY=your_key
   RECAPTCHA_SECRET_KEY=your_secret
   ```
   - Test download dengan menyelesaikan CAPTCHA
   - Test download tanpa menyelesaikan CAPTCHA (harus gagal)

2. **Dengan CAPTCHA skip:**
   ```env
   RECAPTCHA_SKIP_LOCAL=true
   ```
   - Download harus langsung berhasil tanpa CAPTCHA

### Test Automated (Optional):

```php
// Test di Feature Test
public function test_download_requires_captcha()
{
    $user = User::factory()->create();
    $item = GalleryItem::factory()->create();
    
    $response = $this->actingAs($user)
        ->get(route('gallery.download', $item->id));
    
    $response->assertRedirect();
    $response->assertSessionHas('error');
}
```

## Production Checklist

Sebelum deploy ke production:

- [ ] Dapatkan reCAPTCHA keys untuk domain production
- [ ] Update `.env` dengan keys production
- [ ] Set `RECAPTCHA_SKIP_LOCAL=false`
- [ ] Tambahkan domain production di Google reCAPTCHA admin
- [ ] Test CAPTCHA di production
- [ ] Monitor logs untuk error CAPTCHA
- [ ] Setup rate limiting untuk download (optional)

## Support

Jika ada masalah:
1. Check logs: `storage/logs/laravel.log`
2. Check browser console untuk error JavaScript
3. Verify keys di Google reCAPTCHA admin
4. Test dengan CAPTCHA skip untuk isolate masalah

## Resources

- [Google reCAPTCHA Documentation](https://developers.google.com/recaptcha/docs/display)
- [reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
- [Laravel HTTP Client](https://laravel.com/docs/http-client)
