# Aplikasi Chatting

Proyek ini adalah aplikasi chatting yang dapat diunggah ke server hosting. 
Aplikasi ini dirancang agar **User Friendly** dan **Mobile Friendly**. 
Meskipun mungkin tidak berguna dalam "Real Life", siapa tahu aplikasi ini bisa digunakan untuk belajar, mengembangkan, atau sekadar untuk tugas kuliah.

Kalian bisa pakai Hostingan untuk deployment : [Hostingan](https://hostingan.id) 

sanagat sederhana:
Menggunakan PHP dan Javascript

## Fitur

- **Chat dengan Secret ID**: Hanya orang yang saling tahu dan memasukkan Secret ID pengguna yang bisa melakukan chat.
- **Auto Delete Chat**: Chat akan otomatis dihapus menggunakan interval dan cron job.
- **Validasi Username dan Secret Code**: Memastikan tidak ada kesamaan username dan secret code untuk menghindari konflik.

## Prerequisites

- Hosting dengan akses FTP atau file manager.
- Database MySQL.

## Langkah 1: Meng-upload File ke Hosting

1. **Akses Hosting Anda**:
   - Gunakan FTP client seperti FileZilla atau akses file manager melalui panel kontrol hosting Anda.

2. **Upload File Proyek**:
   - Arahkan ke direktori root (biasanya `public_html` atau `www`).
   - Unggah semua file proyek Anda ke dalam direktori ini.

## Langkah 2: Mengatur Database

1. **Buka phpMyAdmin**:
   - Akses phpMyAdmin melalui panel kontrol hosting Anda.

2. **Buat Database**:
   - Klik "Databases" di menu atas.
   - Masukkan nama database yang ingin Anda buat (misalnya `chat_db`) dan klik "Create".

3. **Import Database (Jika Ada)**:
   - Jika Anda memiliki file SQL untuk struktur tabel, pilih database yang baru dibuat, lalu klik "Import" dan unggah file SQL tersebut.

## Langkah 3: Mengonfigurasi `config.php`

1. **Edit File `config.php`**:
   - Buka file `config.php` yang ada di dalam folder proyek Anda.
   - Sesuaikan pengaturan berikut dengan nama database yang telah Anda
 
## Langkah 4: Mengatur Cron
     
 2. **Tambahkan Cron Job Baru**
    - Ini berguna agar menghapus chat secara otomatis denga interval 30 menit << silahkan ubah sendiri >>

- **Interval**: Pilih frekuensi di mana Anda ingin cron job dijalankan. Misalnya, untuk menjalankan setiap jam, pilih `Once Per Hour`.
- **Perintah**: Kalian juga bisa pakai cron PHP dari luar, contohnya cronjobs.org arahkan saja file `cleanup.php`:

```php
domainmu.com/cleanup.php
  
