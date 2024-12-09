# Modul Pembelajaran: Sistem Manajemen Buku Berbasis Web

## Daftar Isi
1. Pendahuluan
2. Prasyarat
3. Struktur Project
4. Implementasi Project
5. Pengujian
6. Troubleshooting

## 1. Pendahuluan

Modul ini akan mengajarkan cara membuat sistem manajemen buku berbasis web yang terdiri dari dua bagian:
- Backend API (REST Service)
- Frontend (Client Interface)

### Tujuan Pembelajaran:
- Memahami arsitektur client-server
- Implementasi REST API dengan PHP
- Pembuatan interface dengan HTML, JavaScript dan Bootstrap
- Integrasi frontend dan backend
- Manajemen database MySQL

## 2. Prasyarat

Software yang dibutuhkan:
- XAMPP (minimal versi 7.4)
- Text Editor (VSCode, Sublime Text, dll)
- Web Browser (Chrome/Firefox)
- Postman (opsional, untuk testing API)

Pengetahuan dasar:
- HTML, CSS, JavaScript
- PHP
- MySQL
- REST API konsep

## 3. Struktur Project

```
c:\xampp\htdocs\
├── rest_buku\                  # Folder Backend API
│   ├── book_api.php           # File REST API
│   └── config\
│       └── database.php       # Konfigurasi database
│
└── klien\                     # Folder Frontend
    └── index.html            # Interface pengguna
```

## 4. Implementasi Project

### A. Setup Database

```sql
CREATE DATABASE IF NOT EXISTS bookstore;
USE bookstore;

CREATE TABLE IF NOT EXISTS books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    year INT NOT NULL
);

-- Data awal
INSERT INTO books (title, author, year) VALUES
('To Kill a Mockingbird', 'Harper Lee', 1960),
('1984', 'George Orwell', 1949),
('Pride and Prejudice', 'Jane Austen', 1813),
('The Great Gatsby', 'F. Scott Fitzgerald', 1925),
('One Hundred Years of Solitude', 'Gabriel Garcia Márquez', 1967);
```

### B. Backend Implementation (c:\xampp\htdocs\rest_buku\book_api.php)

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

### C. Frontend Implementation (c:\xampp\htdocs\klien\index.html)

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-group-action {
            white-space: nowrap;
        }
    </style>
