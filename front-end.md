# Modul Pembelajaran: Implementasi Front End REST API Web Service

## Identitas Modul
- Mata Kuliah: Pemrograman Web
- Materi: Implementasi Front End REST API Web Service
- Level: Mahasiswa Teknik Informatika
- Waktu: 2 x 50 menit

## Capaian Pembelajaran
Setelah mengikuti pembelajaran ini, mahasiswa diharapkan mampu:
1. Memahami konsep dasar REST API Web Service
2. Mengimplementasikan front end untuk mengakses REST API
3. Membuat tampilan responsif menggunakan Bootstrap
4. Mengelola data dari REST API menggunakan PHP
5. Mengimplementasikan fitur pencarian data melalui API

## A. Pendahuluan

### 1. Pengertian REST API
REST (Representational State Transfer) API adalah standar arsitektur komunikasi berbasis web yang sering diterapkan dalam pengembangan layanan berbasis web. REST API menggunakan HTTP protocol sebagai protokol komunikasi data.

### 2. Komponen yang Dibutuhkan
- Text Editor (VS Code/Sublime Text)
- XAMPP (PHP & MySQL)
- Web Browser
- Bootstrap 5 CSS Framework
- Koneksi Internet

## B. Langkah-langkah Implementasi

### 1. Persiapan Struktur Project
```
📁 rest_client
   ├── 📄 index.php
   └── 📄 styles.css (opsional)
```

### 2. Membuat Template Dasar HTML
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Konten akan ditambahkan di sini -->
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

### 3. Implementasi Form Pencarian
```html
<div class="container mt-5">
    <h2 class="mb-4">Daftar Buku</h2>
    
    <!-- Form Pencarian -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form action="" method="GET" class="d-flex gap-2">
                <input type="number" name="search_id" class="form-control" 
                       placeholder="Cari berdasarkan ID">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>
    </div>
</div>
```

### 4. Implementasi Tabel Data
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
            <!-- Data akan dimasukkan di sini menggunakan PHP -->
        </tbody>
    </table>
</div>
```

## C. Implementasi Pengambilan Data API

### 1. Penggunaan file_get_contents()
```php
// Konfigurasi timeout
$ctx = stream_context_create([
    'http' => [
        'timeout' => 5 // timeout dalam detik
    ]
]);

// URL API
$api_url = 'http://localhost/rest_buku/book_api.php';

// Mengambil data dari API
$response = file_get_contents($api_url, false, $ctx);
```

**Penjelasan:**
- `file_get_contents()`: Fungsi PHP untuk membaca file/URL ke dalam string
- Melakukan HTTP GET request ke URL API
- Parameter:
  - URL API yang akan diakses
  - `false`: penggunaan include path
  - `$ctx`: konteks stream untuk konfigurasi tambahan (timeout)

### 2. Implementasi Try-Catch
```php
try {
    // Set URL API
    $api_url = 'http://localhost/rest_buku/book_api.php';
    
    // Cek jika ada pencarian
    if(isset($_GET['search_id']) && !empty($_GET['search_id'])) {
        $api_url .= '/' . $_GET['search_id'];
    }
    
    // Konfigurasi timeout
    $ctx = stream_context_create([
        'http' => ['timeout' => 5]
    ]);
    
    // Ambil data dari API
    $response = file_get_contents($api_url, false, $ctx);
    
    // Konversi JSON ke Array
    $books = json_decode($response, true);
    
    // Proses data...
    
} catch (Exception $e) {
    echo "<tr><td colspan='4' class='text-center text-danger'>";
    echo "Error: " . $e->getMessage();
    echo "</td></tr>";
}
```

**Penjelasan Try-Catch:**
1. `try`: Blok kode yang mungkin menghasilkan error
2. `catch`: Menangkap dan menangani error
3. `Exception $e`: Objek yang menyimpan informasi error
4. Penggunaan try-catch penting untuk:
   - Menangani API tidak tersedia
   - Menangani error parsing JSON
   - Menangani timeout
   - Menangani network error

### 3. JSON Decode dan Struktur Data
```php
// Konversi JSON ke Array PHP
$books = json_decode($response, true);

