Panduan Penggunaan Fitur Admin
Fitur admin ini memungkinkan pengguna dengan hak akses tertentu untuk melakukan manajemen terhadap pengguna lainnya. Untuk mengaktifkan fitur admin, Anda perlu membuat tabel ganteng di database dan menambahkan pengguna admin dengan perintah SQL.

1. Membuat Tabel Admin ganteng
Untuk mulai menggunakan fitur admin, buatlah tabel ganteng di database Anda. Tabel ini akan digunakan untuk menyimpan data login admin.

Perintah SQL untuk Membuat Tabel
Jalankan perintah berikut di command line MySQL atau di tool database Anda untuk membuat tabel ganteng:


CREATE TABLE ganteng (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL

2. Menambahkan Pengguna Admin ke Tabel
Setelah tabel ganteng berhasil dibuat, tambahkan pengguna admin pertama dengan menggunakan perintah berikut:


INSERT INTO ganteng (username, password) VALUES ('admin', MD5('mysecurepassword'));

Catatan:

Password admin disimpan menggunakan algoritma MD5, berbeda dengan pengguna biasa yang disimpan menggunakan bcrypt.
Gantilah mysecurepassword dengan kata sandi yang kuat untuk admin.

akses admin : domain.com/admin