</head>
<body class="container py-4">
    <h1>Daftar Buku</h1>
    
    <div class="row mb-3">
        <div class="col">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan ID">
        </div>
        <div class="col-auto">
            <button onclick="searchBook()" class="btn btn-primary">Cari</button>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bookModal">
                Tambah Buku
            </button>
        </div>
    </div>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="bookList">
        </tbody>
    </table>

    <!-- Modal for Add/Edit Book -->
    <div class="modal fade" id="bookModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="bookForm">
                        <input type="hidden" id="bookId">
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Penulis</label>
                            <input type="text" class="form-control" id="author" required>
                        </div>
                        <div class="mb-3">
                            <label for="year" class="form-label">Tahun</label>
                            <input type="number" class="form-control" id="year" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveBook()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = 'http://localhost/rest_buku/book_api.php';
        let bookModal;

        document.addEventListener('DOMContentLoaded', function() {
            bookModal = new bootstrap.Modal(document.getElementById('bookModal'));
            loadBooks();
        });

        function loadBooks() {
            fetch(API_URL)
                .then(response => response.json())
                .then(books => {
                    const bookList = document.getElementById('bookList');
                    bookList.innerHTML = '';
                    books.forEach(book => {
                        bookList.innerHTML += `
                            <tr>
                                <td>${book.id}</td>
                                <td>${book.title}</td>
                                <td>${book.author}</td>
                                <td>${book.year}</td>
                                <td class="btn-group-action">
                                    <button class="btn btn-sm btn-warning me-1" onclick="editBook(${book.id})">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteBook(${book.id})">Hapus</button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(error => alert('Error loading books: ' + error));
        }

        function searchBook() {
            const id = document.getElementById('searchInput').value;
            if (!id) {
                loadBooks();
                return;
            }
            
            fetch(`${API_URL}/${id}`)
                .then(response => response.json())
                .then(book => {
                    const bookList = document.getElementById('bookList');
                    if (book.message) {
                        alert('Book not found');
                        return;
                    }
                    bookList.innerHTML = `
                        <tr>
                            <td>${book.id}</td>
                            <td>${book.title}</td>
                            <td>${book.author}</td>
                            <td>${book.year}</td>
                            <td class="btn-group-action">
                                <button class="btn btn-sm btn-warning me-1" onclick="editBook(${book.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteBook(${book.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                })
                .catch(error => alert('Error searching book: ' + error));
        }

        function editBook(id) {
            fetch(`${API_URL}/${id}`)
                .then(response => response.json())
                .then(book => {
                    document.getElementById('bookId').value = book.id;
                    document.getElementById('title').value = book.title;
                    document.getElementById('author').value = book.author;
                    document.getElementById('year').value = book.year;
                    document.getElementById('modalTitle').textContent = 'Edit Buku';
                    bookModal.show();
                })
                .catch(error => alert('Error loading book details: ' + error));
        }

        function deleteBook(id) {
            if (confirm('Are you sure you want to delete this book?')) {
                fetch(`${API_URL}/${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    alert('Book deleted successfully');
                    loadBooks();
                })
                .catch(error => alert('Error deleting book: ' + error));
            }
        }

        function saveBook() {
            const bookId = document.getElementById('bookId').value;
            const bookData = {
                title: document.getElementById('title').value,
                author: document.getElementById('author').value,
                year: document.getElementById('year').value
            };

            const method = bookId ? 'PUT' : 'POST';
            const url = bookId ? `${API_URL}/${bookId}` : API_URL;

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(bookData)
            })
            .then(response => response.json())
            .then(data => {
                alert(bookId ? 'Book updated successfully' : 'Book added successfully');
                bookModal.hide();
                loadBooks();
                resetForm();
            })
            .catch(error => alert('Error saving book: ' + error));
        }

        function resetForm() {
            document.getElementById('bookId').value = '';
            document.getElementById('bookForm').reset();
            document.getElementById('modalTitle').textContent = 'Tambah Buku';
        }

        // Reset form when modal is closed
        document.getElementById('bookModal').addEventListener('hidden.bs.modal', resetForm);
    </script>
</body>
</html>
```

## 5. Langkah Implementasi

### Langkah 1: Setup XAMPP
1. Install XAMPP
2. Start Apache dan MySQL
3. Buka phpMyAdmin: `http://localhost/phpmyadmin`
4. Buat database dan table menggunakan SQL di atas

### Langkah 2: Setup Project
1. Buat folder `rest_buku` di `c:\xampp\htdocs\`
2. Buat folder `klien` di `c:\xampp\htdocs\`
3. Copy file API ke `rest_buku\book_api.php`
4. Copy file interface ke `klien\index.html`

### Langkah 3: Testing API
1. Buka browser
2. Akses `http://localhost/rest_buku/book_api.php`
3. Seharusnya muncul data JSON
4. Jika error, periksa:
   - Apache running
   - File permissions
   - Database connection
   - PHP error log

### Langkah 4: Testing Interface
1. Buka `http://localhost/klien/index.html`
2. Test fitur-fitur:
   - Tampil data
   - Pencarian
   - Tambah buku
   - Edit buku
   - Hapus buku

## 6. Troubleshooting

### Common Errors:

1. **CORS Error**
   ```
   Access to fetch at 'http://localhost/rest_buku/book_api.php' from origin 'http://localhost' has been blocked by CORS policy
   ```
   Solusi: Pastikan header CORS di API sudah benar

2. **Database Connection Error**
   ```
   SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'
   ```
   Solusi: 
   - Check database credentials
   - Pastikan MySQL running
   - Reset password root jika perlu

3. **404 Not Found**
   ```
   GET http://localhost/rest_buku/book_api.php 404 (Not Found)
   ```
   Solusi:
   - Check path file
   - Pastikan nama file benar
   - Periksa case sensitivity

4. **JSON Parse Error**
   ```
   SyntaxError: Unexpected token < in JSON at position 0
   ```
   Solusi:
   - Pastikan API mengembalikan JSON valid
   - Check PHP errors
   - Periksa Content-Type header

## 7. Pengembangan Lanjutan

1. Tambah Fitur:
   - Authentication
   - Upload gambar buku
   - Export PDF/Excel
   - Filter dan sorting

2. Improve Security:
   - Input validation
   - SQL injection prevention
   - XSS protection
   - CSRF protection

3. Improve UI/UX:
   - Responsive design
   - Loading indicators
   - Better error handling
   - Form validation

## 8. Evaluasi

Kriteria penilaian:
1. Implementasi CRUD
2. Error handling
3. Code organization
4. UI/UX
5. Documentation

## 9. Resources

1. Documentation:
   - PHP: https://www.php.net/docs.php
   - Bootstrap: https://getbootstrap.com/
   - JavaScript: https://developer.mozilla.org/
   - MySQL: https://dev.mysql.com/doc/

2. Tools:
   - XAMPP: https://www.apachefriends.org/
   - Postman: https://www.postman.com/
   - VSCode: https://code.visualstudio.com/
