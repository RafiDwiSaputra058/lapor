<<<<<<< HEAD
# 📢 Lapor Pak — Aplikasi Pengaduan Fasilitas Umum
=======
<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>
>>>>>>> 546867b13341d5143545556aa08380ef1025402d

Lapor Pak adalah aplikasi berbasis web untuk memudahkan warga dalam melaporkan kerusakan atau masalah fasilitas umum di lingkungan sekitar. Laporan dapat dipantau perkembangannya secara real-time oleh pelapor.

---

## ✨ Fitur

### 👤 Sisi User
- **Beranda** — Melihat kategori laporan dan laporan terbaru dari seluruh warga
- **Kamera** — Membuat laporan dengan mengambil foto terlebih dahulu, lalu mengonfirmasi sebelum mengisi form
- **Form Laporan** — Mengisi judul, kategori, deskripsi, dan lokasi otomatis via GPS
- **Laporanmu** — Melihat riwayat laporan pribadi yang dikelompokkan berdasarkan status (Terkirim, Diproses, Selesai, Ditolak)
- **Detail Laporan** — Melihat detail laporan beserta riwayat perkembangan status
- **Profil** — Informasi akun dan tombol logout
- **Daftar & Login** — Registrasi akun baru dengan foto profil dan login untuk mengakses fitur

### 🛠️ Sisi Admin
- **Data User** — Manajemen data pengguna (CRUD)
- **Data Kategori** — Manajemen kategori laporan seperti Infrastruktur, Lingkungan, Keamanan, Kesehatan (CRUD)
- **Data Laporan** — Manajemen seluruh laporan yang masuk dari warga (CRUD)
- **Status Laporan** — Memperbarui status laporan (Pending, In Progress, Selesai, Ditolak) beserta bukti foto dan deskripsi progres

---

## 🛠️ Teknologi

- **Backend** — Laravel 11
- **Frontend** — Bootstrap 5, Leaflet.js, Lottie
- **Database** — MySQL
- **Storage** — Laravel Storage (public disk)
- **Map** — OpenStreetMap + Nominatim (reverse geocoding)
- **Auth** — Laravel built-in authentication

---

## ⚙️ Instalasi

### 1. Clone repository
```bash
git clone https://github.com/username/lapor-pak.git
cd lapor-pak
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Konfigurasi environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` sesuaikan database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lapor_pak
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & seeder
```bash
php artisan migrate --seed
```

### 5. Storage link
```bash
php artisan storage:link
```

### 6. Jalankan aplikasi
```bash
php artisan serve
```

Buka di browser: `http://127.0.0.1:8000`

---


<<<<<<< HEAD
---

## 📄 Lisensi

Project ini dibuat untuk keperluan pembelajaran. Bebas digunakan dan dimodifikasi.
=======
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# Lapor
>>>>>>> 040d5bc5ae5730ff145b0970dec7b98f1491ca0e
>>>>>>> 546867b13341d5143545556aa08380ef1025402d
