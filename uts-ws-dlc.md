# Ujian Tengah Semester - Web Service Development Kelas DLC
## REST API Development dengan PHP dan MySQL

### Pembagian Objek REST API
| NIM | Nama | Objek API & Struktur Tabel |
|-----|------|---------------------------|
| 21.01.55.3001 | AYUSTIN ANJELITA NURYANTO | **Tabel: movies**<br>- id (PK)<br>- title<br>- genre<br>- release_year<br>- rating |
| 22.01.55.6003 | NOVIA ADRIANI | **Tabel: recipes**<br>- id (PK)<br>- name<br>- cuisine_type<br>- cooking_time<br>- difficulty |
| 22.01.55.6007 | PRATIWI WINASTUTI | **Tabel: pets**<br>- id (PK)<br>- name<br>- species<br>- age<br>- status |
| 22.01.55.6008 | NABIEL PRAMUDYA MAHENDRA | **Tabel: games**<br>- id (PK)<br>- title<br>- genre<br>- release_year<br>- price |
| 23.01.55.6002 | TRIYANTO | **Tabel: gadgets**<br>- id (PK)<br>- name<br>- brand<br>- price<br>- stock |
| 24.01.55.7003 | RETNO SILVIA NINGRUM | **Tabel: plants**<br>- id (PK)<br>- name<br>- type<br>- price<br>- stock |

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
1. Implementasi Authentication menggunakan JWT
2. Validasi input untuk mencegah SQL Injection
3. Sanitasi output

## Dokumentasi yang Harus Dibuat
1. **README.md**
   - Nama dan NIM
   - Deskripsi project
   - Query SQL pembuatan tabel
   - Daftar endpoint API
   - Cara instalasi dan penggunaan
   - Screenshot hasil pengujian di Postman

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
   - Implementasi authentication
   - Validasi dan sanitasi
   - Error handling

4. **Dokumentasi (10%)**
   - Kelengkapan dokumentasi
   - Postman collection
   - Screenshot pengujian
   - Petunjuk penggunaan

## Ketentuan Pengumpulan
1. Upload source code ke GitHub
2. Repository harus memiliki:
   - Source code lengkap
   - File SQL
   - README.md dengan screenshot
   - Postman collection

## Template Query SQL
Contoh untuk tabel movies:
```sql
CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    release_year INT NOT NULL,
    rating DECIMAL(3,1) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Deadline
- Pengumpulan: 12 November 2024

## Catatan Penting
- Gunakan PHP versi 7.4 atau lebih tinggi
- Database wajib MySQL/MariaDB
- Repository GitHub harus public
- Commit history harus menunjukkan progres pengerjaan
- Sertakan screenshot hasil pengujian di README.md
- Pastikan dokumentasi jelas dan lengkap

## Bonus Point
- Implementasi pencarian dengan multiple parameter
- Implementasi sorting
- Implementasi pagination
- Unit testing
- Dokumentasi API menggunakan Swagger/OpenAPI

*Selamat mengerjakan!*
