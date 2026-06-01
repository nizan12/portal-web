# POLTREE - Portal Link Terintegrasi

**POLTREE** adalah platform web portal layanan terintegrasi yang dirancang untuk mengorganisasi, memantau, dan mempercepat akses ke berbagai layanan internal dan eksternal kampus. Platform ini mengusung desain **Premium Glassmorphism**, interaksi intuitif, dan animasi fluid untuk memberikan pengalaman pengguna yang luar biasa.

---

## 🌟 Fitur Utama Sistem

### 1. Sistem Autentikasi Modern & Dinamis (GSAP)
* **GSAP Fluid Transitions:** Halaman Login, Lupa Password, dan Atur Ulang Kata Sandi dilengkapi animasi masuk yang sangat halus dan premium menggunakan GreenSock Animation Platform (GSAP).
* **Reset Password Interaktif (Local Dev Mode):** Alur pemulihan kata sandi mandiri yang aman dan interaktif, memudahkan pengguna dan administrator melakukan reset kata sandi secara instan tanpa ketergantungan konfigurasi SMTP lokal.

### 2. Dual-Dashboard (Admin & Pengguna)
* **Admin Dashboard:**
  * Manajemen CRUD Layanan Utama, Kategori Terdaftar, dan Tag Layanan.
  * Panel Monitoring Kesehatan Layanan (*Health Check*) real-time.
  * Grafik visual persentase status online/downtime dan metrik rata-rata waktu respon (*Response Time*).
  * Statistik ringkasan (Top Clicked Links & Kategori Teraktif).
* **User Dashboard:**
  * Koleksi layanan personal terintegrasi.
  * Tambah kategori kustom dengan pemilihan ikon dinamis.
  * Navigasi tab pintar (*Semua Layanan*, *Disimpan*, *Kategori Kustom*).

### 3. Portal-Based Premium Dropdown Component
* Menghilangkan isu komponen terpotong (*clipping*) pada container modal scrollable dengan menerapkan **Radix/Popper Portal pattern**.
* Dropdown di-render dan ditempelkan langsung pada `document.body` dengan penentuan koordinat fixed dinamis berbasis `getBoundingClientRect`.

### 4. Responsivitas Penuh & Desain Ramah Mobile
* **Horizontal Scroll Table:** Tabel data admin secara otomatis menerapkan pembatas viewport fleksibel dan scroll horizontal yang mulus pada perangkat mobile tanpa memecah lebar halaman utama.
* **Responsive Sidebar:** Sidebar admin meluncur masuk secara dinamis pada layar sentuh dengan hamburger menu dan backdrop overlay kaca buram (*frosted-glass backdrop*).
* Grid visual yang menyesuaikan ukuran dari layar desktop ultra-lebar hingga perangkat seluler terkecil (`minmax(290px, 1fr)`).

---

## 🛠️ Spesifikasi Teknologi

* **Backend Framework:** Laravel (PHP 8.x)
* **Asset Bundler:** Vite
* **Frontend Tech:** HTML5, CSS3 Custom Variables (Vanilla Premium Styles), Vanilla Javascript ES6
* **Animation Library:** GSAP (GreenSock Animation Platform) via CDN
* **Database:** MySQL / MariaDB

---

## 🚀 Panduan Instalasi & Penggunaan

Ikuti langkah-langkah di bawah untuk menjalankan proyek **POLTREE** di server lokal Anda:

### 1. Persiapan Awal
Kloning repositori proyek ke direktori lokal Anda (misalnya Laragon atau XAMPP):
```bash
git clone https://github.com/nizan12/portal-web.git
cd poltree-rapip
```

### 2. Instalasi Dependensi Backend
Instal pustaka PHP yang diperlukan menggunakan Composer:
```bash
composer update
composer install
```

### 3. Instalasi Dependensi Frontend
Instal pustaka Javascript yang diperlukan menggunakan NPM:
```bash
npm install
```

### 4. Konfigurasi Lingkungan (`.env`)
Salin file konfigurasi contoh `.env.example` ke `.env`:
```bash
cp .env.example .env
```
Buka file `.env` yang baru dibuat dan sesuaikan kredensial koneksi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=poltree_db
DB_USERNAME=root
DB_PASSWORD=
```
Hasilkan kunci enkripsi aplikasi:
```bash
php artisan key:generate
```

### 5. Migrasi Database dan Seeders
Jalankan migrasi database beserta data awal untuk membuat tabel sistem dan akun uji coba:
```bash
php artisan migrate --seed
```

### 6. Kompilasi Aset Frontend
Lakukan kompilasi aset CSS dan Javascript menggunakan Vite untuk mode produksi:
```bash
npm run build
```
Atau jalankan server pengembangan lokal secara real-time:
```bash
npm run dev
```

### 7. Jalankan Server Aplikasi
Jalankan perintah berikut untuk mengaktifkan server lokal Laravel:
```bash
php artisan serve
```
Akses aplikasi melalui peramban web Anda di alamat: `http://127.0.0.1:8000`

---

## 🔑 Kredensial Akun Default (Hasil Seeds)

Setelah melakukan `--seed`, Anda dapat menggunakan akun bawaan berikut untuk menguji sistem:

### Akun Administrator (Admin Panel)
* **NIP/Username:** `111` (atau sesuai data seeder)
* **Password:** `admin123` (atau password default sistem)

### Akun Pengguna (User Dashboard)
* **NIK/Username:** `222` (atau sesuai data seeder)
* **Password:** `user123` (atau password default sistem)

---

## 📁 Struktur Direktori Penting

* `app/Http/Controllers/` - Mengontrol logika autentikasi, dashboard, dan operasi CRUD.
* `resources/views/` - File Blade untuk User Interface (Layouts, Auth, Admin, Pengguna).
* `resources/css/admin.css` - Lembar gaya utama admin, kustomisasi komponen, media queries, dan UI responsif.
* `resources/js/pengguna.js` - Logika interaktif panel pengguna dan pengelolaan custom dropdown.
* `routes/web.php` - Registrasi endpoint rute sistem.

---
*Dikembangkan dengan penuh dedikasi untuk menciptakan User Experience (UX) terbaik di lingkungan sistem portal kampus.*
