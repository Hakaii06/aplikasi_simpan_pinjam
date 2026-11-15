# Aplikasi Simpan Pinjam

Aplikasi web berbasis Laravel 12, MySQL, Bootstrap, dan FPDF untuk autentikasi pengguna, pengelolaan anggota, transaksi simpanan/pinjaman, dan pencetakan laporan PDF.

## Fitur

- Registrasi, login, dan logout.
- CRUD anggota (nomor anggota, nama, alamat, dan telepon); setiap anggota dimiliki oleh pengguna yang mendaftarkannya.
- CRUD transaksi simpanan (setoran/penarikan) dan pinjaman (pencairan/angsuran).
- Daftar, filter, dan ringkasan transaksi.
- Laporan PDF anggota dan transaksi dengan tanggal cetak serta jumlah data.
- Validasi formulir dan pembatasan data antar pengguna.

## Menjalankan aplikasi

Persyaratan: PHP 8.2+, Composer, MySQL, dan ekstensi PHP `pdo_mysql` serta `iconv`.

1. Buat database MySQL bernama `aplikasi_simpan_pinjam`.
2. Salin `.env.example` menjadi `.env`, lalu sesuaikan `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan MySQL lokal.
3. Jalankan perintah berikut dari folder proyek:

   ```sh
   composer install
   php artisan key:generate
   php artisan migrate
   php artisan serve
   ```

4. Buka URL yang ditampilkan oleh `php artisan serve`, lalu daftar untuk membuat akun.

PDF dibuat dengan paket `setasign/fpdf` yang sudah terdaftar sebagai dependensi Composer; tidak perlu instalasi manual.

## Menjalankan pengujian

```sh
php artisan test
```
