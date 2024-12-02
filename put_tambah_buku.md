# Sistem Manajemen Buku dengan REST API

## 1. Struktur HTML Dasar
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
```
**Penjelasan:**
- `<!DOCTYPE html>`: Mendeklarasikan dokumen sebagai HTML5
- `<meta charset="UTF-8">`: Mengatur encoding karakter untuk mendukung berbagai bahasa
- `<meta name="viewport">`: Mengoptimalkan tampilan untuk perangkat mobile
- Link Bootstrap: Mengimpor CSS Bootstrap untuk styling

## 2. Form Pencarian dan Tombol Tambah
```html
<div class="row mb-4">
    <div class="col-md-6">
        <form action="" method="GET" class="d-flex gap-2">
            <input type="number" name="search_id" class="form-control" 
                   placeholder="Cari berdasarkan ID">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>
    <div class="col-md-6 text-end">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBookModal">
            Tambah Buku
        </button>
    </div>
</div>
```
**Penjelasan:**
- Layout menggunakan sistem grid Bootstrap (`row` dan `col-md-6`)
- Form pencarian menggunakan method GET untuk memudahkan bookmarking
- Tombol tambah menggunakan atribut `data-bs-toggle` dan `data-bs-target` untuk memunculkan modal

## 3. Tabel dan Struktur Data
```html
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun</th>
            </tr>
        </thead>
        <tbody>
```
**Penjelasan:**
- `table-responsive`: Memungkinkan scrolling horizontal pada layar kecil
- Menggunakan kelas Bootstrap untuk styling tabel
- Header tabel menggunakan `table-dark` untuk kontras visual

## 4. Koneksi dan Konfigurasi API
```php
try {
    $api_url = 'http://localhost/rest_buku/book_api.php';
    
    if(isset($_GET['search_id']) && !empty($_GET['search_id'])) {
        $api_url .= '/' . $_GET['search_id'];
    }
    
    $ctx = stream_context_create([
        'http' => ['timeout' => 5]
    ]);
```
**Penjelasan:**
- Menggunakan `try-catch` untuk handling error
- URL API dimodifikasi berdasarkan ada/tidaknya parameter pencarian
- Mengatur timeout 5 detik untuk mencegah hanging

## 5. Pengambilan dan Validasi Data
```php
$response = file_get_contents($api_url, false, $ctx);

if ($response === false) {
    throw new Exception('Gagal mengambil data dari API');
}

$books = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception('Invalid JSON response');
}
```
**Penjelasan:**
- Menggunakan `file_get_contents()` untuk HTTP GET request
- Validasi response untuk menangkap kegagalan koneksi
- Validasi JSON untuk memastikan format data valid

## 6. Logika Tampilan Data
```php
if(isset($_GET['search_id'])) {
    if($books && !isset($books['message'])) {
        // Tampilkan satu buku
        echo "<tr>";
        echo "<td>{$books['id']}</td>";
        // ...
    } else {
        // Tampilkan pesan tidak ditemukan
        echo "<tr><td colspan='4' class='text-center'>";
        echo "Buku dengan ID tersebut tidak ditemukan";
        echo "</td></tr>";
    }
}
```
**Penjelasan:**
- Logika berbeda untuk pencarian ID dan tampilan semua buku
- Handling kasus tidak ada data
- Formatting output menggunakan HTML table

## 7. Modal Form Tambah Buku
```html
<div class="modal fade" id="addBookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Buku Baru</h5>
                <!-- ... -->
            </div>
            <!-- ... -->
        </div>
    </div>
</div>
```
**Penjelasan:**
- Menggunakan komponen Modal Bootstrap
- Form input untuk data buku baru
- Struktur modal: header, body, footer

## 8. JavaScript untuk Penambahan Data
```javascript
function addBook() {
    const form = document.getElementById('addBookForm');
    const formData = new FormData(form);
    const data = {
        title: formData.get('title'),
        author: formData.get('author'),
        year: parseInt(formData.get('year'))
    };

    fetch('http://localhost/rest_buku/book_api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    // ...
}
```
**Penjelasan:**
- Menggunakan `FormData` untuk mengambil data form
- `fetch` API untuk melakukan HTTP POST request
- Konversi data ke format JSON
- Handling response dan error

## 9. REST API (book_api.php)
```php
switch ($method) {
    case 'GET':
        // Logika untuk mengambil data
        break;
    case 'POST':
        // Logika untuk menambah data
        break;
    // ...
}
```
**Fitur-fitur API:**
- Mendukung operasi CRUD lengkap
- Validasi input
- Penanganan error
- Response dalam format JSON
- Menggunakan PDO untuk keamanan database

## Kesimpulan
Sistem ini mendemonstrasikan:
1. Integrasi frontend dan backend
2. Penggunaan REST API
3. Error handling yang baik
4. Responsive design dengan Bootstrap
5. JavaScript untuk interaksi dinamis
6. Keamanan database dengan PDO

## Latihan
1. Tambahkan fitur edit buku
2. Implementasikan fitur hapus buku
3. Tambahkan validasi form di sisi client
4. Implementasikan paginasi untuk data buku
5. Tambahkan fitur sorting berdasarkan kolom
