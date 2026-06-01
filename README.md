# POLTREE - Portal Link Terintegrasi

<div align="center">
  <a href="README.md">
    <img src="https://img.shields.io/badge/README-Panduan%20Utama-blue?style=for-the-badge&logo=markdown&logoColor=white" alt="README" />
  </a>
  &nbsp;&nbsp;
  <a href="explainationfungsi.md">
    <img src="https://img.shields.io/badge/EXPLAINATION-Alur%20Kerja%20MVC-grey?style=for-the-badge&logo=laravel&logoColor=white" alt="Explaination Fungsi" />
  </a>
</div>

---

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
php artisan migrate:fresh --seed
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

## 🔌 Integrasi API & Health Check

POLTREE memiliki modul pemantauan keaktifan website (*service uptime monitor*) yang terintegrasi secara otomatis menggunakan API eksternal **Downtime Check**:

* **Endpoint API Eksternal:** `https://downtimecheck.vercel.app/api/check`
* **Metode:** `GET`
* **Parameter Kirim:** 
  * `url` (string) - Alamat website tujuan yang akan diperiksa statusnya.
* **Format Respons API:**
  ```json
  {
    "success": true,
    "data": {
      "online": true,
      "statusCode": 200,
      "responseTimeMs": 142,
      "statusText": "OK",
      "errorMessage": null
    }
  }
  ```

---

## 🗄️ Fitur Basis Data Lanjutan

Untuk menjamin integritas data yang kokoh dan efisiensi pemrosesan data, sistem database POLTREE telah ditingkatkan dengan fitur basis data lanjutan sebagai berikut:

### 1. Database Trigger (`trg_after_link_update`)
* **Tujuan:** Mencatat log riwayat perubahan tautan secara otomatis ke tabel `t_audit_log`.
* **Cara Kerja:** Memicu aksi `AFTER UPDATE` pada tabel `t_link`. Jika terjadi perubahan pada kolom `status` atau `url`, data nilai lama (*OLD*) dan nilai baru (*NEW*) akan disimpan langsung ke tabel audit log.
* **Definisi SQL:**
  ```sql
  CREATE TRIGGER trg_after_link_update
  AFTER UPDATE ON t_link
  FOR EACH ROW
  BEGIN
      IF OLD.status <> NEW.status OR OLD.url <> NEW.url THEN
          INSERT INTO t_audit_log (table_name, action, record_id, old_value, new_value)
          VALUES (
              't_link',
              'UPDATE',
              OLD.id_link,
              CONCAT('status: ', OLD.status, ', url: ', OLD.url),
              CONCAT('status: ', NEW.status, ', url: ', NEW.url)
          );
      END IF;
  END
  ```

### 2. Stored Procedure (`sp_get_dashboard_statistics`)
* **Tujuan:** Melakukan penghitungan agregat seluruh data statistik dashboard admin dalam satu panggilan tunggal.
* **Fitur Terkandung:**
  * **Agregat:** Menghitung jumlah tautan (`COUNT`), total tautan aktif (`SUM(IF(status = 'aktif', 1, 0))`), dan rata-rata waktu respon (`AVG(status_response_time_ms)`).
  * **Subquery & Agregat:** Subquery internal tingkat lanjut untuk mendeteksi ID kategori yang paling aktif (memiliki tautan terbanyak) di tabel `t_terdaftar`, lalu mengambil nama kategori tersebut dari tabel `t_kategori`.
* **Definisi SQL:**
  ```sql
  CREATE PROCEDURE sp_get_dashboard_statistics(
      OUT out_total_links INT,
      OUT out_active_links INT,
      OUT out_avg_response_time INT,
      OUT out_most_active_category VARCHAR(100)
  )
  BEGIN
      SELECT 
          COUNT(*),
          SUM(IF(status = 'aktif', 1, 0)),
          AVG(IF(status_response_time_ms IS NOT NULL, status_response_time_ms, 0))
      INTO 
          out_total_links,
          out_active_links,
          out_avg_response_time
      FROM t_link;

      BEGIN
          DECLARE max_cat_id INT;
          SELECT id_kategori INTO max_cat_id
          FROM t_terdaftar 
          GROUP BY id_kategori 
          ORDER BY COUNT(id_link) DESC 
          LIMIT 1;

          IF max_cat_id IS NOT NULL THEN
              SELECT nama_kategori INTO out_most_active_category
              FROM t_kategori
              WHERE id_kategori = max_cat_id
              LIMIT 1;
          ELSE
              SET out_most_active_category = 'Tidak Ada';
          END IF;
      END;
  END
  ```

### 3. Stored Function (`sf_get_category_link_count`)
* **Tujuan:** Fungsi modular untuk menghitung jumlah tautan aktif yang terdaftar di bawah ID kategori tertentu.
* **Definisi SQL:**
  ```sql
  CREATE FUNCTION sf_get_category_link_count(cat_id INT)
  RETURNS INT
  DETERMINISTIC
  READS SQL DATA
  BEGIN
      DECLARE link_count INT;
      SELECT COUNT(*) INTO link_count
      FROM t_terdaftar
      WHERE id_kategori = cat_id;
      RETURN link_count;
  END
  ```

### 4. Database CHECK Constraint (`chk_link_status`)
* **Tujuan:** Menjamin konsistensi data tingkat tinggi dengan membatasi nilai yang masuk ke kolom `status` tabel `t_link`.
* **Definisi SQL:**
  ```sql
  ALTER TABLE t_link
  ADD CONSTRAINT chk_link_status
  CHECK (status IN ('aktif', 'bermasalah'))
  ```

---

## 📁 Struktur Direktori Penting

* `app/Http/Controllers/` - Mengontrol logika autentikasi, dashboard, dan operasi CRUD.
* `resources/views/` - File Blade untuk User Interface (Layouts, Auth, Admin, Pengguna).
* `resources/css/admin.css` - Lembar gaya utama admin, kustomisasi komponen, media queries, dan UI responsif.
* `resources/js/pengguna.js` - Logika interaktif panel pengguna dan pengelolaan custom dropdown.
* `routes/web.php` - Registrasi endpoint rute sistem.

---
*Dikembangkan dengan penuh dedikasi untuk menciptakan User Experience (UX) terbaik di lingkungan sistem portal kampus.*
