# 🏢 KosBooking Pro — Sistem Manajemen & Pemesanan Kamar Kos Online

Aplikasi web berbasis sistem informasi manajemen dan pemesanan kamar kos/homestay secara online. [cite_start]Sistem ini mengintegrasikan seluruh proses transaksi penyeledikan properti, fitur manajemen keranjang belanja, unggah bukti bayar, hingga halaman dashboard pengelolaan operasional bagi admin properti dalam satu ekosistem[cite: 13, 19, 20].

## 🔗 Tautan Aplikasi
* **Link Deployment:** [http://sistemkos.pdwtiumy.click/](http://sistemkos.pdwtiumy.click/)
* **Repository GitHub:** [https://github.com/Kysohee18/Kelompok2_SistemKos.git](https://github.com/Kysohee18/Kelompok2_SistemKos.git)

---

## 🛠️ Spesifikasi Teknologi (Tech Stack)
* [cite_start]**Front-end:** Tailwind CSS (Utama) & JavaScript [cite: 22]
* **Back-end:** PHP Native (PDO MySQL API Terstruktur)
* **Database:** MySQL Pro (Database Name: `kosbooking`)
* **Web Server Environment:** Laragon / XAMPP Apache Server
* **Deployment Workflow:** Terintegrasi otomatis melalui auto-deployment tersambung langsung dengan *branch main* repository GitHub ini.

---

## 👥 Analisis Use Case Sistem

<img width="1216" height="1084" alt="Use_Case_Sistem_Pemesanan" src="https://github.com/user-attachments/assets/b5841383-6ad0-412f-8a30-44e6cea4d6e3" />

Aplikasi ini membagi hak akses ke dalam 2 aktor utama (Admin dan User) dengan rincian use case esensial sebagai berikut:

### 1. Aktor: User (Calon Penghuni)
* [cite_start]**Melihat Katalog Kamar:** Menelusuri seluruh daftar jenis kamar kos yang tersedia beserta kelengkapan harga sewa, fasilitas, dan foto dokumentasi[cite: 27].
* [cite_start]**Mengelola Keranjang Pemesanan (Cart):** Menyimpan pilihan kamar sementara dan mengatur jumlah durasi sewa (hitungan bulan)[cite: 29].
* [cite_start]**Checkout & Kalkulasi Biaya:** Menghitung total biaya sewa secara otomatis akumulatif termasuk penambahan biaya deposit awal[cite: 31].
* [cite_start]**Unggah Bukti Pembayaran:** Mengunggah berkas gambar/foto transfer bank agar status pesanan dapat diproses[cite: 33].
* [cite_start]**Penilaian & Ulasan (Review):** Memberikan ulasan tertulis serta penilaian rating bintang terhadap kualitas kamar kos setelah masa tinggal selesai[cite: 40].

### 2. Aktor: Admin (Pengelola / Pemilik Proyek)
* [cite_start]**Dashboard Utama:** Memantau ringkasan statistik hunian kamar, riwayat pesanan masuk, dan ringkasan aktivitas ekosistem kos[cite: 20].
* [cite_start]**Manajemen CRUD Properti:** Menambah, mengubah informasi data, atau menghapus listing kamar kos di dalam katalog[cite: 35].
* [cite_start]**Validasi Transaksi:** Memeriksa berkas pembayaran dan mengubah status pesanan (Pending, Booked, Active, Cancelled) secara real-time[cite: 35, 38].

---

## 📸 Dokumentasi Antarmuka Aplikasi (Screenshot)

*(Catatan: Ambil gambar halaman web Anda yang sudah dideploy, lalu simpan di folder proyek Anda dengan nama folder `assets/`. Pastikan penamaan file sesuai di bawah ini agar gambar otomatis tampil di GitHub).*

### 1. Halaman Beranda (Home Page)
Tampilan landing page modern yang menyajikan fitur pencarian kamar unggulan dan ulasan testimoni dari penghuni.
<img width="1917" height="1017" alt="image" src="https://github.com/user-attachments/assets/92693583-cd18-407a-8d98-1a0d521363f6" />

### 2. Halaman Autentikasi (Login & Register)
Formulir masuk aman bagi pengguna menggunakan verifikasi akun terdaftar dan formulir bagi user yang ingin mendaftar
<img width="1918" height="1017" alt="image" src="https://github.com/user-attachments/assets/1a8d3dea-3ec2-4ba6-96ad-d55835bdf531" />
<img width="1918" height="1020" alt="image" src="https://github.com/user-attachments/assets/30afa911-4f0b-414a-b09c-f02374ca3d7a" />

### 3. Halaman Katalog Kamar Kos
[cite_start]Antarmuka grid yang menampilkan seluruh list properti kamar kos lengkap dengan deskripsi fasilitas dan tombol pesan[cite: 27].
<img width="1918" height="1020" alt="image" src="https://github.com/user-attachments/assets/4b23b8f1-b76a-417a-b172-c6516b63a488" />

### 4. Halaman Keranjang & Pengaturan Durasi dengan tambah dan kurang (Cart)
[cite_start]Modul bagi user untuk mengatur lama sewa kamar kos per bulan sebelum beralih ke transaksi pembayaran[cite: 29].
<img width="1918" height="1020" alt="image" src="https://github.com/user-attachments/assets/d086f006-757a-4f63-8e35-3c9af8288d29" />
<img width="1918" height="1018" alt="image" src="https://github.com/user-attachments/assets/214b194d-8c4d-40c5-be01-323f03c242c4" />


### 5. Halaman Riwayat Transaksi, Upload Bukti Transfer, Melihat Detail
[cite_start]Fasilitas user dalam memantau status pesanan dan mengunggah bukti transaksi, serta melihat detail transaksi[cite: 33, 38].
<img width="1918" height="1017" alt="image" src="https://github.com/user-attachments/assets/35019011-04cf-44e5-abf5-c517eef2ff52" />
<img width="1917" height="1020" alt="image" src="https://github.com/user-attachments/assets/b160b48b-7a46-4b9a-987c-d499e2c62d31" />
<img width="1918" height="1017" alt="image" src="https://github.com/user-attachments/assets/01fd004d-7b75-4a20-9fae-17f71f232a8d" />


### 6. Halaman Untuk menulis Review
[cite_start] Ketika pembayaran sukses dan telah dikonfirmasi sebagai penghuni aktif, maka user bisa mereview[cite: 35].
<img width="1918" height="1018" alt="image" src="https://github.com/user-attachments/assets/abda7178-ebeb-452b-a604-0dddfaac5c47" />
<img width="1918" height="1017" alt="image" src="https://github.com/user-attachments/assets/a4684570-0d25-4d54-8340-03e36aa4e469" />

### 7. Halaman Panel Kontrol Admin (Dashboard Property)
[cite_start]Panel khusus admin kos untuk mengelola ketersediaan kamar (CRUD) dan verifikasi finansial[cite: 35].
- Overview 
<img width="1918" height="1017" alt="image" src="https://github.com/user-attachments/assets/548ffdd8-7eb3-4224-8f3d-b78eeca15b02" />
- Kelola Kamar
<img width="1918" height="1017" alt="image" src="https://github.com/user-attachments/assets/d3d56295-30d4-4a7f-88a3-4b074a960ca4" />
<img width="1918" height="1001" alt="image" src="https://github.com/user-attachments/assets/3e35fdd1-364b-4f79-8909-2e5dcc2cec09" />
<img width="1918" height="1020" alt="image" src="https://github.com/user-attachments/assets/04d5b785-9645-46a0-bf38-c92b420e87a3" />
<img width="1918" height="1018" alt="image" src="https://github.com/user-attachments/assets/ce53272c-5103-4969-b562-1287af4d8929" />
- Verifikasi Traksaksi
<img width="1918" height="1020" alt="image" src="https://github.com/user-attachments/assets/01f8da93-261c-48f1-b228-2538ab0e266e" />
<img width="1918" height="1017" alt="image" src="https://github.com/user-attachments/assets/bd9aa73b-256d-44e8-ae94-44296c11a630" />
<img width="1918" height="1017" alt="image" src="https://github.com/user-attachments/assets/13a97b7f-62e1-47db-a6fa-02b9f7a0e1c8" />
<img width="1918" height="1017" alt="image" src="https://github.com/user-attachments/assets/a12f3c75-4600-4cbf-806e-712e9a289783" />
<img width="1918" height="1020" alt="image" src="https://github.com/user-attachments/assets/a58405b9-1f3c-46f0-a291-a9a9bcd1902e" />
- Kelola Profil
<img width="1918" height="1017" alt="image" src="https://github.com/user-attachments/assets/6c5f6c27-fc0d-47f7-9e07-a81798c1f111" />



---

## 🚀 Panduan Menjalankan Proyek Secara Lokal

### Langkah 1: Kloning Repositori
Buka Command Prompt (CMD) atau Terminal, arahkan direktori aktif ke dalam folder server lokal Anda (`htdocs` jika XAMPP, atau `www` jika Laragon), lalu jalankan perintah:
```bash
git clone [https://github.com/Kysohee18/Kelompok2_SistemKos.git](https://github.com/Kysohee18/Kelompok2_SistemKos.git)


---
### ⚠️Kalau mau import databasenya yang databaseUpdate.sql

**cara running : (contohnya pakai command promt)**
1. buka cmd
2. cd/ change directory ke htdocs(kalo pake XAMPP) atau ke www(kalo pakai laragon)
3. ketik : git clone https://github.com/Kysohee18/Kelompok2_SistemKos.git

**Laragon/Xampp:**
1. Start Apache dan MySql
2. Masuk ke phpMyAdmin atau kalo di laragon : "https://localhost/phpmyadmin/index.php"
3. Setelah login klik new
<img width="296" height="508" alt="image" src="https://github.com/user-attachments/assets/39281166-9fb5-4431-a231-e1b0a73e7329" />

4. Pilih tab import dan pilih file nya yang databaseUpdate.sql di folder yang tadi di clone
<img width="1610" height="553" alt="image" src="https://github.com/user-attachments/assets/0b173149-0ab5-4ef2-a5bb-94e81b9ec0c0" />

5. Klik import/go
<img width="1600" height="120" alt="image" src="https://github.com/user-attachments/assets/5b2693bc-7944-495f-ac27-33909bece81e" />

6. Ulangi langkah import untuk file seed.sql

7. Buka link : http://localhost/Kelompok2_SistemKos/

**Login:**
1. Admin :
   - username : admin
   - password : admin123
2. User :
   - username : user
   - password : user123
