# ukom-galeri-foto

[![CI](https://github.com/Ivann4y/ukom-galeri-foto/actions/workflows/ci.yml/badge.svg)](https://github.com/Ivann4y/ukom-galeri-foto/actions/workflows/ci.yml)

Aplikasi galeri foto berbasis Laravel: pengguna bisa mendaftar, mengunggah foto ke album, saling memberi like, dan berkomentar. Dibuat sebagai proyek Uji Kompetensi Keahlian (UKOM) tahun 2024, lalu dirapikan pada November 2026.

## Fitur

- Registrasi (username, nama lengkap, email, alamat, password minimal 8 karakter), login, dan logout.
- Unggah foto (jpg, jpeg, png, webp; maksimal 2 MB) dengan caption.
- Halaman profil berisi daftar foto milik pengguna yang sedang login.
- Detail foto: gambar, caption, jumlah like, dan daftar komentar.
- Like dengan perilaku toggle (klik sekali menyukai, klik lagi membatalkan).
- Komentar pada foto.
- Album: buat, lihat (grid foto), ubah nama/deskripsi, dan hapus.
  Menghapus album hanya melepas foto dari album (`id_album` menjadi `null`); file dan data foto tidak dihapus.
- Ubah caption dan album sebuah foto, serta hapus foto.
- Otorisasi kepemilikan: hanya pemilik yang boleh mengubah atau menghapus foto dan album (selain itu 403).
- Validasi input terpusat di FormRequest, dengan pesan error yang tampil di form.

## Stack

- Laravel 10.45 (PHP >= 8.1, disarankan 8.2)
- MySQL / MariaDB
- Blade + Tailwind CSS dan Flowbite (keduanya lewat CDN)
- Vite/npm tidak dipakai — aset disajikan lewat CDN, jadi `npm install` tidak diperlukan

## Skema database

| Tabel | Kolom penting |
|---|---|
| `users` | `id_user`, `username` (unik), `fullname`, `email` (unik), `alamat`, `password` |
| `albums` | `id_album`, `nama_album`, `deskripsi`, `id_user` |
| `fotos` | `id_foto`, `judul_foto`, `deskripsi`, `path_foto`, `id_album` (nullable), `id_user` |
| `likes` | `id_like`, `id_foto`, `id_user` |
| `komentars` | `id_komentar`, `id_foto`, `id_user`, `komentar` |

## Menjalankan secara lokal

Prasyarat: PHP 8.1+ dengan ekstensi `mbstring`, `fileinfo`, `pdo_mysql`, `gd`, `openssl`; Composer 2; MySQL 8 atau MariaDB.

```bash
git clone https://github.com/Ivann4y/ukom-galeri-foto.git
cd ukom-galeri-foto
composer install
cp .env.example .env
php artisan key:generate
```

Sesuaikan `.env`:

```env
DB_DATABASE=pra_ukom2
DB_USERNAME=root
DB_PASSWORD=
FILESYSTEM_DISK=public
```

Lalu:

```bash
php artisan migrate
php artisan storage:link
php artisan serve
```

Buka http://127.0.0.1:8000 — akan diarahkan ke halaman sign in. Buat akun baru lewat `/signup`.

### Catatan setup

- `FILESYSTEM_DISK=public` dan `php artisan storage:link` wajib. View memanggil `asset('storage/' . $path_foto)`; kalau disk masih `local`, gambar akan 404 walaupun upload berhasil.
- Migrasi `add_unique_index_to_users_table` menambahkan indeks unik pada `username` dan `email`. Kalau database lama sudah punya data duplikat, migrasi ini akan gagal — bersihkan datanya lebih dulu.

## Halaman utama

| Route | Keterangan |
|---|---|
| `/signup`, `/signin` | Registrasi dan login |
| `/` | Beranda / daftar foto |
| `/profil` | Foto milik pengguna |
| `/newFoto`, `/editFoto/{id_foto}`, `/deleteFoto/{id_foto}` | Unggah, ubah caption, hapus foto |
| `/album`, `/newAlbum`, `/editAlbum/{id_album}`, `/deleteAlbum/{id_album}` | Kelola album |
| `/detailFoto/{id_foto}`, `/detailAlbum/{id_album}` | Detail foto dan album |
| `/likeFoto/{id_foto}`, `/komentar/{id_foto}` | Like dan komentar |

## Status dan batasan

Repository ini adalah proyek UKOM 2024 yang dirapikan pada November 2026: nama repo diperjelas, validasi input diisi, bug like/komentar diperbaiki, hak akses dibatasi ke pemilik data, dan CI ditambahkan.

Yang perlu diketahui sebelum memakai kode ini:

- Dependency masih versi 2024 (Laravel 10.45.1, Guzzle 7.8.1). Laravel 10 sudah tidak menerima patch keamanan, jadi jangan dipakai untuk produksi.
- Belum ada test otomatis. CI hanya memeriksa sintaks PHP, mendaftar route, dan mengompilasi view — bukan menguji perilaku aplikasi.
- Hapus foto masih menggunakan `GET /deleteFoto/{id_foto}`, belum `DELETE` dengan form dan CSRF.
- Tailwind dan Flowbite dimuat dari CDN, bukan bundel lokal.
