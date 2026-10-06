# Keuangan MCM

Aplikasi pencatatan dan pengelolaan transaksi keuangan perusahaan berbasis Laravel.

## Fitur

- Pencatatan transaksi keuangan
- Sumber dana
- Tujuan transaksi
- Nomor bukti transaksi otomatis
- Upload bukti transaksi
- Daftar transaksi
- Hapus transaksi
- Pencatatan kas kantor
- Pencatatan transaksi langsung dari bos
- Perhitungan saldo kas berdasarkan transaksi

## Teknologi

- Laravel
- PHP
- MySQL
- Bootstrap
- Vite
- JavaScript

## Instalasi

Clone repository:

    git clone <URL_REPOSITORY>
    cd keuangan_mcm

Install dependency PHP:

    composer install

Install dependency JavaScript:

    npm install

Salin file environment:

    cp .env.example .env

Generate application key:

    php artisan key:generate

Atur konfigurasi database pada file .env:

    DB_DATABASE=keuangan_mcm
    DB_USERNAME=root
    DB_PASSWORD=

Jalankan migration:

    php artisan migrate

Buat symbolic link storage:

    php artisan storage:link

Build asset:

    npm run build

## Menjalankan Development

Jalankan Laravel:

    php artisan serve

Untuk development frontend:

    npm run dev

## Struktur Utama

    app/
    ├── Http/
    │   └── Controllers/
    └── Models/

    database/
    ├── migrations/
    └── seeders/

    resources/
    └── views/

    routes/
    └── web.php

    storage/
    └── app/

## Konsep Transaksi

Aplikasi menggunakan dua sumber dana utama:

- Bos
- Kas Kantor

### Bos → Kas Kantor

Dana masuk ke kas kantor.

Saldo Kas Kantor bertambah dan transaksi ini bukan merupakan pengeluaran.

### Kas Kantor → Tujuan

Dana keluar dari kas kantor.

Saldo Kas Kantor berkurang dan transaksi dicatat sebagai pengeluaran.

### Bos → Tujuan

Bos melakukan pembayaran secara langsung.

Transaksi tidak menambah saldo Kas Kantor, tetapi tetap dicatat sebagai pengeluaran perusahaan.

## Bukti Transaksi

Bukti transaksi berupa gambar disimpan pada:

    storage/app/public/bukti-transaksi/

File bukti menggunakan kode transaksi sebagai nama file, misalnya:

    BPU001.jpg
    BPU002.png

## Nomor Bukti

Nomor bukti transaksi menggunakan format:

    BPU001
    BPU002
    BPU003

Nomor akan dimulai kembali dari BPU001 pada bulan berikutnya.

## Database

Tabel utama:

- sumber_dana
- tujuan_transaksi
- transaksi

## Environment

File berikut tidak disimpan dalam repository:

- .env
- vendor/
- node_modules/
- public/build/
- public/storage/

Pastikan konfigurasi database dan environment sudah benar sebelum menjalankan migration.

## License

Private / Internal Use