# Ujian Tengah Semester - Web Service Development
## REST API Development dengan PHP dan MySQL

### Pembagian Objek REST API
| NIM | Nama | Objek API & Struktur Tabel |
|-----|------|---------------------------|
| 18.01.55.0068 | FANI CHOIRUL RIJAL | **Tabel: smartphones**<br>- id (PK)<br>- brand<br>- model<br>- price<br>- stock |
| 20.01.55.0025 | GALIH YUNUS AL ANAS | **Tabel: laptops**<br>- id (PK)<br>- brand<br>- model<br>- price<br>- stock |
| 21.01.55.0001 | FITRIA INDAH MUTIA RINI | **Tabel: cosmetics**<br>- id (PK)<br>- name<br>- brand<br>- price<br>- stock |
| 21.01.55.0002 | SITI ZUMAROH | **Tabel: restaurants**<br>- id (PK)<br>- name<br>- address<br>- phone<br>- rating |
| 21.01.55.0003 | NABILA ANTANIA PUTRI ANJANI | **Tabel: movies**<br>- id (PK)<br>- title<br>- genre<br>- year<br>- rating |
| 21.01.55.0005 | SEKAR WULAN AYU LISANTI | **Tabel: events**<br>- id (PK)<br>- name<br>- date<br>- location<br>- price |
| 21.01.55.0006 | LULUK TRI UTAMI | **Tabel: clinics**<br>- id (PK)<br>- name<br>- address<br>- phone<br>- schedule |
| 21.01.55.0007 | IRWAN ELSANDHY | **Tabel: games**<br>- id (PK)<br>- title<br>- genre<br>- price<br>- rating |
| 21.01.55.0008 | AMMABELL KEZIA ADHI SAPUTRI | **Tabel: clothes**<br>- id (PK)<br>- name<br>- size<br>- price<br>- stock |
| 21.01.55.0009 | KAILANA AL KAIS | **Tabel: gym_equipments**<br>- id (PK)<br>- name<br>- type<br>- condition<br>- quantity |
| 21.01.55.0010 | DIMAS LUTHFI AZIS | **Tabel: books**<br>- id (PK)<br>- title<br>- author<br>- year<br>- stock |
| 21.01.55.0011 | BRIGIDA MERILA WARDANI | **Tabel: rooms**<br>- id (PK)<br>- room_number<br>- type<br>- price<br>- status |
| 21.01.55.0013 | LINTANG PUTRA RISALDI | **Tabel: vehicles**<br>- id (PK)<br>- brand<br>- model<br>- year<br>- price |
| 21.01.55.0014 | TEGAR TAQIF YASSAR | **Tabel: electronics**<br>- id (PK)<br>- name<br>- brand<br>- price<br>- stock |
| 21.01.55.0015 | NAZHWA RESTA PRAMESTY | **Tabel: cakes**<br>- id (PK)<br>- name<br>- flavor<br>- price<br>- stock |
| 21.01.55.0016 | RANTI NURMALA SARI | **Tabel: medicines**<br>- id (PK)<br>- name<br>- type<br>- price<br>- stock |
| 21.01.55.0017 | NIKEN NOVIARDIANA | **Tabel: services**<br>- id (PK)<br>- name<br>- duration<br>- price<br>- category |
| 21.01.55.0018 | SALSA BILA NAQIYYAH | **Tabel: courses**<br>- id (PK)<br>- title<br>- instructor<br>- duration<br>- price |
| 21.01.55.0019 | SITI CHOIRIYAH | **Tabel: menus**<br>- id (PK)<br>- name<br>- category<br>- price<br>- portion |
| 21.01.55.0020 | APRILIANA SETYAWATI | **Tabel: flowers**<br>- id (PK)<br>- name<br>- color<br>- price<br>- stock |
| 21.01.55.0022 | ALICIA AYU NABILA | **Tabel: tickets**<br>- id (PK)<br>- destination<br>- date<br>- price<br>- stock |
| 21.01.55.0024 | MERITA CAHYA KURNIASARI | **Tabel: sports_equipment**<br>- id (PK)<br>- name<br>- type<br>- price<br>- stock |
| 21.01.55.0025 | ALYA MARIATUL KIFTIAH | **Tabel: instruments**<br>- id (PK)<br>- name<br>- type<br>- price<br>- stock |
| 21.01.65.0002 | BAGUS SETYA PUTRA | **Tabel: playstation_units**<br>- id (PK)<br>- unit_number<br>- type<br>- status<br>- hourly_rate |

## Deskripsi Tugas
Buatlah Web Service REST untuk sistem manajemen sesuai objek yang ditentukan menggunakan PHP dan MySQL. Web Service harus mendukung operasi CRUD (Create, Read, Update, Delete) dan diuji menggunakan Postman.

### Spesifikasi Teknis

#### 1. Database
- Buat 1 tabel sesuai dengan struktur yang telah ditentukan
- Gunakan tipe data yang sesuai untuk setiap kolom
- ID menggunakan auto_increment

#### 2. Endpoint API
Implementasikan endpoint berikut:

1. **GET** `/api/[objek]`
   - Menampilkan semua data
   - Mendukung pencarian berdasarkan nama/title

2. **GET** `/api/[objek]/{id}`
   - Menampilkan detail data berdasarkan ID
   - Response 404 jika data tidak ditemukan

3. **POST** `/api/[objek]`
   - Menambah data baru
   - Validasi input
   - Response 201 jika berhasil

4. **PUT** `/api/[objek]/{id}`
   - Mengupdate data berdasarkan ID
   - Validasi input
   - Response 404 jika data tidak ditemukan

5. **DELETE** `/api/[objek]/{id}`
   - Menghapus data berdasarkan ID
   - Response 404 jika data tidak ditemukan

### Format Response
```json
{
    "status": "success|error",
    "message": "Pesan informatif",
    "data": {
        // Data response
    }
}
```

### Keamanan
1. Validasi input untuk mencegah SQL Injection


## Dokumentasi yang Harus Dibuat
1. **README.md**
   - Nama dan NIM
   - Deskripsi project
   - Query SQL pembuatan tabel
   - Daftar endpoint API
   - Cara instalasi dan penggunaan

2. **Postman Collection**
   - Export collection Postman
   - Contoh request dan response untuk setiap endpoint

## Kriteria Penilaian
1. **Fungsionalitas (40%)**
   - Implementasi CRUD lengkap
   - Error handling
   - Validasi input
   - Pengujian endpoint

2. **Kode dan Struktur (30%)**
   - Organisasi kode
   - Penggunaan best practices
   - Komentar dan dokumentasi kode
   - Konsistensi coding style

3. **Keamanan (20%)**
   - Validasi Input MySQL

4. **Dokumentasi (10%)**
   - Kelengkapan dokumentasi
   - Postman collection
   - Petunjuk penggunaan

## Ketentuan Pengumpulan
1. Upload source code ke GitHub
2. Repository harus memiliki:
   - Source code lengkap
   - File SQL
   - README.md
   - Postman collection


## Catatan Penting
- Gunakan PHP versi 7.4 atau lebih tinggi
- Database wajib MySQL/MariaDB
- Repository GitHub harus public
- Commit history harus menunjukkan progres pengerjaan

*Selamat mengerjakan!*
