Solusi untuk latihan tambahan 5.1 hingga 5.3 dari modul praktikum yang telah kita buat. Mari kita implementasikan fitur pencarian, paginasi, dan validasi input yang lebih ketat.

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

function validateBook($title, $author, $year) {
    $errors = [];
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    if (empty($author)) {
        $errors[] = "Author is required";
    }
    if (!is_numeric($year) || strlen($year) !== 4) {
        $errors[] = "Year must be a 4-digit number";
    }
    return $errors;
}

$db = getConnection();

switch ($method) {
    case 'GET':
        if (!empty($request) && isset($request[0])) {
            if ($request[0] === 'search') {
                // 5.1 Search functionality
                $searchTerm = $_GET['term'] ?? '';
                $stmt = $db->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
                $searchTerm = "%$searchTerm%";
                $stmt->execute([$searchTerm, $searchTerm]);
                $books = $stmt->fetchAll();
                response(200, $books);
            } else {
                // Get specific book
                $id = $request[0];
                $stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
                $stmt->execute([$id]);
                $book = $stmt->fetch();
                if ($book) {
                    response(200, $book);
                } else {
                    response(404, ["message" => "Book not found"]);
                }
            }
        } else {
            // 5.2 Pagination
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $offset = ($page - 1) * $limit;

            $stmt = $db->prepare("SELECT * FROM books LIMIT ? OFFSET ?");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            $books = $stmt->fetchAll();

            $totalStmt = $db->query("SELECT COUNT(*) FROM books");
            $total = $totalStmt->fetchColumn();

            response(200, [
                'books' => $books,
                'total' => $total,
                'page' => $page,
                'limit' => $limit
            ]);
        }
        break;
    
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        // 5.3 Stricter input validation
        $errors = validateBook($data->title ?? '', $data->author ?? '', $data->year ?? '');
        if (!empty($errors)) {
            response(400, ["errors" => $errors]);
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
        // 5.3 Stricter input validation
        $errors = validateBook($data->title ?? '', $data->author ?? '', $data->year ?? '');
        if (!empty($errors)) {
            response(400, ["errors" => $errors]);
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

Penjelasan implementasi:

1. Fitur Pencarian (5.1):
   - Ditambahkan endpoint baru: GET `/rest_buku/book_api.php/search?term=your_search_term`
   - Menggunakan query LIKE untuk mencari buku berdasarkan judul atau penulis
   - Contoh penggunaan di Postman: GET `http://localhost/rest_buku/book_api.php/search?term=Tolkien`

2. Paginasi (5.2):
   - Ditambahkan ke endpoint GET utama
   - Menggunakan parameter `page` dan `limit` untuk mengontrol paginasi
   - Mengembalikan total buku, halaman saat ini, dan limit per halaman
   - Contoh penggunaan di Postman: GET `http://localhost/rest_buku/book_api.php?page=1&limit=5`

3. Validasi Input yang Lebih Ketat (5.3):
   - Ditambahkan fungsi `validateBook()` untuk memeriksa input
   - Memastikan judul dan penulis tidak kosong
   - Memvalidasi tahun sebagai angka 4 digit
   - Diterapkan pada operasi POST dan PUT

Untuk menguji fitur-fitur baru ini di Postman:

1. Pencarian:
   - Method: GET
   - URL: `http://localhost/rest_buku/book_api.php/search?term=Tolkien`

2. Paginasi:
   - Method: GET
   - URL: `http://localhost/rest_buku/book_api.php?page=1&limit=5`

3. Validasi Input (contoh untuk POST):
   - Method: POST
   - URL: `http://localhost/rest_buku/book_api.php`
   - Headers: 
     - Key: Content-Type
     - Value: application/json
   - Body (raw JSON):
     ```json
     {
         "title": "Invalid Book",
         "author": "Test Author",
         "year": "20xx"
     }
     ```
   - Ini akan menghasilkan error karena tahun tidak valid

Dengan implementasi ini, API buku Anda sekarang memiliki fitur pencarian, paginasi, dan validasi input yang lebih kuat, meningkatkan fungsionalitas dan keamanannya.
