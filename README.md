# KasirKita

Aplikasi web POS dan manajemen pelanggan berbasis Laravel 12 untuk PHP 8.2+.

## Fitur

- Ringkasan penjualan, jumlah pelanggan, produk, dan stok menipis.
- Katalog produk dengan pencarian, tambah, ubah, dan hapus.
- Kasir dengan keranjang belanja, pilihan pelanggan, dan pencatatan pembayaran.
- Validasi stok dan pengambilan harga dari database; penyimpanan pesanan, detail, dan pengurangan stok berjalan atomik.
- Riwayat transaksi dan detail struk.
- Manajemen pelanggan dengan pencarian, tambah, ubah, dan hapus.

Belum ada autentikasi pada scaffold Laravel awal, sehingga aplikasi ini tidak menambahkan sistem login buatan sendiri. Pasang middleware autentikasi/otorisasi sebelum mengekspos aplikasi ke internet atau lingkungan produksi.

## Persyaratan

- PHP 8.2+
- Composer 2+
- Node.js dan npm
- SQLite (default) atau database yang didukung Laravel

## Menjalankan aplikasi

```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`. Untuk mode pengembangan dengan hot reload, jalankan `npm run dev` di terminal terpisah.

Seeder menyediakan beberapa produk dan pelanggan contoh. Gunakan `php artisan migrate:fresh --seed` hanya untuk database pengembangan karena perintah tersebut menghapus data yang sudah ada.

## Pengujian

```sh
php artisan test
vendor/bin/pint --test
npm run build
```
