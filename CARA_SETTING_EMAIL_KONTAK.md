# Cara Setting Email untuk Form Kontak

## ✅ Yang Sudah Dibuat:

1. ✅ **ContactController** - Menangani pengiriman email
2. ✅ **ContactFormMail** - Template email yang dikirim
3. ✅ **Email Template** - Tampilan email yang bagus
4. ✅ **Route** - `/contact/send` untuk submit form
5. ✅ **Form Update** - Form sudah terintegrasi dengan backend

---

## 📧 Langkah Konfigurasi Email

### 1. Edit File `.env`

Buka file `.env` di root project dan tambahkan/edit konfigurasi berikut:

```env
# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email_sekolah@gmail.com
MAIL_PASSWORD=password_aplikasi_gmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=email_sekolah@gmail.com
MAIL_FROM_NAME="SMK Negeri 4 Bogor"

# Email tujuan untuk menerima pesan dari form kontak
CONTACT_EMAIL=email_admin_anda@gmail.com
```

---

## 🔐 Cara Mendapatkan Password Aplikasi Gmail

### Langkah-langkah:

1. **Buka Google Account**
   - Kunjungi: https://myaccount.google.com/

2. **Aktifkan 2-Step Verification**
   - Klik "Security" di menu kiri
   - Scroll ke "2-Step Verification"
   - Klik dan ikuti langkah-langkahnya
   - **PENTING:** 2-Step Verification harus aktif untuk membuat App Password

3. **Buat App Password**
   - Setelah 2-Step Verification aktif
   - Kembali ke "Security"
   - Scroll ke "App passwords" (atau cari "App passwords")
   - Klik "App passwords"
   - Pilih "Mail" sebagai app
   - Pilih "Other" sebagai device, ketik "Laravel Website"
   - Klik "Generate"
   - **Copy password 16 karakter** yang muncul (tanpa spasi)
   - Paste ke `MAIL_PASSWORD` di file `.env`

---

## 📝 Contoh Konfigurasi Lengkap

### Menggunakan Gmail:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=smkn4bogor@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=smkn4bogor@gmail.com
MAIL_FROM_NAME="SMK Negeri 4 Bogor"

CONTACT_EMAIL=berliananishanayla@gmail.com
```

**Catatan:**
- `MAIL_USERNAME` = Email Gmail sekolah
- `MAIL_PASSWORD` = App Password 16 karakter dari Gmail (bukan password login biasa!)
- `MAIL_FROM_ADDRESS` = Email pengirim (biasanya sama dengan MAIL_USERNAME)
- `CONTACT_EMAIL` = Email yang akan menerima pesan dari form kontak

---

## 🌐 Alternatif: Menggunakan Mailtrap (Untuk Testing)

Jika ingin test dulu tanpa mengirim email sungguhan:

1. **Daftar di Mailtrap**
   - Kunjungi: https://mailtrap.io/
   - Daftar gratis

2. **Dapatkan Kredensial SMTP**
   - Login ke Mailtrap
   - Klik inbox Anda
   - Copy kredensial SMTP

3. **Update `.env`**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=username_dari_mailtrap
   MAIL_PASSWORD=password_dari_mailtrap
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=test@example.com
   MAIL_FROM_NAME="SMK Negeri 4 Bogor"
   
   CONTACT_EMAIL=admin@example.com
   ```

---

## 🚀 Cara Test Email

### 1. Clear Config Cache
Setelah edit `.env`, jalankan:
```bash
php artisan config:clear
php artisan cache:clear
```

### 2. Test Kirim Email
1. Buka halaman kontak: `http://127.0.0.1:8000/contact`
2. Isi form dengan data test
3. Klik "Kirim Pesan"
4. Cek email di inbox `CONTACT_EMAIL` yang Anda set

### 3. Jika Ada Error
Cek log error di:
```
storage/logs/laravel.log
```

---

## 📧 Format Email yang Dikirim

Email yang diterima akan berisi:
- ✅ Nama pengirim
- ✅ Email pengirim (bisa langsung reply)
- ✅ Nomor telepon (jika diisi)
- ✅ Subjek pesan
- ✅ Isi pesan lengkap
- ✅ Waktu pengiriman
- ✅ Desain email yang rapi dan profesional

---

## 🔧 Troubleshooting

### Error: "Failed to authenticate"
**Solusi:**
- Pastikan 2-Step Verification Gmail sudah aktif
- Gunakan App Password, bukan password login biasa
- Pastikan tidak ada spasi di App Password

### Error: "Connection refused"
**Solusi:**
- Cek MAIL_HOST dan MAIL_PORT sudah benar
- Pastikan firewall tidak memblokir port 587
- Coba ganti MAIL_PORT ke 465 dan MAIL_ENCRYPTION ke ssl

### Email tidak masuk
**Solusi:**
- Cek folder Spam/Junk
- Pastikan CONTACT_EMAIL sudah benar
- Cek log: `storage/logs/laravel.log`
- Test dengan Mailtrap dulu

### Error: "Address in mailbox given does not comply with RFC"
**Solusi:**
- Pastikan MAIL_FROM_ADDRESS adalah email yang valid
- Pastikan tidak ada spasi di email address

---

## 💡 Tips Keamanan

1. **Jangan commit file `.env`** ke Git
   - File `.env` sudah ada di `.gitignore`
   - Jangan pernah share App Password

2. **Gunakan Email Khusus**
   - Buat email khusus untuk website (contoh: website@smkn4bogor.ac.id)
   - Jangan gunakan email personal

3. **Batasi Rate Limit**
   - Tambahkan rate limiting untuk mencegah spam
   - Sudah otomatis di Laravel

---

## 📱 Notifikasi Tambahan (Opsional)

### Kirim Notifikasi ke WhatsApp
Jika ingin notifikasi WhatsApp saat ada pesan baru, bisa integrasikan dengan:
- Twilio WhatsApp API
- WhatsApp Business API
- Fonnte.com (Indonesia)

### Kirim ke Telegram
Bisa juga kirim notifikasi ke Telegram Bot.

---

## ✅ Checklist Setup

- [ ] Edit file `.env` dengan kredensial email
- [ ] Aktifkan 2-Step Verification di Gmail
- [ ] Generate App Password di Gmail
- [ ] Set `CONTACT_EMAIL` dengan email tujuan
- [ ] Jalankan `php artisan config:clear`
- [ ] Test kirim email dari form kontak
- [ ] Cek email masuk di inbox
- [ ] Cek folder Spam jika tidak ada di inbox

---

## 🎉 Selesai!

Setelah konfigurasi selesai:
1. User mengisi form kontak
2. Klik "Kirim Pesan"
3. Email otomatis terkirim ke `CONTACT_EMAIL` Anda
4. Anda bisa langsung reply dari email tersebut

---

## 📞 Bantuan

Jika masih ada masalah, cek:
1. File log: `storage/logs/laravel.log`
2. Pastikan semua langkah sudah diikuti
3. Test dengan Mailtrap dulu untuk memastikan kode berfungsi

Selamat mencoba! 📧
