# Modul Praktikum: Web Service REST untuk Manajemen Buku

## Tujuan
Membuat dan menguji web service REST untuk manajemen buku menggunakan PHP dan MySQL.

## Alat yang Dibutuhkan
1. XAMPP (atau server web lain dengan PHP dan MySQL)
2. Text editor (misalnya Visual Studio Code, Notepad++, dll)
3. Postman

## Langkah-langkah Praktikum

### 1. Persiapan Lingkungan
1. Instal XAMPP jika belum ada.
2. Buat folder baru bernama `rest_buku` di dalam direktori `htdocs` XAMPP Anda.

### 2. Membuat Database
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Buat database baru bernama `bookstore`
3. Pilih database `bookstore`, lalu buka tab SQL
4. Jalankan query SQL berikut untuk membuat tabel dan menambahkan data sampel:

```sql
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    year INT NOT NULL
);

INSERT INTO books (title, author, year) VALUES
('To Kill a Mockingbird', 'Harper Lee', 1960),
('1984', 'George Orwell', 1949),
('Pride and Prejudice', 'Jane Austen', 1813),
('The Great Gatsby', 'F. Scott Fitzgerald', 1925),
('One Hundred Years of Solitude', 'Gabriel García Márquez', 1967);
```

### 3. Membuat File PHP untuk Web Service
1. Buka text editor Anda.
2. Buat file baru dan simpan sebagai `book_api.php` di dalam folder `rest_buku`.
3. Salin dan tempel kode berikut ke dalam `book_api.php`:

```php
<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
$request = [];

if (isset($_SERVER['PATH_INFO'])) {
    $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
}

function getConnection() {
    $host = 'localhost';
    $db   = 'bookstore';
    $user = 'root';
    $pass = ''; // Ganti dengan password MySQL Anda jika ada
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}

function response($status, $data = NULL) {
    header("HTTP/1.1 " . $status);
    if ($data) {
        echo json_encode($data);
    }
    exit();
}

$db = getConnection();

switch ($method) {
    case 'GET':
        if (!empty($request) && isset($request[0])) {
            $id = $request[0];
            $stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$id]);
            $book = $stmt->fetch();
            if ($book) {
                response(200, $book);
            } else {
                response(404, ["message" => "Book not found"]);
            }
        } else {
            $stmt = $db->query("SELECT * FROM books");
            $books = $stmt->fetchAll();
            response(200, $books);
        }
        break;
    
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->title) || !isset($data->author) || !isset($data->year)) {
            response(400, ["message" => "Missing required fields"]);
        }
        $sql = "INSERT INTO books (title, author, year) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$data->title, $data->author, $data->year])) {
            response(201, ["message" => "Book created", "id" => $db->lastInsertId()]);
        } else {
            response(500, ["message" => "Failed to create book"]);
        }
        break;
    
    case 'PUT':
        if (empty($request) || !isset($request[0])) {
            response(400, ["message" => "Book ID is required"]);
        }
        $id = $request[0];
        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->title) || !isset($data->author) || !isset($data->year)) {
            response(400, ["message" => "Missing required fields"]);
        }
        $sql = "UPDATE books SET title = ?, author = ?, year = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$data->title, $data->author, $data->year, $id])) {
            response(200, ["message" => "Book updated"]);
        } else {
            response(500, ["message" => "Failed to update book"]);
        }
        break;
    
    case 'DELETE':
        if (empty($request) || !isset($request[0])) {
            response(400, ["message" => "Book ID is required"]);
        }
        $id = $request[0];
        $sql = "DELETE FROM books WHERE id = ?";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$id])) {
            response(200, ["message" => "Book deleted"]);
        } else {
            response(500, ["message" => "Failed to delete book"]);
        }
        break;
    
    default:
        response(405, ["message" => "Method not allowed"]);
        break;
}
?>
```

### 4. Pengujian dengan Postman
1. Buka Postman
2. Buat request baru untuk setiap operasi berikut:

#### a. GET All Books
- Method: GET
- URL: `http://localhost/rest_buku/book_api.php`
- Klik "Send"

#### b. GET Specific Book
- Method: GET
- URL: `http://localhost/rest_buku/book_api.php/1` (untuk buku dengan ID 1)
- Klik "Send"

#### c. POST New Book
- Method: POST
- URL: `http://localhost/rest_buku/book_api.php`
- Headers: 
  - Key: Content-Type
  - Value: application/json
- Body:
  - Pilih "raw" dan "JSON"
  - Masukkan:
    ```json
    {
        "title": "The Hobbit",
        "author": "J.R.R. Tolkien",
        "year": 1937
    }
    ```
- Klik "Send"

#### d. PUT (Update) Book
- Method: PUT
- URL: `http://localhost/rest_buku/book_api.php/6` (asumsikan ID buku baru adalah 6)
- Headers: 
  - Key: Content-Type
  - Value: application/json
- Body:
  - Pilih "raw" dan "JSON"
  - Masukkan:
    ```json
    {
        "title": "The Hobbit: An Unexpected Journey",
        "author": "J.R.R. Tolkien",
        "year": 1937
    }
    ```
- Klik "Send"

#### e. DELETE Book
- Method: DELETE
- URL: `http://localhost/rest_buku/book_api.php/6` (untuk menghapus buku dengan ID 6)
- Klik "Send"

### 5. Latihan Tambahan
1. Tambahkan fitur pencarian buku berdasarkan judul atau penulis.
2. Implementasikan paginasi untuk mendapatkan buku.
3. Tambahkan validasi input yang lebih ketat (misalnya, tahun harus 4 digit).
4. Buat dokumentasi API sederhana menggunakan Markdown atau HTML.

### Kesimpulan
Dalam praktikum ini, Anda telah berhasil membuat web service REST untuk manajemen buku menggunakan PHP dan MySQL. Anda juga telah belajar cara menguji API menggunakan Postman. Praktik ini memberikan dasar yang kuat untuk pengembangan API RESTful lebih lanjut.