/* 
Struktur data hasil konversi:
$books = [
    [
        "id" => 1,
        "title" => "PHP Basic",
        "author" => "John",
        "year" => 2023
    ],
    [
        "id" => 2,
        "title" => "MySQL Basic",
        "author" => "Jane",
        "year" => 2023
    ]
];
*/
```

### 4. Menampilkan Data dengan Foreach
```php
// Cek apakah ada data
if(!empty($books)) {
    // Loop untuk setiap buku
    foreach($books as $book) {
        echo "<tr>";
        echo "<td>{$book['id']}</td>";
        echo "<td>{$book['title']}</td>";
        echo "<td>{$book['author']}</td>";
        echo "<td>{$book['year']}</td>";
        echo "</tr>";
    }
} else {
    // Tampilkan pesan jika tidak ada data
    echo "<tr><td colspan='4' class='text-center'>";
    echo "Tidak ada data buku";
    echo "</td></tr>";
}
```

**Penjelasan Foreach:**
1. `!empty($books)`: Validasi keberadaan data
2. `foreach($books as $book)`: Iterasi untuk setiap buku
3. Mengakses data menggunakan array key
4. Membangun struktur HTML tabel

### 5. Penanganan Kasus Pencarian
```php
if(isset($_GET['search_id'])) {
    if($books && !isset($books['message'])) {
        // Tampilkan satu buku
        echo "<tr>";
        echo "<td>{$books['id']}</td>";
        echo "<td>{$books['title']}</td>";
        echo "<td>{$books['author']}</td>";
        echo "<td>{$books['year']}</td>";
        echo "</tr>";
    } else {
        // Tampilkan pesan tidak ditemukan
        echo "<tr><td colspan='4' class='text-center'>";
        echo "Buku dengan ID tersebut tidak ditemukan";
        echo "</td></tr>";
    }
}
```

## D. Best Practices dan Tips

### 1. Validasi Response API
```php
// Validasi response
if ($response === false) {
    throw new Exception('Gagal mengambil data dari API');
}

// Validasi JSON
$books = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    throw new Exception('Invalid JSON response');
}
```

### 2. Menggunakan Konstanta untuk Konfigurasi
```php
define('API_URL', 'http://localhost/rest_buku/book_api.php');
define('API_TIMEOUT', 5);
```

### 3. Alternative Method menggunakan cURL
```php
function getDataWithCurl($url) {
    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_FOLLOWLOCATION => true
    ]);
    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);
    
    if ($error) {
        throw new Exception($error);
    }
    
    return $response;
}
```

## E. Latihan Praktikum

1. **Latihan Dasar**
   - Implementasi daftar buku dengan REST API
   - Tambahkan fitur pencarian by ID

2. **Pengembangan**
   - Tambahkan fitur pencarian by judul
   - Implementasi pagination
   - Tambahkan sorting

3. **Tantangan**
   - Implementasi CRUD lengkap
   - Tambahkan validasi form
   - Implementasi caching response API

## F. Penilaian

### Komponen Penilaian:
1. Implementasi dasar (40%)
2. Penanganan error (20%)
3. Best practices (20%)
4. Pengembangan fitur (20%)

## G. Referensi
1. PHP Documentation: https://www.php.net/docs.php
2. Bootstrap Documentation: https://getbootstrap.com/
3. MDN Web Docs: https://developer.mozilla.org/
4. REST API Documentation Best Practices

## H. Troubleshooting

### 1. Error CORS
```php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
```

### 2. Error Timeout
```php
// Increase timeout
ini_set('default_socket_timeout', 10);
```

### 3. JSON Decode Error
```php
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_last_error_msg();
}
```
