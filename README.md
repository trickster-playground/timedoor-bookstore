# 📚 Timedoor Bookstore API

## Deskripsi

Aplikasi ini merupakan sistem manajemen buku berbasis Laravel 10 dan MySQL, yang mencakup fitur-fitur berikut:

- **Daftar Buku**: Menampilkan buku-buku dengan informasi terkait.
- **Top Author**: Menampilkan Author dengan Voter terbanyak.
- **Input Rating**: Pengguna dapat memberikan rating pada buku.
- **Pencarian dan Filter**: Memungkinkan pencarian berdasarkan judul buku atau nama penulis.

## Teknologi yang Digunakan

- **Backend**: Laravel 10
- **Database**: MySQL
- **PHP**: Versi 8.1
- **Frontend**: Blade (minimalis)

## Persiapan Proyek

1. **Kloning Repositori**

   ```bash
   git clone https://github.com/trickster-playground/timedoor-bookstore.git
   cd timedoor-bookstore

2. **Instalasi Dependensi**

   ```bash
   composer install
   npm install

3. **Konfigurasi Env**

- Salin file .env.example menjadi .env dan sesuaikan konfigurasi database:

   ```bash
   cp .env.example .env
   ```

- Sesuaikan nilai-nilai berikut di file .env:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=timedoor_bookstore
   DB_USERNAME=root
   DB_PASSWORD=

4. **Generate Key Aplikasi**

   ```bash
   php artisan key:generate

5. **Migrasi dan Seeder Database**

   ```bash
   php artisan migrate --seed

## Proses ini akan membuat tabel-tabel berikut:

- authors

- categories

- books

- ratings

## Dan mengisi tabel-tabel tersebut dengan data palsu menggunakan Faker.

## Fitur Utama
 # Daftar Buku
  - Menampilkan daftar buku dengan informasi seperti:

    - Judul

    - Kategori

    - Penulis

    - Rating

    - Jumlah Pemberi Rating

    - Input Rating
    Pengguna dapat memberikan rating (1-10) pada buku tertentu.

    - Pencarian dan Filter
      Pengguna dapat mencari buku berdasarkan judul atau nama penulis, serta memfilter jumlah buku yang ditampilkan per halaman.
